<?php
// Archivo de prueba para verificar el envío de emails
require_once '../src/config/config.php';
require_once '../src/config/email_config.php';

echo "<h2>Prueba del Sistema de Emails</h2>";

// Función de prueba
function probarEnvioEmail($email) {
    $asunto = "Prueba de Email - Filá Mariscales";
    
    $mensaje = "
    <!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <title>Prueba de Email</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #dc143c; color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
            .content { background: #f8f9fa; padding: 20px; border-radius: 0 0 10px 10px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>🛡️ Prueba de Email</h1>
                <p>Filá Mariscales de Caballeros Templarios</p>
            </div>
            <div class='content'>
                <h2>¡Email de prueba enviado correctamente!</h2>
                <p>Este es un email de prueba para verificar que el sistema de envío de correos funciona correctamente.</p>
                <p><strong>Destinatario:</strong> $email</p>
                <p><strong>Fecha:</strong> " . date('d/m/Y H:i:s') . "</p>
                <p>Si recibes este email, significa que el sistema está funcionando perfectamente.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    return enviarEmail($email, $asunto, $mensaje);
}

// Procesar formulario de prueba
if ($_POST) {
    $email = $_POST['email'] ?? '';
    
    if (empty($email)) {
        echo "<p style='color: red;'>Por favor, introduce un email de prueba.</p>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p style='color: red;'>Por favor, introduce un email válido.</p>";
    } else {
        echo "<p>Enviando email de prueba a: <strong>$email</strong></p>";
        
        if (probarEnvioEmail($email)) {
            echo "<p style='color: green;'>✅ Email enviado correctamente!</p>";
            echo "<p>Revisa tu bandeja de entrada (y la carpeta de spam).</p>";
        } else {
            echo "<p style='color: red;'>❌ Error al enviar el email.</p>";
            echo "<p>Verifica la configuración del servidor de correo.</p>";
        }
    }
}

// Mostrar formulario de prueba
?>
<form method="POST" style="margin: 20px 0; padding: 20px; border: 1px solid #ddd; border-radius: 10px;">
    <h3>Probar Envío de Email</h3>
    <p>Introduce tu email para recibir un mensaje de prueba:</p>
    
    <div style="margin: 10px 0;">
        <label for="email">Email de prueba:</label><br>
        <input type="email" id="email" name="email" required 
               style="padding: 10px; width: 300px; border: 1px solid #ccc; border-radius: 5px;"
               placeholder="tu-email@ejemplo.com">
    </div>
    
    <button type="submit" style="background: #dc143c; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
        Enviar Email de Prueba
    </button>
</form>

<div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin: 20px 0;">
    <h3>Información del Sistema</h3>
    <p><strong>Modo de desarrollo:</strong> <?php echo DEVELOPMENT_MODE ? 'Activado' : 'Desactivado'; ?></p>
    <p><strong>Servidor SMTP:</strong> <?php echo SMTP_HOST; ?></p>
    <p><strong>Puerto:</strong> <?php echo SMTP_PORT; ?></p>
    <p><strong>Email de la filá:</strong> <?php echo FILA_EMAIL; ?></p>
    
    <?php if (DEVELOPMENT_MODE): ?>
    <div style="background: #fff3cd; padding: 15px; border-radius: 5px; margin: 10px 0;">
        <strong>⚠️ Modo de desarrollo activado:</strong><br>
        Los emails se simulan y se registran en los logs del servidor, pero no se envían realmente.
        Para enviar emails reales, cambia DEVELOPMENT_MODE a false en src/config/email_config.php
    </div>
    <?php endif; ?>
</div>

<div style="background: #d1ecf1; padding: 20px; border-radius: 10px; margin: 20px 0;">
    <h3>Configuración para Producción</h3>
    <p>Para que los emails se envíen realmente, necesitas:</p>
    <ol>
        <li>Configurar un servidor SMTP (Gmail, Outlook, etc.)</li>
        <li>Actualizar las credenciales en <code>src/config/email_config.php</code></li>
        <li>Cambiar <code>DEVELOPMENT_MODE</code> a <code>false</code></li>
        <li>Opcionalmente, instalar PHPMailer para mejor funcionalidad</li>
    </ol>
</div>
