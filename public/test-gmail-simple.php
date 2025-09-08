<?php
// Prueba simple de envío de email con Gmail
echo "<h2>📧 Prueba de Email con Gmail</h2>";

// Función simple para enviar email
function enviarEmailSimple($destinatario, $asunto, $mensaje) {
    $headers = "From: Filá Mariscales <edu300572@gmail.com>\r\n";
    $headers .= "Reply-To: edu300572@gmail.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
    
    return mail($destinatario, $asunto, $mensaje, $headers);
}

// Procesar formulario
if ($_POST) {
    $email = $_POST['email'] ?? '';
    $asunto = $_POST['asunto'] ?? 'Prueba de Email';
    $mensaje = $_POST['mensaje'] ?? 'Este es un mensaje de prueba.';
    
    if ($email) {
        echo "<div style='background: #f8f9fa; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
        echo "<h3>📤 Enviando email...</h3>";
        echo "<p><strong>Para:</strong> $email</p>";
        echo "<p><strong>Asunto:</strong> $asunto</p>";
        
        $resultado = enviarEmailSimple($email, $asunto, $mensaje);
        
        if ($resultado) {
            echo "<p style='color: green; font-weight: bold;'>✅ ¡Email enviado correctamente!</p>";
            echo "<p>Revisa tu bandeja de entrada.</p>";
        } else {
            echo "<p style='color: red; font-weight: bold;'>❌ Error al enviar email.</p>";
            echo "<p>Esto puede deberse a la configuración de XAMPP.</p>";
        }
        echo "</div>";
    }
}

// Mostrar formulario
?>
<div style="background: #e7f3ff; padding: 20px; border-radius: 10px; margin: 20px 0;">
    <h3>🧪 Probar Envío de Email</h3>
    <form method="POST">
        <div style="margin: 10px 0;">
            <label><strong>Email de destino:</strong></label><br>
            <input type="email" name="email" value="edu300572@gmail.com" required style="width: 300px; padding: 8px;">
        </div>
        
        <div style="margin: 10px 0;">
            <label><strong>Asunto:</strong></label><br>
            <input type="text" name="asunto" value="Prueba de Email - Filá Mariscales" style="width: 300px; padding: 8px;">
        </div>
        
        <div style="margin: 10px 0;">
            <label><strong>Mensaje:</strong></label><br>
            <textarea name="mensaje" rows="5" style="width: 100%; padding: 8px;">Este es un mensaje de prueba desde la web de la Filá Mariscales de Caballeros Templarios.

Fecha: <?php echo date('d/m/Y H:i:s'); ?>

Si recibes este email, significa que la configuración funciona correctamente.

¡Saludos desde Elche!</textarea>
        </div>
        
        <button type="submit" style="background: #dc143c; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
            📧 Enviar Email de Prueba
        </button>
    </form>
</div>

<div style="background: #fff3cd; padding: 20px; border-radius: 10px; margin: 20px 0;">
    <h3>⚠️ Notas Importantes</h3>
    <ul>
        <li><strong>Gmail puede rechazar emails</strong> si no están configurados correctamente</li>
        <li><strong>Revisa la carpeta de spam</strong> si no recibes el email</li>
        <li><strong>XAMPP necesita configuración</strong> para envío de emails</li>
        <li><strong>Alternativa:</strong> Usar un servicio externo como Mailtrap</li>
    </ul>
</div>

<div style="background: #d1ecf1; padding: 20px; border-radius: 10px; margin: 20px 0;">
    <h3>🔧 Configuración Actual de PHP</h3>
    <table style="width: 100%; border-collapse: collapse;">
        <tr style="background: #f8f9fa;">
            <th style="border: 1px solid #ddd; padding: 8px;">Configuración</th>
            <th style="border: 1px solid #ddd; padding: 8px;">Valor</th>
            <th style="border: 1px solid #ddd; padding: 8px;">Estado</th>
        </tr>
        <tr>
            <td style="border: 1px solid #ddd; padding: 8px;">sendmail_path</td>
            <td style="border: 1px solid #ddd; padding: 8px;"><?php echo ini_get('sendmail_path') ?: 'No establecido'; ?></td>
            <td style="border: 1px solid #ddd; padding: 8px;"><?php echo ini_get('sendmail_path') ? '✅ Configurado' : '❌ No configurado'; ?></td>
        </tr>
        <tr>
            <td style="border: 1px solid #ddd; padding: 8px;">SMTP</td>
            <td style="border: 1px solid #ddd; padding: 8px;"><?php echo ini_get('SMTP') ?: 'No establecido'; ?></td>
            <td style="border: 1px solid #ddd; padding: 8px;"><?php echo ini_get('SMTP') ? '✅ Configurado' : '❌ No configurado'; ?></td>
        </tr>
        <tr>
            <td style="border: 1px solid #ddd; padding: 8px;">smtp_port</td>
            <td style="border: 1px solid #ddd; padding: 8px;"><?php echo ini_get('smtp_port') ?: 'No establecido'; ?></td>
            <td style="border: 1px solid #ddd; padding: 8px;"><?php echo ini_get('smtp_port') ? '✅ Configurado' : '❌ No configurado'; ?></td>
        </tr>
        <tr>
            <td style="border: 1px solid #ddd; padding: 8px;">sendmail_from</td>
            <td style="border: 1px solid #ddd; padding: 8px;"><?php echo ini_get('sendmail_from') ?: 'No establecido'; ?></td>
            <td style="border: 1px solid #ddd; padding: 8px;"><?php echo ini_get('sendmail_from') ? '✅ Configurado' : '❌ No configurado'; ?></td>
        </tr>
    </table>
</div>
