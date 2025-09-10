<?php
// Test simple del dashboard
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🧪 Test del Dashboard</h1>";

try {
    // Cargar configuraciones
    require_once '../src/config/config.php';
    require_once '../src/config/Database.php';
    
    echo "✅ Configuraciones cargadas<br>";
    
    // Crear instancia de Database
    $db = new Database();
    echo "✅ Database creado<br>";
    
    // Probar consulta simple
    $db->query("SELECT COUNT(*) as count FROM users");
    $result = $db->single();
    echo "✅ Consulta de usuarios: " . $result->count . "<br>";
    
    // Probar consulta de eventos
    $db->query("SELECT COUNT(*) as count FROM eventos");
    $result = $db->single();
    echo "✅ Consulta de eventos: " . $result->count . "<br>";
    
    // Probar consulta de productos
    $db->query("SELECT COUNT(*) as total FROM products");
    $result = $db->single();
    echo "✅ Consulta de productos: " . $result->total . "<br>";
    
    echo "<hr>";
    echo "<h2>✅ Todas las consultas funcionan</h2>";
    echo "<a href='/prueba-php/public/admin/dashboard'>Probar Dashboard</a><br>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    echo "Stack trace: " . $e->getTraceAsString() . "<br>";
}
?>
