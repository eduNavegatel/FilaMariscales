<?php
// Configurador automático de XAMPP para emails
echo "<h2>🔧 Configurador Automático de XAMPP</h2>";

// Función para configurar php.ini automáticamente
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
        'smtp_port' => 'smtp_port = 25'
    ];
    
    echo "<h3>🔧 Configuraciones a aplicar:</h3>";
    echo "<ul>";
    foreach ($configuraciones as $key => $value) {
        echo "<li><strong>$key:</strong> $value</li>";
    }
    echo "</ul>";
    
    // Crear backup
    $backupPath = $phpIniPath . '.backup.' . date('Y-m-d-H-i-s');
    copy($phpIniPath, $backupPath);
    echo "<p>✅ <strong>Backup creado:</strong> $backupPath</p>";
    
    // Aplicar configuraciones
    $nuevoConfig = $config;
    
    foreach ($configuraciones as $key => $value) {
        // Buscar línea existente y descomentarla o agregarla
        $pattern = '/^;?\s*' . preg_quote($key, '/') . '\s*=.*$/m';
        if (preg_match($pattern, $nuevoConfig)) {
            // Reemplazar línea existente
            $nuevoConfig = preg_replace($pattern, $value, $nuevoConfig);
        } else {
            // Agregar nueva línea en la sección [mail function]
            $nuevoConfig = preg_replace(
                '/(\[mail function\][^\n]*\n)/',
                "$1$value\n",
                $nuevoConfig
            );
        }
    }
    
    // Guardar configuración
    if (file_put_contents($phpIniPath, $nuevoConfig)) {
        echo "<p>✅ <strong>Configuración aplicada correctamente</strong></p>";
        return true;
    } else {
        echo "<p>❌ <strong>Error al aplicar configuración</strong></p>";
        return false;
    }
}

// Función para crear sendmail.ini
function crearSendmailIni() {
    $sendmailIniPath = 'C:\\xampp\\sendmail\\sendmail.ini';
    $sendmailDir = dirname($sendmailIniPath);
    
    // Crear directorio si no existe
    if (!is_dir($sendmailDir)) {
        mkdir($sendmailDir, 0755, true);
        echo "<p>✅ <strong>Directorio creado:</strong> $sendmailDir</p>";
    }
    
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
    
    if (file_put_contents($sendmailIniPath, $contenido)) {
        echo "<p>✅ <strong>Archivo sendmail.ini creado:</strong> $sendmailIniPath</p>";
        return true;
    } else {
        echo "<p>❌ <strong>Error al crear sendmail.ini</strong></p>";
        return false;
    }
}

// Función para probar configuración
function probarConfiguracion() {
    echo "<h3>🧪 Probando configuración actual:</h3>";
    
    $email = 'edu300572@gmail.com';
    $asunto = 'Prueba de Configuración XAMPP - ' . date('H:i:s');
    $mensaje = "Este es un email de prueba para verificar la configuración de XAMPP.\n\n";
    $mensaje .= "Fecha: " . date('d/m/Y H:i:s') . "\n";
    $mensaje .= "Desde: Filá Mariscales de Caballeros Templarios\n";
    $mensaje .= "Configuración: XAMPP con Gmail SMTP";
    
    $headers = "From: Filá Mariscales <edu300572@gmail.com>\r\n";
    $headers .= "Reply-To: edu300572@gmail.com\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    echo "<p>Enviando email a: <strong>$email</strong></p>";
    
    $resultado = mail($email, $asunto, $mensaje, $headers);
    
    if ($resultado) {
        echo "<p style='color: green;'>✅ <strong>¡Email enviado correctamente!</strong></p>";
        echo "<p>Revisa tu bandeja de entrada.</p>";
    } else {
        echo "<p style='color: red;'>❌ <strong>Error al enviar email.</strong></p>";
        echo "<p>Necesitas reiniciar Apache después de la configuración.</p>";
    }
    
    return $resultado;
}

