<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Cargar configuración
require_once '../src/config/config.php';

// Configuración de Mailtrap (servicio externo para testing)
// Mailtrap es un servicio que captura emails en desarrollo
// No envía emails reales, pero simula el proceso completo

// Función para simular envío de email (Mailtrap style)
function enviarEmailMailtrap($destinatario, $asunto, $mensaje) {
    // Simular envío exitoso
    $logEntry = date('Y-m-d H:i:s') . " | MAILTRAP | Para: $destinatario | Asunto: $asunto\n";
    $logEntry .= "Mensaje: " . substr(strip_tags($mensaje), 0, 100) . "...\n";
    $logEntry .= "---\n";
    
    // Guardar en log
    file_put_contents('../uploads/mailtrap-log.txt', $logEntry, FILE_APPEND | LOCK_EX);
    
    // Simular éxito
    return true;
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
            .success { background: #d4edda; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #28a745; }
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
            
            <div class='success'>
                <h3>✅ Suscripción Confirmada</h3>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Fecha:</strong> " . date('d/m/Y H:i:s') . "</p>
                <p><strong>Estado:</strong> Activa</p>
            </div>
            
            <h3>📧 A partir de ahora recibirás:</h3>
            <ul>
                <li>📰 Las últimas noticias y eventos de la filá</li>
                <li>📅 Información sobre próximas actividades</li>
                <li>⚔️ Noticias sobre la tradición templaria</li>
                <li>🎉 Actualizaciones de las Fiestas de Moros y Cristianos</li>
                <li>🏆 Resultados de competiciones y eventos</li>
                <li>📸 Galerías de fotos de eventos</li>
            </ul>
            
            <div class='highlight'>
                <h4>🎯 Próximos Eventos</h4>
                <p>Mantente atento a nuestras comunicaciones para no perderte ningún evento importante de la filá.</p>
            </div>
            
            <p style='text-align: center;'>
                <a href='http://localhost/prueba-php/public/' class='button'>🏠 Visitar Nuestra Web</a>
            </p>
            
            <p><small>Si no deseas recibir más emails, puedes darte de baja respondiendo a este correo con la palabra "BAJA".</small></p>
        </div>
        <div class='footer'>
            <p><strong>Filá Mariscales de Caballeros Templarios de Elche</strong></p>
            <p>📧 Email: edu300572@gmail.com</p>
            <p>🌐 Web: http://localhost/prueba-php/public/</p>
            <p><small>Este email fue enviado usando Mailtrap (modo desarrollo)</small></p>
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
                user_agent TEXT,
                metodo_envio VARCHAR(50) DEFAULT 'mailtrap'
            )
        ";
        $pdo->exec($createTable);
        
        // Insertar suscripción
        $stmt = $pdo->prepare("
            INSERT INTO newsletter_subscriptions (email, ip_address, user_agent, metodo_envio) 
            VALUES (?, ?, ?, 'mailtrap')
            ON DUPLICATE KEY UPDATE 
            fecha_suscripcion = CURRENT_TIMESTAMP,
            activo = TRUE,
            metodo_envio = 'mailtrap'
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
    
    // Enviar email (simulado con Mailtrap)
    $emailEnviado = enviarEmailMailtrap($email, $emailData['asunto'], $emailData['mensaje']);
    
    if ($emailEnviado) {
        echo json_encode([
            'success' => true,
            'message' => '¡Suscripción exitosa! El email se ha procesado correctamente (modo desarrollo).'
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
