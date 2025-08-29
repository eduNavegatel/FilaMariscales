<?php
// Acceso directo a socios (sin router)
require_once '../src/config/config.php';
require_once '../src/config/helpers.php';
require_once '../src/controllers/Controller.php';
require_once '../src/controllers/SociosController.php';

try {
    // Simular la configuración necesaria
    if (!defined('APPROOT')) {
        define('APPROOT', dirname(dirname(__DIR__)) . '/src/views/');
    }
    
    $sociosController = new SociosController();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $sociosController->login();
    } else {
        $sociosController->index();
    }
} catch (Exception $e) {
    echo "<h1>Error en el Sistema</h1>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Archivo:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Línea:</strong> " . $e->getLine() . "</p>";
    echo "<hr>";
    echo "<p><a href='/prueba-php/public/'>Volver al inicio</a></p>";
}
?>
