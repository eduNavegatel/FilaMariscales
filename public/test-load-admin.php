<?php
// Test súper simple: solo cargar AdminController
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🧪 Test: Solo Cargar AdminController</h1>";

// Paso 1: Verificar que PHP funciona
echo "<p>✅ Paso 1: PHP funciona</p>";

// Paso 2: Intentar cargar archivos uno por uno
echo "<h2>Paso 2: Cargando archivos</h2>";

try {
    echo "<p>Intentando cargar config.php...</p>";
    require_once '../src/config/config.php';
    echo "<p>✅ config.php cargado</p>";
} catch (Exception $e) {
    echo "<p>❌ Error en config.php: " . $e->getMessage() . "</p>";
    exit;
}

try {
    echo "<p>Intentando cargar helpers.php...</p>";
    require_once '../src/config/helpers.php';
    echo "<p>✅ helpers.php cargado</p>";
} catch (Exception $e) {
    echo "<p>❌ Error en helpers.php: " . $e->getMessage() . "</p>";
    exit;
}

try {
    echo "<p>Intentando cargar admin_credentials.php...</p>";
    require_once '../src/config/admin_credentials.php';
    echo "<p>✅ admin_credentials.php cargado</p>";
} catch (Exception $e) {
    echo "<p>❌ Error en admin_credentials.php: " . $e->getMessage() . "</p>";
    exit;
}

try {
    echo "<p>Intentando cargar Controller.php...</p>";
    require_once '../src/controllers/Controller.php';
    echo "<p>✅ Controller.php cargado</p>";
} catch (Exception $e) {
    echo "<p>❌ Error en Controller.php: " . $e->getMessage() . "</p>";
    exit;
}

// Paso 3: Intentar cargar AdminController
echo "<h2>Paso 3: Cargando AdminController</h2>";

try {
    echo "<p>Intentando cargar AdminController.php...</p>";
    
    // Usar include en lugar de require para capturar errores
    $result = include '../src/controllers/AdminController.php';
    
    if ($result === false) {
        echo "<p>❌ AdminController.php falló al cargar</p>";
        exit;
    }
    
    echo "<p>✅ AdminController.php cargado</p>";
    
    // Verificar si la clase existe
    if (class_exists('AdminController')) {
        echo "<p>✅ Clase AdminController existe</p>";
        
        // Intentar crear instancia
        try {
            echo "<p>Intentando crear instancia...</p>";
            $adminController = new AdminController();
            echo "<p>✅ Instancia creada correctamente</p>";
            
            // Probar método dashboard
            if (method_exists($adminController, 'dashboard')) {
                echo "<p>✅ Método dashboard existe</p>";
                echo "<p>🎉 AdminController funcionando correctamente!</p>";
            } else {
                echo "<p>❌ Método dashboard NO existe</p>";
            }
            
        } catch (Exception $e) {
            echo "<p>❌ Error creando instancia: " . $e->getMessage() . "</p>";
            echo "<h3>Stack trace:</h3>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
        }
        
    } else {
        echo "<p>❌ Clase AdminController NO existe después de cargar el archivo</p>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error cargando AdminController: " . $e->getMessage() . "</p>";
    echo "<h3>Stack trace:</h3>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
} catch (Error $e) {
    echo "<p>❌ Error fatal en AdminController: " . $e->getMessage() . "</p>";
    echo "<h3>Stack trace:</h3>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h2>🎯 Resumen</h2>";
echo "<p>Si llegaste hasta aquí, el AdminController se cargó correctamente.</p>";
echo "<p>Si no llegaste hasta aquí, hay un error fatal en algún paso.</p>";

echo "<div style='margin-top: 20px;'>";
echo "<a href='/prueba-php/public/admin/' class='btn btn-primary'>Probar Admin</a>";
echo "<a href='/prueba-php/public/admin/dashboard' class='btn btn-secondary' style='margin-left: 10px;'>Probar Dashboard</a>";
echo "</div>";
