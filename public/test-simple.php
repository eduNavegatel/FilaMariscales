<?php
// Test súper simple para identificar el problema
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🧪 Test Súper Simple</h1>";

// Paso 1: Verificar que PHP funciona
echo "<p>✅ PHP funciona</p>";

// Paso 2: Verificar que podemos cargar archivos
echo "<h2>Paso 2: Cargando archivos</h2>";

try {
    echo "<p>Intentando cargar config.php...</p>";
    require_once '../src/config/config.php';
    echo "<p>✅ config.php cargado</p>";
} catch (Exception $e) {
    echo "<p>❌ Error cargando config.php: " . $e->getMessage() . "</p>";
    exit;
}

try {
    echo "<p>Intentando cargar helpers.php...</p>";
    require_once '../src/config/helpers.php';
    echo "<p>✅ helpers.php cargado</p>";
} catch (Exception $e) {
    echo "<p>❌ Error cargando helpers.php: " . $e->getMessage() . "</p>";
    exit;
}

try {
    echo "<p>Intentando cargar admin_credentials.php...</p>";
    require_once '../src/config/admin_credentials.php';
    echo "<p>✅ admin_credentials.php cargado</p>";
} catch (Exception $e) {
    echo "<p>❌ Error cargando admin_credentials.php: " . $e->getMessage() . "</p>";
    exit;
}

// Paso 3: Verificar funciones
echo "<h2>Paso 3: Verificando funciones</h2>";
echo "<p>isAdminLoggedIn existe: " . (function_exists('isAdminLoggedIn') ? 'SÍ' : 'NO') . "</p>";
echo "<p>getAdminInfo existe: " . (function_exists('getAdminInfo') ? 'SÍ' : 'NO') . "</p>";

// Paso 4: Verificar sesión
echo "<h2>Paso 4: Verificando sesión</h2>";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    echo "<p>⚠️ Sesión iniciada manualmente</p>";
}

echo "<p>Estado de sesión: " . (session_status() === PHP_SESSION_ACTIVE ? 'Activa' : 'Inactiva') . "</p>";
echo "<p>Datos de sesión: " . print_r($_SESSION, true) . "</p>";

// Paso 5: Probar función isAdminLoggedIn
echo "<h2>Paso 5: Probando isAdminLoggedIn</h2>";
if (function_exists('isAdminLoggedIn')) {
    try {
        $result = isAdminLoggedIn();
        echo "<p>✅ isAdminLoggedIn() ejecutada: " . ($result ? 'TRUE' : 'FALSE') . "</p>";
    } catch (Exception $e) {
        echo "<p>❌ Error en isAdminLoggedIn(): " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>❌ isAdminLoggedIn no está disponible</p>";
}

// Paso 6: Intentar cargar AdminController
echo "<h2>Paso 6: Cargando AdminController</h2>";
try {
    echo "<p>Intentando cargar AdminController.php...</p>";
    require_once '../src/controllers/AdminController.php';
    echo "<p>✅ AdminController.php cargado</p>";
    
    if (class_exists('AdminController')) {
        echo "<p>✅ Clase AdminController existe</p>";
        
        try {
            echo "<p>Intentando crear instancia...</p>";
            $adminController = new AdminController();
            echo "<p>✅ Instancia creada correctamente</p>";
            
            // Probar método dashboard
            if (method_exists($adminController, 'dashboard')) {
                echo "<p>✅ Método dashboard existe</p>";
                
                echo "<p>Intentando ejecutar dashboard...</p>";
                ob_start();
                $adminController->dashboard();
                $output = ob_get_clean();
                echo "<p>✅ Dashboard ejecutado. Output length: " . strlen($output) . "</p>";
                
                if (strlen($output) > 0) {
                    echo "<h3>Primeros 500 caracteres del output:</h3>";
                    echo "<pre>" . htmlspecialchars(substr($output, 0, 500)) . "</pre>";
                }
                
            } else {
                echo "<p>❌ Método dashboard NO existe</p>";
            }
            
        } catch (Exception $e) {
            echo "<p>❌ Error creando instancia: " . $e->getMessage() . "</p>";
            echo "<h3>Stack trace:</h3>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
        }
        
    } else {
        echo "<p>❌ Clase AdminController NO existe</p>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error cargando AdminController: " . $e->getMessage() . "</p>";
    echo "<h3>Stack trace:</h3>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h2>🎯 Resumen</h2>";
echo "<p>Si llegaste hasta aquí, el problema está en la ejecución del método dashboard.</p>";
echo "<p>Si no llegaste hasta aquí, el problema está en la carga de archivos o creación de instancia.</p>";

echo "<div style='margin-top: 20px;'>";
echo "<a href='/prueba-php/public/admin/' class='btn btn-primary'>Probar Admin</a>";
echo "<a href='/prueba-php/public/admin/dashboard' class='btn btn-secondary' style='margin-left: 10px;'>Probar Dashboard</a>";
echo "</div>";
?>
