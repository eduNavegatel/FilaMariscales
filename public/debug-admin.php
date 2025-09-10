<?php
// Debug del AdminController
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔍 Debug del AdminController</h1>";

try {
    echo "<h2>1. Cargando configuraciones...</h2>";
    require_once '../src/config/config.php';
    echo "✅ config.php cargado<br>";
    
    require_once '../src/config/Database.php';
    echo "✅ Database.php cargado<br>";
    
    echo "<h2>2. Cargando AdminController...</h2>";
    require_once '../src/controllers/AdminController.php';
    echo "✅ AdminController.php cargado<br>";
    
    echo "<h2>3. Verificando clase...</h2>";
    if (class_exists('AdminController')) {
        echo "✅ Clase AdminController existe<br>";
    } else {
        echo "❌ Clase AdminController NO existe<br>";
    }
    
    echo "<h2>4. Creando instancia...</h2>";
    $admin = new AdminController();
    echo "✅ Instancia de AdminController creada<br>";
    
    echo "<h2>5. Verificando método dashboard...</h2>";
    if (method_exists($admin, 'dashboard')) {
        echo "✅ Método dashboard existe<br>";
    } else {
        echo "❌ Método dashboard NO existe<br>";
    }
    
    echo "<hr>";
    echo "<h2>✅ Todo parece estar bien</h2>";
    echo "<a href='/prueba-php/public/admin/dashboard'>Probar Dashboard</a><br>";
    
} catch (Exception $e) {
    echo "<h2>❌ Error encontrado:</h2>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>Archivo:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Línea:</strong> " . $e->getLine() . "</p>";
    echo "<p><strong>Stack trace:</strong></p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
} catch (Error $e) {
    echo "<h2>❌ Error fatal encontrado:</h2>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>Archivo:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Línea:</strong> " . $e->getLine() . "</p>";
    echo "<p><strong>Stack trace:</strong></p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
?>
