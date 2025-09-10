<?php
// Test del dashboard
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🧪 Test del Dashboard</h1>";

// Test 1: Verificar archivo del dashboard
echo "<h2>1. Verificando archivo del dashboard</h2>";
$dashboardFile = '../src/views/admin/dashboard.php';
if (file_exists($dashboardFile)) {
    echo "✅ Dashboard existe<br>";
    
    // Leer las primeras líneas para verificar que no esté vacío
    $content = file_get_contents($dashboardFile);
    if (strlen($content) > 100) {
        echo "✅ Dashboard tiene contenido (" . strlen($content) . " caracteres)<br>";
    } else {
        echo "❌ Dashboard parece estar vacío<br>";
    }
} else {
    echo "❌ Dashboard no existe<br>";
}

// Test 2: Verificar layout admin
echo "<h2>2. Verificando layout admin</h2>";
$layoutFile = '../src/views/layouts/admin.php';
if (file_exists($layoutFile)) {
    echo "✅ Layout admin existe<br>";
} else {
    echo "❌ Layout admin no existe<br>";
}

// Test 3: Enlaces de prueba
echo "<h2>3. Enlaces de prueba</h2>";
echo "<a href='/prueba-php/public/admin/dashboard' target='_blank'>Dashboard Admin</a><br>";
echo "<a href='/prueba-php/public/admin/productos' target='_blank'>Gestión de Productos</a><br>";

echo "<hr>";
echo "<h2>🎯 Prueba ahora</h2>";
echo "<p>Refresca el dashboard y deberías ver la interfaz visual en lugar de las consultas SQL.</p>";
?>