// Procesar acciones
if (isset($_POST['configurar'])) {
    echo "<div style='background: #e7f3ff; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
    echo "<h3>🔧 Configurando XAMPP...</h3>";
    
    $phpConfigurado = configurarPHPIni();
    $sendmailCreado = crearSendmailIni();
    
    if ($phpConfigurado && $sendmailCreado) {
        echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<h4>✅ Configuración Completada</h4>";
        echo "<p><strong>Pasos siguientes:</strong></p>";
        echo "<ol>";
        echo "<li>Reinicia Apache en XAMPP Control Panel</li>";
        echo "<li>Prueba el envío de emails</li>";
        echo "<li>Verifica tu bandeja de entrada</li>";
        echo "</ol>";
        echo "</div>";
    }
    echo "</div>";
}

if (isset($_POST['probar'])) {
    echo "<div style='background: #fff3cd; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
    probarConfiguracion();
    echo "</div>";
}

// Mostrar estado actual
echo "<div style='background: #f8f9fa; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
echo "<h3>📋 Estado Actual de la Configuración</h3>";
echo "<table style='width: 100%; border-collapse: collapse;'>";
echo "<tr style='background: #e9ecef;'>";
echo "<th style='border: 1px solid #ddd; padding: 8px;'>Configuración</th>";
echo "<th style='border: 1px solid #ddd; padding: 8px;'>Valor</th>";
echo "<th style='border: 1px solid #ddd; padding: 8px;'>Estado</th>";
echo "</tr>";

$configs = [
    'sendmail_path' => ini_get('sendmail_path'),
    'sendmail_from' => ini_get('sendmail_from'),
    'SMTP' => ini_get('SMTP'),
    'smtp_port' => ini_get('smtp_port')
];

foreach ($configs as $key => $value) {
    $estado = $value ? '✅ Configurado' : '❌ No configurado';
    $valor = $value ?: 'No establecido';
    echo "<tr>";
    echo "<td style='border: 1px solid #ddd; padding: 8px;'>$key</td>";
    echo "<td style='border: 1px solid #ddd; padding: 8px;'>$valor</td>";
    echo "<td style='border: 1px solid #ddd; padding: 8px;'>$estado</td>";
    echo "</tr>";
}
echo "</table>";
echo "</div>";

// Mostrar formularios
?>
<div style="background: #e7f3ff; padding: 20px; border-radius: 10px; margin: 20px 0;">
    <h3>🔧 Configurar XAMPP Automáticamente</h3>
    <p>Este proceso configurará XAMPP para enviar emails correctamente:</p>
    <ul>
        <li>✅ Configurará php.ini</li>
        <li>✅ Creará sendmail.ini</li>
        <li>✅ Creará backup de seguridad</li>
    </ul>
    <form method="POST">
        <button type="submit" name="configurar" style="background: #dc143c; color: white; padding: 12px 25px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
            🔧 Configurar XAMPP
        </button>
    </form>
</div>

<div style="background: #fff3cd; padding: 20px; border-radius: 10px; margin: 20px 0;">
    <h3>🧪 Probar Configuración</h3>
    <p>Después de configurar, haz clic aquí para probar:</p>
    <form method="POST">
        <button type="submit" name="probar" style="background: #28a745; color: white; padding: 12px 25px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
            📧 Probar Envío de Email
        </button>
    </form>
</div>

<div style="background: #d1ecf1; padding: 20px; border-radius: 10px; margin: 20px 0;">
    <h3>⚠️ Instrucciones Importantes</h3>
    <ol>
        <li><strong>Haz clic en "Configurar XAMPP"</strong> para aplicar la configuración</li>
        <li><strong>Reinicia Apache</strong> en XAMPP Control Panel</li>
        <li><strong>Haz clic en "Probar"</strong> para verificar que funciona</li>
        <li><strong>Revisa tu bandeja de entrada</strong> (y spam)</li>
    </ol>
    <p><strong>Nota:</strong> Si no funciona, puede ser que Gmail requiera configuración adicional.</p>
</div>
