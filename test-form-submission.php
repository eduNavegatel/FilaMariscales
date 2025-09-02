<?php
// Script para probar el envío del formulario web de edición de usuarios
echo "<h1>📝 Prueba de Envío del Formulario de Edición</h1>";

try {
    // Simular una petición HTTP real
    $url = 'http://localhost/prueba-php/public/admin/editarUsuario/31';
    
    // Datos del formulario que se enviarían
    $postData = [
        'csrf_token' => 'test-token-123',
        'user_id' => '31',
        'nombre' => 'Fran',
        'apellidos' => '',
        'email' => 'fran@fran.es',
        'rol' => 'admin', // Cambiando de 'socio' a 'admin'
        'activo' => '1'
    ];
    
    echo "<h2>📋 Datos del Formulario:</h2>";
    echo "<pre>" . print_r($postData, true) . "</pre>";
    
    echo "<h2>🔗 URL de Destino:</h2>";
    echo "<p><code>$url</code></p>";
    
    // Simular el envío usando cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded',
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
        'Accept-Language: es-ES,es;q=0.8,en-US;q=0.5,en;q=0.3',
        'Accept-Encoding: gzip, deflate',
        'Connection: keep-alive',
        'Upgrade-Insecure-Requests: 1'
    ]);
    
    echo "<h2>📤 Enviando Formulario...</h2>";
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    $error = curl_error($ch);
    
    curl_close($ch);
    
    echo "<h3>📊 Resultado de la Petición:</h3>";
    echo "<ul>";
    echo "<li><strong>Código HTTP:</strong> $httpCode</li>";
    echo "<li><strong>URL Final:</strong> $finalUrl</li>";
    echo "<li><strong>Error cURL:</strong> " . ($error ?: 'Ninguno') . "</li>";
    echo "<li><strong>Tamaño de respuesta:</strong> " . strlen($response) . " bytes</li>";
    echo "</ul>";
    
    // Analizar la respuesta
    if ($httpCode == 200) {
        echo "<h3>✅ Respuesta Exitosa (200)</h3>";
        
        // Verificar si se redirigió a la lista de usuarios
        if (strpos($finalUrl, '/admin/usuarios') !== false) {
            echo "<p style='color: green;'>✅ Redirección exitosa a la lista de usuarios</p>";
        } else {
            echo "<p style='color: orange;'>⚠️ No se redirigió a la lista de usuarios</p>";
        }
        
        // Verificar si hay mensaje de éxito
        if (strpos($response, 'Usuario actualizado correctamente') !== false) {
            echo "<p style='color: green;'>✅ Mensaje de éxito encontrado</p>";
        } else {
            echo "<p style='color: orange;'>⚠️ Mensaje de éxito NO encontrado</p>";
        }
        
    } elseif ($httpCode == 302) {
        echo "<h3>🔄 Redirección (302)</h3>";
        echo "<p>La petición fue redirigida. Esto puede indicar:</p>";
        echo "<ul>";
        echo "<li>Redirección después de actualización exitosa</li>";
        echo "<li>Redirección por falta de autenticación</li>";
        echo "<li>Redirección por error de validación</li>";
        echo "</ul>";
        
    } elseif ($httpCode == 401 || $httpCode == 403) {
        echo "<h3>🚫 Error de Autenticación ($httpCode)</h3>";
        echo "<p>El usuario no está autenticado o no tiene permisos.</p>";
        
    } else {
        echo "<h3>❌ Error HTTP ($httpCode)</h3>";
        echo "<p>La petición falló con código de error.</p>";
    }
    
    // Mostrar parte de la respuesta para análisis
    echo "<h3>📄 Contenido de la Respuesta:</h3>";
    echo "<div style='border: 1px solid #ccc; padding: 1rem; max-height: 400px; overflow-y: auto; background-color: #f8f9fa;'>";
    echo htmlspecialchars(substr($response, 0, 2000));
    if (strlen($response) > 2000) {
        echo "<br><br><em>... (respuesta truncada, total: " . strlen($response) . " bytes)</em>";
    }
    echo "</div>";
    
    // Verificar si el rol se cambió realmente en la base de datos
    echo "<h2>🔍 Verificación en Base de Datos:</h2>";
    
    require_once 'src/config/config.php';
    require_once 'src/models/Database.php';
    
    $db = new Database();
    
    // Verificar el rol actual del usuario
    $db->query("SELECT rol FROM users WHERE id = :id");
    $db->bind(':id', 31);
    $result = $db->single();
    
    if ($result) {
        echo "<p><strong>Rol actual del usuario ID 31:</strong> {$result->rol}</p>";
        
        if ($result->rol == 'admin') {
            echo "<p style='color: green;'>✅ ¡El rol se cambió exitosamente a 'admin'!</p>";
        } else {
            echo "<p style='color: red;'>❌ El rol NO se cambió. Sigue siendo '{$result->rol}'</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ No se pudo obtener el usuario de la base de datos</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h2>🔍 Análisis del Problema:</h2>";
echo "<p>Basándome en esta prueba, el problema del cambio de rol puede estar en:</p>";
echo "<ul>";
echo "<li><strong>Autenticación:</strong> La sesión de admin puede haber expirado</li>";
echo "<li><strong>CSRF Token:</strong> El token puede ser inválido</li>";
echo "<li><strong>Validación:</strong> El formulario puede tener errores de validación</li>";
echo "<li><strong>Redirección:</strong> El controlador puede estar redirigiendo antes de procesar</li>";
echo "</ul>";

echo "<h2>🔗 Enlaces de Prueba:</h2>";
echo "<p><a href='/prueba-php/public/admin/login' target='_blank'>🔐 Login de Admin</a></p>";
echo "<p><a href='/prueba-php/public/admin/usuarios' target='_blank'>👥 Lista de Usuarios</a></p>";
echo "<p><em>Script completado.</em></p>";
?>
