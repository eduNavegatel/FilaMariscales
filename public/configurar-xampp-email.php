<?php
// Configurador de email para XAMPP
echo "<h2>🔧 Configuración de Email para XAMPP</h2>";

// Verificar configuración actual de PHP
echo "<h3>📋 Configuración Actual de PHP</h3>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Configuración</th><th>Valor</th><th>Estado</th></tr>";

$configs = [
    'sendmail_path' => ini_get('sendmail_path'),
    'SMTP' => ini_get('SMTP'),
    'smtp_port' => ini_get('smtp_port'),
    'sendmail_from' => ini_get('sendmail_from'),
    'mail.add_x_header' => ini_get('mail.add_x_header'),
    'mail.log' => ini_get('mail.log')
];

foreach ($configs as $config => $value) {
    $status = $value ? "✅ Configurado" : "❌ No configurado";
    echo "<tr><td>$config</td><td>" . ($value ?: 'No establecido') . "</td><td>$status</td></tr>";
}

echo "</table>";

// Función para probar envío simple
function probarEnvioSimple($email) {
    $asunto = "Prueba Simple - Filá Mariscales";
    $mensaje = "Este es un email de prueba simple desde XAMPP.\n\n";
    $mensaje .= "Si recibes este email, el sistema básico funciona.\n";
    $mensaje .= "Fecha: " . date('d/m/Y H:i:s') . "\n";
    $mensaje .= "Desde: Filá Mariscales de Caballeros Templarios";
    
    $headers = "From: edu300572@gmail.com\r\n";
    $headers .= "Reply-To: edu300572@gmail.com\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
    
    return mail($email, $asunto, $mensaje, $headers);
}

// Función para probar envío HTML
function probarEnvioHTML($email) {
    $asunto = "Prueba HTML - Filá Mariscales";
    
    $mensaje = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>Prueba HTML</title>
        <style>
            body { font-family: Arial, sans-serif; }
            .header { background: #dc143c; color: white; padding: 20px; text-align: center; }
            .content { padding: 20px; background: #f8f9fa; }
        </style>
    </head>
    <body>
        <div class='header'>
            <h1>🛡️ Filá Mariscales</h1>
            <p>Prueba de Email HTML</p>
        </div>
        <div class='content'>
            <h2>¡Email HTML funcionando!</h2>
            <p>Este es un email de prueba con formato HTML.</p>
            <p><strong>Fecha:</strong> " . date('d/m/Y H:i:s') . "</p>
            <p><strong>Destinatario:</strong> $email</p>
        </div>
    </body>
    </html>
    ";
    
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: Filá Mariscales <edu300572@gmail.com>\r\n";
    $headers .= "Reply-To: edu300572@gmail.com\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
    
    return mail($email, $asunto, $mensaje, $headers);
}

// Procesar formulario
if ($_POST) {
    $email = $_POST['email'] ?? '';
    $tipo = $_POST['tipo'] ?? 'simple';
    
    if (empty($email)) {
        echo "<p style='color: red;'>❌ Por favor, introduce un email de prueba.</p>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p style='color: red;'>❌ Por favor, introduce un email válido.</p>";
    } else {
        echo "<div style='background: #e7f3ff; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "<h3>📧 Enviando email de prueba...</h3>";
        echo "<p><strong>Destinatario:</strong> $email</p>";
        echo "<p><strong>Tipo:</strong> " . ($tipo === 'html' ? 'HTML' : 'Texto simple') . "</p>";
        
        if ($tipo === 'html') {
            $resultado = probarEnvioHTML($email);
        } else {
            $resultado = probarEnvioSimple($email);
        }
        
        if ($resultado) {
            echo "<p style='color: green;'>✅ <strong>Email enviado correctamente!</strong></p>";
            echo "<p>Revisa tu bandeja de entrada (y la carpeta de spam si no aparece).</p>";
        } else {
            echo "<p style='color: red;'>❌ <strong>Error al enviar el email.</strong></p>";
            echo "<p>Esto puede deberse a:</p>";
            echo "<ul>";
            echo "<li>XAMPP no tiene configurado un servidor SMTP</li>";
            echo "<li>El servidor de correo local no está funcionando</li>";
            echo "<li>Configuración de PHP incorrecta</li>";
            echo "</ul>";
        }
        echo "</div>";
    }
}

// Mostrar formulario de prueba
?>
<div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin: 20px 0;">
    <h3>🧪 Probar Envío de Email</h3>
    <form method="POST">
        <div style="margin: 10px 0;">
            <label for="email"><strong>Email de prueba:</strong></label><br>
            <input type="email" id="email" name="email" required 
                   style="padding: 10px; width: 300px; border: 1px solid #ccc; border-radius: 5px;"
                   placeholder="tu-email@ejemplo.com" value="edu300572@gmail.com">
        </div>
        
        <div style="margin: 10px 0;">
            <label><strong>Tipo de email:</strong></label><br>
            <input type="radio" id="simple" name="tipo" value="simple" checked>
            <label for="simple">Texto simple</label><br>
            <input type="radio" id="html" name="tipo" value="html">
            <label for="html">HTML</label>
        </div>
        
        <button type="submit" style="background: #dc143c; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
            📧 Enviar Email de Prueba
        </button>
    </form>
</div>

<div style="background: #fff3cd; padding: 20px; border-radius: 10px; margin: 20px 0;">
    <h3>⚠️ Notas Importantes para XAMPP</h3>
    <p><strong>XAMPP por defecto NO tiene configurado un servidor SMTP.</strong></p>
    <p>Para que los emails funcionen completamente, necesitas:</p>
    <ol>
        <li><strong>Configurar un servidor SMTP local</strong> (como Mercury Mail)</li>
        <li><strong>O usar un servicio externo</strong> (Gmail, Outlook, etc.)</li>
        <li><strong>O configurar XAMPP con un servidor de correo</strong></li>
    </ol>
    
    <h4>🔧 Solución Rápida:</h4>
    <p>Puedes usar <strong>MailHog</strong> o <strong>Mailtrap</strong> para desarrollo:</p>
    <ul>
        <li>MailHog: Captura emails en desarrollo</li>
        <li>Mailtrap: Servicio online para testing</li>
        <li>Gmail SMTP: Para emails reales</li>
    </ul>
</div>

<div style="background: #d1ecf1; padding: 20px; border-radius: 10px; margin: 20px 0;">
    <h3>💡 Alternativa: Usar Gmail SMTP</h3>
    <p>Si quieres enviar emails reales, puedes configurar Gmail SMTP:</p>
    <ol>
        <li>Activar verificación en 2 pasos en Gmail</li>
        <li>Crear contraseña de aplicación</li>
        <li>Configurar PHPMailer o similar</li>
    </ol>
    <p><strong>Nota:</strong> La función mail() nativa de PHP en XAMPP tiene limitaciones.</p>
</div>
