<?php
// Test simple de la funcionalidad de tienda
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🧪 Test de Funcionalidad de Tienda</h1>";

// Test 1: Verificar rutas
echo "<h2>1. Enlaces de prueba</h2>";
echo "<a href='/prueba-php/public/admin/productos' target='_blank'>Gestión de Productos</a><br>";
echo "<a href='/prueba-php/public/admin/nuevo-producto' target='_blank'>Nuevo Producto</a><br>";
echo "<a href='/prueba-php/public/admin/dashboard' target='_blank'>Dashboard</a><br>";

// Test 2: Verificar archivos
echo "<h2>2. Verificando archivos</h2>";
$archivos = [
    '../src/models/Product.php',
    '../src/views/admin/tienda/productos.php',
    '../src/views/admin/tienda/nuevo-producto.php'
];

foreach ($archivos as $archivo) {
    if (file_exists($archivo)) {
        echo "✅ " . basename($archivo) . " existe<br>";
    } else {
        echo "❌ " . basename($archivo) . " NO existe<br>";
    }
}

// Test 3: Verificar modelo
echo "<h2>3. Verificando modelo Product</h2>";
try {
    require_once '../src/config/config.php';
    require_once '../src/config/Database.php';
    require_once '../src/models/Product.php';
    
    $productModel = new Product();
    echo "✅ Modelo Product creado<br>";
    
    $count = $productModel->countProducts();
    echo "✅ Productos en la base de datos: " . $count . "<br>";
    
} catch (Exception $e) {
    echo "❌ Error con modelo Product: " . $e->getMessage() . "<br>";
}

echo "<hr>";
echo "<h2>🎯 Prueba el botón ahora</h2>";
echo "<p>Ve al dashboard y prueba el botón 'Gestionar Tienda'</p>";
?>
