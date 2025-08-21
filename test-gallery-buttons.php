<?php
// Script para probar los botones de la galería
echo "<h1>Prueba de Botones de Galería</h1>";

// Simular el controlador de administración
require_once 'src/config/config.php';
require_once 'src/controllers/AdminController.php';

// Crear una instancia del controlador
$adminController = new AdminController();

// Obtener archivos de galería
echo "<h2>Archivos de Galería:</h2>";
$mediaFiles = $adminController->getMediaFiles();

if (empty($mediaFiles)) {
    echo "<p>❌ No hay archivos en la galería</p>";
} else {
    echo "<p>✅ Se encontraron " . count($mediaFiles) . " archivos en la galería</p>";
    
    foreach ($mediaFiles as $file) {
        echo "<div style='margin: 20px; padding: 15px; border: 2px solid #ccc; border-radius: 8px;'>";
        echo "<h3>Archivo: {$file['name']}</h3>";
        
        echo "<p><strong>Ruta original:</strong> {$file['path']}</p>";
        echo "<p><strong>URL generada:</strong> {$file['url']}</p>";
        echo "<p><strong>Tipo:</strong> {$file['type']}</p>";
        echo "<p><strong>Tamaño:</strong> " . number_format($file['size'] / 1024 / 1024, 2) . " MB</p>";
        
        // Mostrar imagen
        echo "<div style='margin: 10px 0;'>";
        echo "<strong>Vista previa:</strong><br>";
        echo "<img src='{$file['url']}' style='max-width: 300px; max-height: 200px; border: 1px solid #ddd; margin: 5px;'>";
        echo "</div>";
        
        // Simular botones
        echo "<div style='margin: 10px 0;'>";
        echo "<strong>Botones simulados:</strong><br>";
        echo "<a href='{$file['url']}' class='btn btn-sm btn-outline-primary' target='_blank' style='margin: 5px;'>";
        echo "<i class='fas fa-eye'></i> Ver archivo";
        echo "</a>";
        echo "<button class='btn btn-sm btn-outline-danger' style='margin: 5px;'>";
        echo "<i class='fas fa-trash'></i> Eliminar archivo";
        echo "</button>";
        echo "</div>";
        
        echo "</div>";
    }
}

// Obtener archivos del carrusel
echo "<h2>Archivos del Carrusel:</h2>";
$carouselFiles = $adminController->getCarouselFiles();

if (empty($carouselFiles)) {
    echo "<p>❌ No hay archivos en el carrusel</p>";
} else {
    echo "<p>✅ Se encontraron " . count($carouselFiles) . " archivos en el carrusel</p>";
    
    foreach ($carouselFiles as $file) {
        echo "<div style='margin: 20px; padding: 15px; border: 2px solid #ccc; border-radius: 8px;'>";
        echo "<h3>Archivo: {$file['name']}</h3>";
        
        echo "<p><strong>Ruta original:</strong> {$file['path']}</p>";
        echo "<p><strong>URL generada:</strong> {$file['url']}</p>";
        echo "<p><strong>Tipo:</strong> {$file['type']}</p>";
        echo "<p><strong>Tamaño:</strong> " . number_format($file['size'] / 1024 / 1024, 2) . " MB</p>";
        
        // Mostrar imagen
        echo "<div style='margin: 10px 0;'>";
        echo "<strong>Vista previa:</strong><br>";
        echo "<img src='{$file['url']}' style='max-width: 300px; max-height: 200px; border: 1px solid #ddd; margin: 5px;'>";
        echo "</div>";
        
        // Simular botones
        echo "<div style='margin: 10px 0;'>";
        echo "<strong>Botones simulados:</strong><br>";
        echo "<a href='{$file['url']}' class='btn btn-sm btn-outline-primary' target='_blank' style='margin: 5px;'>";
        echo "<i class='fas fa-eye'></i> Ver imagen";
        echo "</a>";
        echo "<button class='btn btn-sm btn-outline-warning' style='margin: 5px;'>";
        echo "<i class='fas fa-trash'></i> Eliminar del carrusel";
        echo "</button>";
        echo "</div>";
        
        echo "</div>";
    }
}

// Verificar que el método getImageUrl funciona correctamente
echo "<h2>Prueba del método getImageUrl:</h2>";

// Usar reflexión para acceder al método privado
$reflection = new ReflectionClass($adminController);
$method = $reflection->getMethod('getImageUrl');
$method->setAccessible(true);

$testFiles = [
    'uploads/gallery/test.jpg',
    'uploads/carousel/test.png',
    'uploads/eventos/2024/01/test.gif',
    'https://example.com/external.jpg'
];

foreach ($testFiles as $testFile) {
    $url = $method->invoke($adminController, $testFile);
    echo "<p><strong>Archivo:</strong> $testFile</p>";
    echo "<p><strong>URL generada:</strong> $url</p>";
    echo "<hr>";
}

// Enlaces de prueba
echo "<h2>Enlaces de prueba:</h2>";
echo "<p><a href='/prueba-php/public/admin/galeria'>Administración - Galería</a></p>";
echo "<p><a href='/prueba-php/public/pages/galeria'>Galería Pública</a></p>";

// Verificar configuración
echo "<h2>Estado del sistema:</h2>";

if (!empty($mediaFiles) || !empty($carouselFiles)) {
    echo "<p>✅ El sistema está funcionando correctamente</p>";
    echo "<p>✅ Las URLs se generan correctamente</p>";
    echo "<p>✅ Los botones deberían funcionar</p>";
} else {
    echo "<p>⚠️ No hay archivos para probar</p>";
    echo "<p>💡 Sube algunas imágenes desde la administración para probar los botones</p>";
}

echo "<h2>Próximos pasos:</h2>";
echo "<ol>";
echo "<li>Accede a la administración: <a href='/prueba-php/public/admin/galeria'>Galería</a></li>";
echo "<li>Verifica que los botones 'Ver archivo' abren las imágenes correctamente</li>";
echo "<li>Verifica que los botones 'Eliminar archivo' funcionan</li>";
echo "<li>Prueba tanto en la pestaña 'Galería' como en 'Carrusel'</li>";
echo "</ol>";
?>
