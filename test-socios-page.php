<?php
// Script simple para probar la página de socios
echo "<h1>🧪 Prueba de la Página de Socios</h1>";

try {
    // Incluir la página de socios
    echo "<h2>📄 Incluyendo la página de socios...</h2>";
    
    // Simular que estamos en el contexto correcto
    $_GET['debug'] = '1'; // Activar modo debug
    
    // Incluir la página
    ob_start();
    include 'src/views/pages/socios.php';
    $output = ob_get_clean();
    
    echo "<h3>✅ Página incluida correctamente</h3>";
    echo "<p>Longitud del output: " . strlen($output) . " caracteres</p>";
    
    // Mostrar el contenido
    echo "<h3>📋 Contenido de la página:</h3>";
    echo "<div style='border: 1px solid #ccc; padding: 1rem; max-height: 500px; overflow-y: auto; background-color: #f8f9fa;'>";
    echo htmlspecialchars($output);
    echo "</div>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p>Stack trace:</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<hr>";
echo "<h2>🔗 Enlaces:</h2>";
echo "<p><a href='/prueba-php/public/socios?debug=1' target='_blank'>🚀 Ir a Página de Socios (con debug)</a></p>";
echo "<p><a href='/prueba-php/public/socios' target='_blank'>🚀 Ir a Página de Socios (sin debug)</a></p>";
echo "<p><a href='/prueba-php/public/' target='_blank'>🏠 Ir al Inicio</a></p>";
echo "<p><em>Script completado.</em></p>";
?>
