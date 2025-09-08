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
                metodo_envio VARCHAR(50) DEFAULT 'formsubmit'
            )
        ";
        $pdo->exec($createTable);
        
        // Insertar suscripción
        $stmt = $pdo->prepare("
            INSERT INTO newsletter_subscriptions (email, ip_address, user_agent, metodo_envio) 
            VALUES (?, ?, ?, 'formsubmit')
            ON DUPLICATE KEY UPDATE 
            fecha_suscripcion = CURRENT_TIMESTAMP,
            activo = TRUE,
            metodo_envio = 'formsubmit'
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

// Función para crear log de suscripción
function crearLogSuscripcion($email) {
    $logEntry = [
        'fecha' => date('Y-m-d H:i:s'),
        'email' => $email,
        'accion' => 'suscripcion_newsletter',
        'metodo' => 'formsubmit',
        'estado' => 'procesado'
    ];
    
    // Guardar en archivo JSON
    $logFile = '../uploads/newsletter-formsubmit.json';
    $logs = [];
    
    if (file_exists($logFile)) {
        $logs = json_decode(file_get_contents($logFile), true) ?: [];
    }
    
    $logs[] = $logEntry;
    file_put_contents($logFile, json_encode($logs, JSON_PRETTY_PRINT));
    
    // También guardar en formato texto
    $textLog = date('Y-m-d H:i:s') . " | Newsletter FormSubmit | Email: $email | Estado: Suscripción procesada\n";
    file_put_contents('../uploads/newsletter-log.txt', $textLog, FILE_APPEND | LOCK_EX);
    
    return true;
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
    
    // Crear log
    $logCreado = crearLogSuscripcion($email);
    
    // Devolver URL de FormSubmit para el envío del email
    $formSubmitUrl = "https://formsubmit.co/edu300572@gmail.com";
    $formData = [
        'email' => $email,
        '_subject' => 'Nueva suscripción al newsletter - Filá Mariscales',
        '_next' => 'http://localhost/prueba-php/public/noticias?suscrito=true',
        '_captcha' => 'false',
        '_template' => 'table',
        'mensaje' => "Nueva suscripción al newsletter de la Filá Mariscales de Caballeros Templarios.\n\nEmail: $email\nFecha: " . date('d/m/Y H:i:s') . "\nIP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown')
    ];
    
    echo json_encode([
        'success' => true,
        'message' => '¡Suscripción exitosa! El email se enviará automáticamente.',
        'formSubmitUrl' => $formSubmitUrl,
        'formData' => $formData
    ]);
    
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.'
    ]);
}
?>
