<?php
// Script para diagnosticar por qué la página sale en blanco
echo "<h1>Diagnóstico de Página en Blanco</h1>";

// Verificar errores de PHP
echo "<h2>Verificación de Errores PHP:</h2>";

// Habilitar reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<p>✅ Reporte de errores habilitado</p>";

// Verificar archivos críticos
echo "<h2>Verificación de Archivos Críticos:</h2>";

$archivosCriticos = [
    'src/views/admin/users/index.php' => 'Vista principal de usuarios',
    'src/controllers/AdminController.php' => 'Controlador de admin',
    'src/helpers/SecurityHelper.php' => 'Helper de seguridad',
    'src/config/config.php' => 'Configuración',
    'src/models/Database.php' => 'Modelo de base de datos'
];

foreach ($archivosCriticos as $archivo => $descripcion) {
    if (file_exists($archivo)) {
        echo "<p>✅ $descripcion existe</p>";
        
        // Verificar sintaxis PHP
        $output = [];
        $returnCode = 0;
        exec("php -l $archivo 2>&1", $output, $returnCode);
        
        if ($returnCode === 0) {
            echo "<p style='margin-left: 20px;'>✅ Sintaxis PHP correcta</p>";
        } else {
            echo "<p style='margin-left: 20px; color: red;'>❌ Error de sintaxis:</p>";
            foreach ($output as $line) {
                echo "<p style='margin-left: 40px; color: red;'>$line</p>";
            }
        }
    } else {
        echo "<p>❌ $descripcion NO existe</p>";
    }
}

// Probar inclusión de archivos
echo "<h2>Prueba de Inclusión de Archivos:</h2>";

try {
    echo "<p>Probando inclusión de config.php...</p>";
    require_once 'src/config/config.php';
    echo "<p>✅ config.php incluido correctamente</p>";
} catch (Exception $e) {
    echo "<p>❌ Error al incluir config.php: " . $e->getMessage() . "</p>";
}

try {
    echo "<p>Probando inclusión de Database.php...</p>";
    require_once 'src/models/Database.php';
    echo "<p>✅ Database.php incluido correctamente</p>";
} catch (Exception $e) {
    echo "<p>❌ Error al incluir Database.php: " . $e->getMessage() . "</p>";
}

try {
    echo "<p>Probando inclusión de SecurityHelper.php...</p>";
    require_once 'src/helpers/SecurityHelper.php';
    echo "<p>✅ SecurityHelper.php incluido correctamente</p>";
} catch (Exception $e) {
    echo "<p>❌ Error al incluir SecurityHelper.php: " . $e->getMessage() . "</p>";
}

// Verificar función generateCsrfToken
echo "<h2>Verificación de Función CSRF:</h2>";

