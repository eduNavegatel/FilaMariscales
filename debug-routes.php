<?php
// Script de debug para verificar rutas
require_once 'src/config/config.php';

echo "🔍 DEBUG DE RUTAS - Verificando configuración\n";
echo "============================================\n\n";

try {
    // Simular una petición GET a /socios
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_GET['url'] = 'socios';
    
    echo "📋 Información de la petición:\n";
    echo "- Método: " . $_SERVER['REQUEST_METHOD'] . "\n";
    echo "- URL: " . ($_GET['url'] ?? 'vacío') . "\n";
    echo "- URL_ROOT: " . URL_ROOT . "\n\n";
    
    // Verificar archivos necesarios
    echo "📁 Verificando archivos:\n";
    $files = [
        'src/core/Router.php',
        'src/controllers/Controller.php',
        'src/controllers/SociosController.php',
        'routes/web.php'
    ];
    
    $allFilesExist = true;
    foreach ($files as $file) {
        if (file_exists($file)) {
            echo "✅ {$file}\n";
        } else {
            echo "❌ {$file} (NO EXISTE)\n";
            $allFilesExist = false;
        }
    }
    
    if (!$allFilesExist) {
        echo "\n❌ Faltan archivos necesarios\n";
        exit(1);
    }
    
    echo "\n🔧 Cargando Router...\n";
    require_once 'src/core/Router.php';
    require_once 'src/controllers/Controller.php';
    require_once 'src/controllers/SociosController.php';
    
    echo "✅ Router cargado\n";
    
    // Verificar que la clase SociosController existe
    if (class_exists('SociosController')) {
        echo "✅ SociosController existe\n";
        
        $sociosController = new SociosController();
        if (method_exists($sociosController, 'index')) {
            echo "✅ Método index() existe\n";
        } else {
            echo "❌ Método index() no existe\n";
        }
    } else {
        echo "❌ SociosController no existe\n";
        exit(1);
    }
    
    echo "\n🛣️ Cargando rutas...\n";
    $router = require_once 'routes/web.php';
    
    if ($router instanceof Router) {
        echo "✅ Router instanciado correctamente\n";
    } else {
        echo "❌ Error al instanciar Router\n";
        exit(1);
    }
    
    echo "\n🚀 Todo parece estar configurado correctamente\n";
    echo "Intenta acceder a: " . URL_ROOT . "/socios\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "📁 Archivo: " . $e->getFile() . "\n";
    echo "📍 Línea: " . $e->getLine() . "\n";
}
?>



