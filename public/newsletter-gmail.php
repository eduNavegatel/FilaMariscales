<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Cargar configuración
require_once '../src/config/config.php';

// Función para enviar email usando Gmail SMTP (sin PHPMailer)
function enviarEmailGmail($destinatario, $asunto, $mensaje) {
    // Configuración Gmail
    $smtp_host = 'smtp.gmail.com';
    $smtp_port = 587;
    $username = 'edu300572@gmail.com';
    $password = 'granmaestre2024';
    
    // Crear conexión SMTP
    $socket = fsockopen($smtp_host, $smtp_port, $errno, $errstr, 30);
    
    if (!$socket) {
        return false;
    }
    
    // Leer respuesta inicial
    $response = fgets($socket, 515);
    
    // EHLO
    fputs($socket, "EHLO localhost\r\n");
    $response = fgets($socket, 515);
    
    // STARTTLS
    fputs($socket, "STARTTLS\r\n");
    $response = fgets($socket, 515);
    
    // Iniciar TLS
    stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
    
    // EHLO otra vez después de TLS
    fputs($socket, "EHLO localhost\r\n");
    $response = fgets($socket, 515);
    
    // AUTH LOGIN
    fputs($socket, "AUTH LOGIN\r\n");
    $response = fgets($socket, 515);
    
    // Username (base64)
    fputs($socket, base64_encode($username) . "\r\n");
    $response = fgets($socket, 515);
    
    // Password (base64)
    fputs($socket, base64_encode($password) . "\r\n");
    $response = fgets($socket, 515);
    
    // MAIL FROM
    fputs($socket, "MAIL FROM: <$username>\r\n");
    $response = fgets($socket, 515);
    
    // RCPT TO
    fputs($socket, "RCPT TO: <$destinatario>\r\n");
    $response = fgets($socket, 515);
    
    // DATA
    fputs($socket, "DATA\r\n");
    $response = fgets($socket, 515);
    
    // Headers y mensaje
    $email_data = "From: Filá Mariscales <$username>\r\n";
    $email_data .= "To: $destinatario\r\n";
    $email_data .= "Subject: $asunto\r\n";
    $email_data .= "MIME-Version: 1.0\r\n";
    $email_data .= "Content-Type: text/html; charset=UTF-8\r\n";
    $email_data .= "\r\n";
    $email_data .= $mensaje . "\r\n";
    $email_data .= ".\r\n";
    
    fputs($socket, $email_data);
    $response = fgets($socket, 515);
    
    // QUIT
    fputs($socket, "QUIT\r\n");
    $response = fgets($socket, 515);
    
    fclose($socket);
    
    return strpos($response, '250') === 0;
}

// Función para crear email HTML
function crearEmailNewsletter($email) {
    $asunto = "¡Bienvenido al boletín de la Filá Mariscales!";
    
    $mensaje = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; }
            .header { background: #dc143c; color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
            .content { padding: 20px; background: #f9f9f9; }
            .footer { background: #333; color: white; padding: 15px; text-align: center; font-size: 12px; border-radius: 0 0 10px 10px; }
            .button { background: #dc143c; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 15px 0; }
            .highlight { background: #fff3cd; padding: 15px; border-radius: 5px; margin: 15px 0; }
        </style>
    </head>
    <body>
        <div class='header'>
            <h1>🏰 Filá Mariscales de Caballeros Templarios</h1>
            <p>Elche - Fiestas de Moros y Cristianos</p>
        </div>
        <div class='content'>
            <h2>¡Bienvenido a nuestro boletín!</h2>
            <p>Estimado/a amigo/a,</p>
            <p>Nos complace confirmar que te has suscrito exitosamente a nuestro boletín de noticias.</p>
            
            <div class='highlight'>
                <h3>📧 Tu suscripción ha sido confirmada</h3>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Fecha:</strong> " . date('d/m/Y H:i:s') . "</p>
            </div>
            
            <h3>A partir de ahora recibirás:</h3>
            <ul>
                <li>📰 Las últimas noticias y eventos de la filá</li>
                <li>📅 Información sobre próximas actividades</li>
                <li>⚔️ Noticias sobre la tradición templaria</li>
                <li>🎉 Actualizaciones de las Fiestas de Moros y Cristianos</li>
                <li>🏆 Resultados de competiciones y eventos</li>
            </ul>
            
            <p style='text-align: center;'>
                <a href='http://localhost/prueba-php/public/' class='button'>🏠 Visitar Nuestra Web</a>
            </p>
            
            <p><small>Si no deseas recibir más emails, puedes darte de baja respondiendo a este correo con la palabra "BAJA".</small></p>
        </div>
        <div class='footer'>
            <p><strong>Filá Mariscales de Caballeros Templarios de Elche</strong></p>
            <p>📧 Email: edu300572@gmail.com</p>
            <p>🌐 Web: http://localhost/prueba-php/public/</p>
        </div>
    </body>
    </html>
    ";
    
    return ['asunto' => $asunto, 'mensaje' => $mensaje];
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
    
    // Crear email
    $emailData = crearEmailNewsletter($email);
    
    // Enviar email
    $emailEnviado = enviarEmailGmail($email, $emailData['asunto'], $emailData['mensaje']);
    
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
