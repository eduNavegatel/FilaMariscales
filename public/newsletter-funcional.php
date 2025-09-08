<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Cargar configuración
require_once '../src/config/config.php';

// Función para guardar suscripción
function guardarSuscripcion($email) {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Crear tabla si no existe
        $createTable = "
            CREATE TABLE IF NOT EXISTS newsletter_subscriptions (
                id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(255) UNIQUE NOT NULL,
                fecha_suscripcion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                activo BOOLEAN DEFAULT TRUE,
                ip_address VARCHAR(45),
                user_agent TEXT,
                metodo_envio VARCHAR(50) DEFAULT 'funcional'
            )
        ";
        $pdo->exec($createTable);
        
        // Insertar suscripción
        $stmt = $pdo->prepare("
            INSERT INTO newsletter_subscriptions (email, ip_address, user_agent, metodo_envio) 
            VALUES (?, ?, ?, 'funcional')
            ON DUPLICATE KEY UPDATE 
            fecha_suscripcion = CURRENT_TIMESTAMP,
            activo = TRUE,
            metodo_envio = 'funcional'
        ");
        
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        
        $stmt->execute([$email, $ip, $userAgent]);
        
        return true;
    } catch (PDOException $e) {
        error_log("Error en base de datos: " . $e->getMessage());
        return false;
    }
}

// Función para procesar email (sin envío real)
function procesarEmail($email) {
    // Crear log del email
    $logEntry = [
        'fecha' => date('Y-m-d H:i:s'),
        'email' => $email,
        'asunto' => '¡Bienvenido al boletín de la Filá Mariscales!',
        'estado' => 'procesado',
        'metodo' => 'sistema_funcional'
    ];
    
    // Guardar en archivo de log
    $logFile = '../uploads/email-processed.json';
    $logs = [];
    
    if (file_exists($logFile)) {
        $logs = json_decode(file_get_contents($logFile), true) ?: [];
    }
    
    $logs[] = $logEntry;
    file_put_contents($logFile, json_encode($logs, JSON_PRETTY_PRINT));
    
    // También guardar en formato texto
    $textLog = date('Y-m-d H:i:s') . " | Email procesado | Para: $email | Estado: Suscripción exitosa\n";
    file_put_contents('../uploads/email-log.txt', $textLog, FILE_APPEND | LOCK_EX);
    
    return true;
}

// Función para crear resumen de suscripciones
function crearResumenSuscripciones() {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM newsletter_subscriptions WHERE activo = 1");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['total'] ?? 0;
    } catch (PDOException $e) {
        return 0;
    }
}

// Procesar la petición
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        $input = $_POST;
    }
    
    $email = trim($input['email'] ?? '');
    $privacyAccepted = $input['privacy'] ?? false;
    
    // Validaciones
    if (empty($email)) {
        echo json_encode([
            'success' => false,
            'message' => 'Por favor, introduce tu dirección de correo electrónico.'
        ]);
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'success' => false,
            'message' => 'Por favor, introduce una dirección de correo electrónico válida.'
        ]);
        exit;
    }
    
    if (!$privacyAccepted) {
        echo json_encode([
            'success' => false,
            'message' => 'Debes aceptar la política de privacidad para continuar.'
        ]);
        exit;
    }
    
    // Guardar suscripción
    $guardado = guardarSuscripcion($email);
    
    if (!$guardado) {
        echo json_encode([
            'success' => false,
            'message' => 'Error al procesar la suscripción. Inténtalo de nuevo.'
        ]);
        exit;
    }
    
    // Procesar email
    $emailProcesado = procesarEmail($email);
    
    if ($emailProcesado) {
        $totalSuscripciones = crearResumenSuscripciones();
        
        echo json_encode([
            'success' => true,
            'message' => "¡Suscripción exitosa! Tu email ha sido registrado correctamente. Total de suscriptores: $totalSuscripciones"
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error al procesar el email. La suscripción se guardó correctamente.'
        ]);
    }
    
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.'
    ]);
}
?>
