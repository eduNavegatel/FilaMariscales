<?php
// Script para probar el formulario web a través de HTTP
echo "<h1>🌐 Prueba del Formulario Web vía HTTP</h1>";

try {
    // Configurar cURL para simular el envío del formulario
    $url = 'http://localhost/prueba-php/public/admin/editarUsuario/31';
    
    // Datos del formulario
    $postData = [
        'csrf_token' => 'test-token',
        'user_id' => '31',
        'nombre' => 'Usuario de Prueba',
        'apellidos' => 'Apellido de Prueba',
        'email' => 'test@example.com',
        'rol' => 'socio', // Cambiando a socio
        'activo' => '1'
    ];
    
    echo "<h2>📝 Datos a enviar:</h2>";
    echo "<pre>" . print_r($postData, true) . "</pre>";
    
    echo "<h2>🔗 URL de destino:</h2>";
    echo "<p><code>$url</code></p>";
    
    // Configurar cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    
    echo "<h2>📤 Enviando petición...</h2>";
    
    // Ejecutar la petición
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    
    curl_close($ch);
    
    if ($error) {
        echo "<p style='color: red;'>❌ Error de cURL: $error</p>";
    } else {
        echo "<h2>📥 Respuesta recibida:</h2>";
        echo "<p><strong>Código HTTP:</strong> $httpCode</p>";
        
        // Separar headers y body
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);
        
        echo "<h3>📋 Headers de respuesta:</h3>";
        echo "<pre>" . htmlspecialchars($headers) . "</pre>";
        
        echo "<h3>📄 Cuerpo de la respuesta:</h3>";
        echo "<div style='max-height: 400px; overflow-y: auto; border: 1px solid #ccc; padding: 1rem; background-color: #f8f9fa;'>";
        echo htmlspecialchars($body);
        echo "</div>";
        
        // Analizar la respuesta
        if ($httpCode >= 200 && $httpCode < 300) {
            echo "<p style='color: green;'>✅ Petición exitosa (HTTP $httpCode)</p>";
            
            // Verificar si hay redirección
            if (strpos($body, 'Location:') !== false || strpos($body, 'location.href') !== false) {
                echo "<p style='color: blue;'>🔄 Se detectó una redirección</p>";
            }
            
            // Verificar si hay mensajes de éxito
            if (strpos($body, 'Usuario actualizado') !== false || strpos($body, 'success') !== false) {
                echo "<p style='color: green;'>🎉 ¡Usuario actualizado correctamente!</p>";
            }
            
            // Verificar si hay errores
            if (strpos($body, 'error') !== false || strpos($body, 'Error') !== false) {
                echo "<p style='color: red;'>❌ Se detectaron errores en la respuesta</p>";
            }
            
        } elseif ($httpCode >= 300 && $httpCode < 400) {
            echo "<p style='color: orange;'>🔄 Redirección (HTTP $httpCode)</p>";
        } elseif ($httpCode >= 400 && $httpCode < 500) {
            echo "<p style='color: red;'>❌ Error del cliente (HTTP $httpCode)</p>";
        } elseif ($httpCode >= 500) {
            echo "<p style='color: red;'>❌ Error del servidor (HTTP $httpCode)</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h2>🔍 Análisis de la Respuesta:</h2>";
echo "<p>Si la petición HTTP funciona pero el formulario web no, el problema puede estar en:</p>";
echo "<ul>";
echo "<li>JavaScript que previene el envío del formulario</li>";
echo "<li>Problemas con el CSRF token en el navegador</li>";
echo "<li>Errores en la validación del lado del cliente</li>";
echo "<li>Problemas con el enrutador de la aplicación</li>";
echo "<li>Diferencias entre el entorno de desarrollo y producción</li>";
echo "</ul>";

echo "<h2>🔗 Enlaces:</h2>";
echo "<p><a href='/prueba-php/public/admin/usuarios' target='_blank'>🚀 Ir a Gestión de Usuarios</a></p>";
echo "<p><a href='/prueba-php/public/admin/dashboard' target='_blank'>🏠 Ir al Dashboard</a></p>";
echo "<p><em>Script completado.</em></p>";
?>