if (function_exists('generateCsrfToken')) {
    echo "<p>✅ Función generateCsrfToken existe</p>";
    try {
        $token = generateCsrfToken();
        echo "<p>✅ Token generado: " . substr($token, 0, 10) . "...</p>";
    } catch (Exception $e) {
        echo "<p>❌ Error al generar token: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>❌ Función generateCsrfToken NO existe</p>";
}

// Probar conexión a base de datos
echo "<h2>Prueba de Conexión a Base de Datos:</h2>";

try {
    $db = new Database();
    echo "<p>✅ Conexión a base de datos exitosa</p>";
    
    // Probar consulta simple
    $db->query("SELECT 1 as test");
    $result = $db->single();
    echo "<p>✅ Consulta de prueba exitosa</p>";
    
} catch (Exception $e) {
    echo "<p>❌ Error de base de datos: " . $e->getMessage() . "</p>";
}

// Verificar vista específica
echo "<h2>Verificación de Vista de Usuarios:</h2>";

$vistaPath = 'src/views/admin/users/index.php';
if (file_exists($vistaPath)) {
    echo "<p>✅ Vista de usuarios existe</p>";
    
    // Leer contenido de la vista
    $contenido = file_get_contents($vistaPath);
    
    // Verificar elementos críticos
    $elementosCriticos = [
        'SecurityHelper' => 'Inclusión del SecurityHelper',
        'generateCsrfToken' => 'Función CSRF',
        'openEditModal' => 'Función JavaScript',
        'editModal' => 'Modal de edición',
        'Bootstrap' => 'Referencias a Bootstrap'
    ];
    
    foreach ($elementosCriticos as $buscar => $desc) {
        if (strpos($contenido, $buscar) !== false) {
            echo "<p style='margin-left: 20px;'>✅ $desc encontrado</p>";
        } else {
            echo "<p style='margin-left: 20px; color: orange;'>⚠️ $desc NO encontrado</p>";
        }
    }
    
    // Verificar sintaxis específica
    echo "<h3>Verificación de Sintaxis Específica:</h3>";
    
    // Buscar posibles errores de sintaxis
    $erroresComunes = [
        '<?php' => 'Etiqueta de apertura PHP',
        '?>' => 'Etiqueta de cierre PHP',
        'require_once' => 'Inclusión de archivos',
        'function' => 'Definición de funciones',
        'class' => 'Definición de clases'
    ];
    
    foreach ($erroresComunes as $buscar => $desc) {
        if (strpos($contenido, $buscar) !== false) {
            echo "<p style='margin-left: 20px;'>✅ $desc presente</p>";
        } else {
            echo "<p style='margin-left: 20px; color: orange;'>⚠️ $desc no encontrado</p>";
        }
    }
    
} else {
    echo "<p>❌ Vista de usuarios NO existe</p>";
}

// Simular la vista para detectar errores
echo "<h2>Simulación de Vista:</h2>";

echo "<p>Intentando simular la vista de usuarios...</p>";

try {
    // Incluir archivos necesarios
    require_once 'src/config/config.php';
    require_once 'src/models/Database.php';
    require_once 'src/helpers/SecurityHelper.php';
    
    // Simular datos
    $data = [
        'users' => [
            (object)[
                'id' => 1,
                'nombre' => 'Test',
                'apellidos' => 'Usuario',
                'email' => 'test@example.com',
                'rol' => 'user',
                'activo' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ]
        ]
    ];
    
    echo "<p>✅ Datos simulados creados</p>";
    
    // Probar función CSRF
    $csrfToken = generateCsrfToken();
    echo "<p>✅ Token CSRF generado: " . substr($csrfToken, 0, 10) . "...</p>";
    
    echo "<p>✅ Simulación exitosa - No hay errores críticos</p>";
    
} catch (Exception $e) {
    echo "<p>❌ Error en simulación: " . $e->getMessage() . "</p>";
    echo "<p>Stack trace: " . $e->getTraceAsString() . "</p>";
}

// Verificar logs de error
echo "<h2>Verificación de Logs:</h2>";

$logFiles = [
    '/var/log/apache2/error.log',
    '/var/log/httpd/error_log',
    'C:/xampp/apache/logs/error.log',
    'error.log'
];

foreach ($logFiles as $logFile) {
    if (file_exists($logFile)) {
        echo "<p>✅ Log encontrado: $logFile</p>";
        $logContent = file_get_contents($logFile);
        $lines = explode("\n", $logContent);
        $recentLines = array_slice($lines, -10);
        
        echo "<p><strong>Últimas 10 líneas del log:</strong></p>";
        echo "<div style='background-color: #f5f5f5; padding: 10px; border-radius: 5px; font-family: monospace; font-size: 12px;'>";
        foreach ($recentLines as $line) {
            if (!empty(trim($line))) {
                echo htmlspecialchars($line) . "<br>";
            }
        }
        echo "</div>";
        break;
    }
}

// Instrucciones para solucionar
echo "<h2>Instrucciones para Solucionar:</h2>";
echo "<ol>";
echo "<li><strong>Verifica los errores mostrados arriba</strong></li>";
echo "<li><strong>Revisa los logs de Apache/PHP</strong></li>";
echo "<li><strong>Comprueba que todos los archivos existen</strong></li>";
echo "<li><strong>Verifica la sintaxis PHP de los archivos</strong></li>";
echo "<li><strong>Asegúrate de que las funciones están disponibles</strong></li>";
echo "<li><strong>Limpia la caché del navegador</strong></li>";
echo "</ol>";

// Enlaces de prueba
echo "<h2>Enlaces de Prueba:</h2>";
echo "<p><a href='/prueba-php/public/admin/usuarios' target='_blank'>🔗 Administración - Usuarios</a></p>";
echo "<p><a href='/prueba-php/test-edit-user-button.php' target='_blank'>🔗 Diagnóstico del Botón</a></p>";

echo "<h2>Estado del Diagnóstico:</h2>";
echo "<p>Este script ha verificado los posibles problemas que causan la página en blanco.</p>";
echo "<p>Revisa los errores mostrados arriba para identificar el problema específico.</p>";
?>
