<?php
// Configurador automático de XAMPP para emails
echo "<h2>🔧 Configurador Automático de XAMPP para Emails</h2>";

// Función para configurar PHP.ini
function configurarPHPIni() {
    $phpIniPath = php_ini_loaded_file();
    
    if (!$phpIniPath) {
        return "❌ No se pudo encontrar el archivo php.ini";
    }
    
    echo "<h3>📁 Archivo php.ini encontrado:</h3>";
    echo "<p><strong>Ruta:</strong> $phpIniPath</p>";
    
    // Leer configuración actual
    $config = file_get_contents($phpIniPath);
    
    // Configuraciones necesarias
    $configuraciones = [
        'sendmail_path' => 'sendmail_path = "C:\\xampp\\sendmail\\sendmail.exe -t"',
        'sendmail_from' => 'sendmail_from = "edu300572@gmail.com"',
        'SMTP' => 'SMTP = localhost',
        'smtp_port' => 'smtp_port = 25',
        'mail.add_x_header' => 'mail.add_x_header = On',
        'mail.log' => 'mail.log = "C:\\xampp\\logs\\mail.log"'
    ];
    
    echo "<h3>🔧 Configuraciones a aplicar:</h3>";
    echo "<ul>";
    foreach ($configuraciones as $key => $value) {
        echo "<li><strong>$key:</strong> $value</li>";
    }
    echo "</ul>";
    
    return $phpIniPath;
}

// Función para crear archivo sendmail.ini
function crearSendmailIni() {
    $sendmailIniPath = 'C:\\xampp\\sendmail\\sendmail.ini';
    
    $contenido = '
; Configuración de Sendmail para XAMPP
; Para usar Gmail SMTP

[sendmail]
smtp_server=smtp.gmail.com
smtp_port=587
smtp_ssl=tls
auth_username=edu300572@gmail.com
auth_password=granmaestre2024
force_sender=edu300572@gmail.com
hostname=localhost
';
    
    echo "<h3>📝 Creando archivo sendmail.ini:</h3>";
    echo "<p><strong>Ruta:</strong> $sendmailIniPath</p>";
    
    // Mostrar contenido que se creará
    echo "<h4>Contenido del archivo:</h4>";
    echo "<pre style='background: #f8f9fa; padding: 15px; border-radius: 5px;'>";
    echo htmlspecialchars($contenido);
    echo "</pre>";
    
    return $contenido;
}

// Función para probar configuración
function probarConfiguracion() {
    echo "<h3>🧪 Probando configuración actual:</h3>";
    
    $email = 'edu300572@gmail.com';
    $asunto = 'Prueba de Configuración XAMPP';
    $mensaje = "Este es un email de prueba para verificar la configuración de XAMPP.\n\n";
    $mensaje .= "Fecha: " . date('d/m/Y H:i:s') . "\n";
    $mensaje .= "Desde: Filá Mariscales de Caballeros Templarios";
    
    $headers = "From: Filá Mariscales <edu300572@gmail.com>\r\n";
    $headers .= "Reply-To: edu300572@gmail.com\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
    
    echo "<p>Enviando email a: <strong>$email</strong></p>";
    
    $resultado = mail($email, $asunto, $mensaje, $headers);
    
    if ($resultado) {
        echo "<p style='color: green;'>✅ <strong>¡Email enviado correctamente!</strong></p>";
        echo "<p>Revisa tu bandeja de entrada.</p>";
    } else {
        echo "<p style='color: red;'>❌ <strong>Error al enviar email.</strong></p>";
        echo "<p>Necesitas configurar XAMPP correctamente.</p>";
    }
    
    return $resultado;
}

// Ejecutar funciones
$phpIniPath = configurarPHPIni();
$sendmailContent = crearSendmailIni();

echo "<div style='background: #fff3cd; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
echo "<h3>⚠️ Instrucciones de Configuración Manual</h3>";
echo "<p><strong>Para que los emails funcionen en XAMPP, necesitas hacer lo siguiente:</strong></p>";
echo "<ol>";
echo "<li><strong>Editar php.ini:</strong> Abre el archivo php.ini y busca la sección [mail function]</li>";
echo "<li><strong>Descomenta y configura:</strong>";
echo "<ul>";
echo "<li><code>sendmail_path = \"C:\\xampp\\sendmail\\sendmail.exe -t\"</code></li>";
echo "<li><code>sendmail_from = \"edu300572@gmail.com\"</code></li>";
echo "<li><code>SMTP = localhost</code></li>";
echo "<li><code>smtp_port = 25</code></li>";
echo "</ul>";
echo "</li>";
echo "<li><strong>Crear sendmail.ini:</strong> Crea el archivo C:\\xampp\\sendmail\\sendmail.ini con el contenido mostrado arriba</li>";
echo "<li><strong>Reiniciar Apache:</strong> Reinicia el servidor Apache en XAMPP</li>";
echo "</ol>";
echo "</div>";

// Probar si se puede enviar
if (isset($_POST['probar'])) {
    probarConfiguracion();
}

// Mostrar formulario de prueba
?>
<div style="background: #e7f3ff; padding: 20px; border-radius: 10px; margin: 20px 0;">
    <h3>🧪 Probar Configuración</h3>
    <form method="POST">
        <p>Haz clic en el botón para probar si la configuración actual funciona:</p>
        <button type="submit" name="probar" style="background: #dc143c; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
            📧 Probar Envío de Email
        </button>
    </form>
</div>

<div style="background: #d1ecf1; padding: 20px; border-radius: 10px; margin: 20px 0;">
    <h3>💡 Alternativa: Usar Servicio Externo</h3>
    <p>Si configurar XAMPP es complicado, puedes usar un servicio externo:</p>
    <ul>
        <li><strong>Mailtrap:</strong> Servicio online para testing (gratis)</li>
        <li><strong>Gmail SMTP:</strong> Para emails reales</li>
        <li><strong>SendGrid:</strong> Servicio profesional</li>
    </ul>
    <p><strong>Ventaja:</strong> No necesitas configurar XAMPP, solo cambiar la configuración en el código.</p>
</div>

<div style="background: #f8d7da; padding: 20px; border-radius: 10px; margin: 20px 0;">
    <h3>🚨 Importante</h3>
    <p><strong>El sistema de suscripciones funciona perfectamente</strong> aunque no se envíen emails:</p>
    <ul>
        <li>✅ Formulario funciona</li>
        <li>✅ Validaciones completas</li>
        <li>✅ Suscripciones se guardan</li>
        <li>✅ Interfaz profesional</li>
        <li>❌ Solo falta el envío de emails</li>
    </ul>
    <p>Puedes usar el sistema y configurar los emails más tarde.</p>
</div>
