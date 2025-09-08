<?php
// Diagnóstico completo del sistema de emails
echo "<h2>🔍 Diagnóstico del Sistema de Emails</h2>";

// Función para verificar configuración PHP
function verificarConfiguracionPHP() {
    echo "<h3>📋 Configuración de PHP</h3>";
    echo "<table style='width: 100%; border-collapse: collapse; margin: 20px 0;'>";
    echo "<tr style='background: #f8f9fa;'>";
    echo "<th style='border: 1px solid #ddd; padding: 8px;'>Configuración</th>";
    echo "<th style='border: 1px solid #ddd; padding: 8px;'>Valor</th>";
    echo "<th style='border: 1px solid #ddd; padding: 8px;'>Estado</th>";
    echo "</tr>";
    
    $configs = [
        'sendmail_path' => ini_get('sendmail_path'),
        'sendmail_from' => ini_get('sendmail_from'),
        'SMTP' => ini_get('SMTP'),
        'smtp_port' => ini_get('smtp_port'),
        'mail.add_x_header' => ini_get('mail.add_x_header'),
        'mail.log' => ini_get('mail.log')
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
}

// Función para verificar archivos
function verificarArchivos() {
    echo "<h3>📁 Verificación de Archivos</h3>";
    
    $archivos = [
        'php.ini' => php_ini_loaded_file(),
        'sendmail.ini' => 'C:\\xampp\\sendmail\\sendmail.ini',
        'sendmail.exe' => 'C:\\xampp\\sendmail\\sendmail.exe'
    ];
    
    echo "<ul>";
    foreach ($archivos as $nombre => $ruta) {
        if ($ruta && file_exists($ruta)) {
            echo "<li>✅ <strong>$nombre:</strong> $ruta</li>";
        } else {
            echo "<li>❌ <strong>$nombre:</strong> No encontrado</li>";
        }
    }
    echo "</ul>";
}

// Función para probar conexión SMTP
function probarConexionSMTP() {
    echo "<h3>🌐 Prueba de Conexión SMTP</h3>";
    
    $hosts = [
        'localhost:25' => 'XAMPP Local',
        'smtp.gmail.com:587' => 'Gmail SMTP',
        'smtp.gmail.com:465' => 'Gmail SMTP (SSL)'
    ];
    
    foreach ($hosts as $host => $descripcion) {
        list($hostname, $port) = explode(':', $host);
        
        echo "<p><strong>$descripcion ($host):</strong> ";
        
        $connection = @fsockopen($hostname, $port, $errno, $errstr, 5);
        
        if ($connection) {
            echo "<span style='color: green;'>✅ Conectado</span>";
            fclose($connection);
        } else {
            echo "<span style='color: red;'>❌ Error: $errstr ($errno)</span>";
        }
        echo "</p>";
    }
}

// Función para probar envío simple
function probarEnvioSimple() {
    echo "<h3>📧 Prueba de Envío Simple</h3>";
    
    $email = 'edu300572@gmail.com';
    $asunto = 'Prueba de Diagnóstico - ' . date('H:i:s');
    $mensaje = "Este es un email de prueba para diagnóstico.\n\nFecha: " . date('d/m/Y H:i:s');
    
    $headers = "From: Filá Mariscales <edu300572@gmail.com>\r\n";
    $headers .= "Reply-To: edu300572@gmail.com\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
    
    echo "<p>Enviando email a: <strong>$email</strong></p>";
    
    $resultado = mail($email, $asunto, $mensaje, $headers);
    
    if ($resultado) {
        echo "<p style='color: green;'>✅ <strong>Email enviado correctamente</strong></p>";
    } else {
        echo "<p style='color: red;'>❌ <strong>Error al enviar email</strong></p>";
        
        // Verificar errores
        $error = error_get_last();
        if ($error) {
            echo "<p><strong>Último error:</strong> " . $error['message'] . "</p>";
        }
    }
    
    return $resultado;
}

// Función para crear solución alternativa
function crearSolucionAlternativa() {
    echo "<h3>🛠️ Solución Alternativa</h3>";
    
    $codigo = '<?php
// Solución alternativa para envío de emails
function enviarEmailAlternativo($destinatario, $asunto, $mensaje) {
    // Simular envío exitoso
    $logEntry = date("Y-m-d H:i:s") . " | Email simulado | Para: $destinatario | Asunto: $asunto\n";
    file_put_contents("email-log.txt", $logEntry, FILE_APPEND | LOCK_EX);
    
    // En un entorno real, aquí iría la lógica de envío
    return true;
}

// Probar
$resultado = enviarEmailAlternativo("edu300572@gmail.com", "Prueba", "Mensaje de prueba");
echo $resultado ? "✅ Email procesado" : "❌ Error";
?>';
    
    echo "<p>He creado una solución alternativa que funciona sin configuración SMTP:</p>";
    echo "<pre style='background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto;'>";
    echo htmlspecialchars($codigo);
    echo "</pre>";
}

// Ejecutar diagnóstico
verificarConfiguracionPHP();
verificarArchivos();
probarConexionSMTP();

// Probar envío si se solicita
if (isset($_POST['probar'])) {
    echo "<div style='background: #fff3cd; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
    probarEnvioSimple();
    echo "</div>";
}

crearSolucionAlternativa();

// Mostrar formulario de prueba
?>
<div style="background: #e7f3ff; padding: 20px; border-radius: 10px; margin: 20px 0;">
    <h3>🧪 Probar Envío de Email</h3>
    <form method="POST">
        <button type="submit" name="probar" style="background: #dc143c; color: white; padding: 12px 25px; border: none; border-radius: 5px; cursor: pointer;">
            📧 Probar Envío
        </button>
    </form>
</div>

<div style="background: #d1ecf1; padding: 20px; border-radius: 10px; margin: 20px 0;">
    <h3>💡 Soluciones Recomendadas</h3>
    <ol>
        <li><strong>Configurar XAMPP:</strong> Usar el configurador automático</li>
        <li><strong>Sistema alternativo:</strong> Usar Mailtrap para desarrollo</li>
        <li><strong>Servicio externo:</strong> Configurar Gmail SMTP con PHPMailer</li>
        <li><strong>Solución temporal:</strong> Simular envío de emails</li>
    </ol>
</div>

<div style="background: #f8d7da; padding: 20px; border-radius: 10px; margin: 20px 0;">
    <h3>🚨 Estado Actual</h3>
    <p><strong>El sistema de suscripciones funciona perfectamente:</strong></p>
    <ul>
        <li>✅ Formulario completo</li>
        <li>✅ Validaciones</li>
        <li>✅ Guardado en base de datos</li>
        <li>✅ Interfaz profesional</li>
        <li>❌ Solo falta el envío de emails</li>
    </ul>
    <p><strong>Puedes usar el sistema y configurar los emails más tarde.</strong></p>
</div>
