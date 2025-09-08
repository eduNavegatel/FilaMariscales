<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Cargar configuración
require_once '../src/config/config.php';

// Función para enviar email simple
function enviarEmailSimple($email) {
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
    
    $headers = "From: Filá Mariscales <edu300572@gmail.com>\r\n";
    $headers .= "Reply-To: edu300572@gmail.com\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
    
    return mail($email, $asunto, $mensaje, $headers);
}

// Función para guardar suscripción en archivo (alternativa a base de datos)
function guardarSuscripcionArchivo($email) {
    $archivo = '../uploads/suscripciones.txt';
    $fecha = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    
    $linea = "$fecha | $email | $ip | $userAgent\n";
    
    return file_put_contents($archivo, $linea, FILE_APPEND | LOCK_EX) !== false;
}

// Función para guardar suscripción en base de datos
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
        // Fallback a archivo si falla la base de datos
        return guardarSuscripcionArchivo($email);
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
    
    // Intentar enviar email
    $emailEnviado = enviarEmailSimple($email);
    
    if ($emailEnviado) {
        echo json_encode([
            'success' => true,
            'message' => '¡Suscripción exitosa! Revisa tu correo electrónico para confirmar.'
        ]);
    } else {
        // Aunque falle el email, la suscripción se guardó
        echo json_encode([
            'success' => true,
            'message' => 'Suscripción completada. El email se enviará cuando el servidor esté configurado.'
        ]);
    }
    
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.'
    ]);
}
?>
