<?php
// Script para probar el checkout
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Prueba del Checkout</h2>";

// Iniciar sesión
session_start();

echo "<h3>1. Estado del carrito:</h3>";
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    echo "<p>Carrito tiene " . count($_SESSION['cart']) . " productos</p>";
    echo "<pre>" . print_r($_SESSION['cart'], true) . "</pre>";
} else {
    echo "<p>Carrito está vacío - añadiendo producto de prueba</p>";
    $_SESSION['cart'][1] = [
        'id' => 1,
        'nombre' => 'Producto de Prueba',
        'precio' => 25.99,
        'imagen' => 'test.jpg',
        'quantity' => 1
    ];
    echo "<p>Producto añadido al carrito</p>";
}

echo "<h3>2. Probando OrderController:</h3>";

try {
    // Cargar configuración
    require_once 'src/config/config.php';
    require_once 'src/config/helpers.php';
    
    // Cargar controlador
    require_once 'src/controllers/Controller.php';
    require_once 'src/controllers/OrderController.php';
    
    // Crear instancia
    $orderController = new OrderController();
    
    echo "<p>✅ OrderController cargado correctamente</p>";
    
    // Probar checkout
    echo "<h4>Probando checkout():</h4>";
    $orderController->checkout();
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p>Archivo: " . $e->getFile() . "</p>";
    echo "<p>Línea: " . $e->getLine() . "</p>";
    echo "<p>Trace: " . $e->getTraceAsString() . "</p>";
}

echo "<h3>3. Enlaces de prueba:</h3>";
echo "<p><a href='/prueba-php/public/tienda' target='_blank'>Ir a la Tienda</a></p>";
echo "<p><a href='/prueba-php/public/cart' target='_blank'>Ver Carrito</a></p>";
echo "<p><a href='/prueba-php/public/order/checkout' target='_blank'>Ir al Checkout</a></p>";
?>
