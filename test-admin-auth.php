<?php
// Script para diagnosticar la autenticación del administrador
echo "<h1>🔐 Diagnóstico de Autenticación de Administrador</h1>";

try {
    // Incluir archivos necesarios
    require_once 'src/config/config.php';
    require_once 'src/config/admin_credentials.php';
    require_once 'src/helpers/SecurityHelper.php';
    
    echo "<p>✅ Archivos de configuración cargados</p>";
    
    // Verificar si hay una sesión activa
    echo "<h2>📋 Estado de la Sesión:</h2>";
    
    if (session_status() == PHP_SESSION_NONE) {
        echo "<p>⚠️ No hay sesión iniciada</p>";
        session_start();
    } else {
        echo "<p>✅ Sesión ya iniciada</p>";
    }
    
    echo "<ul>";
    echo "<li><strong>ID de sesión:</strong> " . session_id() . "</li>";
    echo "<li><strong>Nombre de sesión:</strong> " . session_name() . "</li>";
    echo "<li><strong>Estado de sesión:</strong> " . session_status() . "</li>";
    echo "</ul>";
    
    // Verificar variables de sesión
    echo "<h2>🔍 Variables de Sesión:</h2>";
    
    if (empty($_SESSION)) {
        echo "<p>⚠️ No hay variables de sesión</p>";
    } else {
        echo "<p>✅ Variables de sesión encontradas:</p>";
        echo "<pre>" . print_r($_SESSION, true) . "</pre>";
    }
    
    // Verificar función de autenticación
    echo "<h2>🔑 Función de Autenticación:</h2>";
    
    if (function_exists('isAdminLoggedIn')) {
        echo "<p>✅ Función isAdminLoggedIn existe</p>";
        
        $authResult = isAdminLoggedIn();
        echo "<p><strong>Resultado de isAdminLoggedIn():</strong> " . ($authResult ? 'TRUE' : 'FALSE') . "</p>";
        
        if ($authResult) {
            echo "<p style='color: green;'>✅ Usuario autenticado como administrador</p>";
        } else {
            echo "<p style='color: red;'>❌ Usuario NO autenticado como administrador</p>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ Función isAdminLoggedIn NO existe</p>";
    }
    
    // Verificar credenciales de admin
    echo "<h2>👤 Credenciales de Administrador:</h2>";
    
    if (defined('ADMIN_USERNAME') && defined('ADMIN_PASSWORD')) {
        echo "<p>✅ Constantes de admin definidas</p>";
        echo "<ul>";
        echo "<li><strong>Username:</strong> " . ADMIN_USERNAME . "</li>";
        echo "<li><strong>Password:</strong> " . (ADMIN_PASSWORD ? '***DEFINIDO***' : 'NO DEFINIDO') . "</li>";
        echo "</ul>";
    } else {
        echo "<p style='color: red;'>❌ Constantes de admin NO definidas</p>";
    }
    
    // Verificar cookies
    echo "<h2>🍪 Cookies:</h2>";
    
    if (empty($_COOKIE)) {
        echo "<p>⚠️ No hay cookies</p>";
    } else {
        echo "<p>✅ Cookies encontradas:</p>";
        echo "<pre>" . print_r($_COOKIE, true) . "</pre>";
    }
    
    // Verificar headers HTTP
    echo "<h2>📡 Headers HTTP:</h2>";
    
    $headers = getallheaders();
    if ($headers) {
        echo "<p>✅ Headers HTTP encontrados:</p>";
        echo "<pre>" . print_r($headers, true) . "</pre>";
    } else {
        echo "<p>⚠️ No se pudieron obtener headers HTTP</p>";
    }
    
    // Probar login manual
    echo "<h2>🧪 Prueba de Login Manual:</h2>";
    
    if (function_exists('isAdminLoggedIn')) {
        // Simular login
        echo "<p>Intentando simular login...</p>";
        
        // Verificar si hay algún mecanismo de login en el archivo de credenciales
        $adminCredsContent = file_get_contents('src/config/admin_credentials.php');
        
        if (strpos($adminCredsContent, 'function') !== false) {
            echo "<p>✅ Archivo admin_credentials.php contiene funciones</p>";
        } else {
            echo "<p>⚠️ Archivo admin_credentials.php solo contiene constantes</p>";
        }
        
        // Mostrar contenido del archivo (sin contraseñas)
        echo "<h3>📄 Contenido de admin_credentials.php:</h3>";
        echo "<div style='border: 1px solid #ccc; padding: 1rem; max-height: 300px; overflow-y: auto; background-color: #f8f9fa;'>";
        $safeContent = preg_replace('/ADMIN_PASSWORD\s*=\s*["\'][^"\']*["\']/', 'ADMIN_PASSWORD = "***OCULTO***"', $adminCredsContent);
        echo htmlspecialchars($safeContent);
        echo "</div>";
    }
    
    // Verificar archivo de configuración principal
    echo "<h2>⚙️ Configuración Principal:</h2>";
    
    $configContent = file_get_contents('src/config/config.php');
    
    if (strpos($configContent, 'session_start') !== false) {
        echo "<p>✅ Config.php contiene session_start</p>";
    } else {
        echo "<p>⚠️ Config.php NO contiene session_start</p>";
    }
    
    // Verificar si hay algún problema con la función de autenticación
    echo "<h2>🔍 Análisis de la Función de Autenticación:</h2>";
    
    if (function_exists('isAdminLoggedIn')) {
        // Intentar obtener el código fuente de la función
        $reflection = new ReflectionFunction('isAdminLoggedIn');
        $filename = $reflection->getFileName();
        $startLine = $reflection->getStartLine();
        $endLine = $reflection->getEndLine();
        
        echo "<p><strong>Archivo:</strong> $filename</p>";
        echo "<p><strong>Líneas:</strong> $startLine - $endLine</p>";
        
        if (file_exists($filename)) {
            $lines = file($filename);
            $functionCode = implode('', array_slice($lines, $startLine - 1, $endLine - $startLine + 1));
            
            echo "<h3>📄 Código de la función isAdminLoggedIn:</h3>";
            echo "<div style='border: 1px solid #ccc; padding: 1rem; max-height: 300px; overflow-y: auto; background-color: #f8f9fa;'>";
            echo htmlspecialchars($functionCode);
            echo "</div>";
        }
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p>Stack trace:</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<hr>";
echo "<h2>🔍 Resumen del Problema:</h2>";
echo "<p>El problema del cambio de rol se debe a que:</p>";
echo "<ul>";
echo "<li><strong>La sesión de administrador no está activa</strong></li>";
echo "<li><strong>El sistema redirige al login antes de procesar el formulario</strong></li>";
echo "<li><strong>El controlador nunca recibe la petición POST</strong></li>";
echo "</ul>";

echo "<h2>🛠️ Soluciones Recomendadas:</h2>";
echo "<ol>";
echo "<li><strong>Iniciar sesión como administrador</strong> en /prueba-php/public/admin/login</li>";
echo "<li><strong>Verificar que la sesión no expire</strong> durante la edición</li>";
echo "<li><strong>Revisar la función isAdminLoggedIn</strong> para asegurar que funcione correctamente</li>";
echo "</ol>";

echo "<h2>🔗 Enlaces de Prueba:</h2>";
echo "<p><a href='/prueba-php/public/admin/login' target='_blank'>🔐 Login de Admin</a></p>";
echo "<p><a href='/prueba-php/public/admin/usuarios' target='_blank'>👥 Lista de Usuarios</a></p>";
echo "<p><em>Script completado.</em></p>";
?>
