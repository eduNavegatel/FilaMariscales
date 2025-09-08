<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Cargar configuración
require_once '../src/config/config.php';

// Función para enviar email usando servicio externo (simulado)
function enviarEmailExterno($email) {
    // Simular envío exitoso (en producción usarías un servicio real)
    $asunto = "¡Bienvenido al boletín de la Filá Mariscales!";
    
    $mensaje = "
    ¡Bienvenido a la Filá Mariscales de Caballeros Templarios!

    Estimado/a amigo/a,

    Nos complace confirmar que te has suscrito exitosamente a nuestro boletín de noticias.

    A partir de ahora recibirás:
    • Las últimas noticias y eventos de la filá
    • Información sobre próximas actividades
    • Noticias sobre la tradición templaria
    • Actualizaciones de las Fiestas de Moros y Cristianos

    Tu dirección de correo electrónico ($email) ha sido añadida a nuestra lista de suscriptores.

    Puedes visitar nuestra web en: http://localhost/prueba-php/public/

    Si no deseas recibir más emails, puedes darte de baja respondiendo a este correo.

    --
    Filá Mariscales de Caballeros Templarios de Elche
    Email: edu300572@gmail.com
    ";
    
    // Log del email (para desarrollo)
    $logEntry = date('Y-m-d H:i:s') . " | Email enviado a: $email | Asunto: $asunto\n";
    file_put_contents('../uploads/email-log.txt', $logEntry, FILE_APPEND | LOCK_EX);
    
    // Simular éxito (en producción aquí iría la llamada al servicio externo)
    return true;
}

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
                user_agent TEXT
            )
        ";
        $pdo->exec($createTable);
        
        // Insertar suscripción
        $stmt = $pdo->prepare("
            INSERT INTO newsletter_subscriptions (email, ip_address, user_agent) 
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE 
            fecha_suscripcion = CURRENT_TIMESTAMP,
            activo = TRUE
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
    
    // Enviar email
    $emailEnviado = enviarEmailExterno($email);
    
    if ($emailEnviado) {
        echo json_encode([
            'success' => true,
            'message' => '¡Suscripción exitosa! El email se ha enviado correctamente.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error al enviar el email. La suscripción se guardó correctamente.'
        ]);
    }
    
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.'
    ]);
}
?>
