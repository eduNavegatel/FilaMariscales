<?php
// Script para probar las redirecciones de galería
echo "<h1>🔧 Prueba de Redirecciones de Galería</h1>";

// Incluir configuración
require_once 'src/config/config.php';
require_once 'src/controllers/AdminController.php';

echo "<h2>📋 Verificación de Configuración</h2>";
echo "<p><strong>URL_ROOT:</strong> " . URL_ROOT . "</p>";
echo "<p><strong>Directorio actual:</strong> " . __DIR__ . "</p>";

// Verificar que el controlador existe
if (class_exists('AdminController')) {
    echo "<p>✅ <strong>AdminController</strong> cargado correctamente</p>";
} else {
    echo "<p>❌ <strong>AdminController</strong> no encontrado</p>";
}

// Verificar directorios de uploads
$galleryDir = 'uploads/gallery/';
$carouselDir = 'uploads/carousel/';

echo "<h2>📁 Verificación de Directorios</h2>";
if (is_dir($galleryDir)) {
    echo "<p>✅ Directorio de galería existe: <code>$galleryDir</code></p>";
} else {
    echo "<p>❌ Directorio de galería no existe: <code>$galleryDir</code></p>";
    echo "<p>Creando directorio...</p>";
    if (mkdir($galleryDir, 0755, true)) {
        echo "<p>✅ Directorio creado exitosamente</p>";
    } else {
        echo "<p>❌ Error al crear directorio</p>";
    }
}

if (is_dir($carouselDir)) {
    echo "<p>✅ Directorio de carrusel existe: <code>$carouselDir</code></p>";
} else {
    echo "<p>❌ Directorio de carrusel no existe: <code>$carouselDir</code></p>";
    echo "<p>Creando directorio...</p>";
    if (mkdir($carouselDir, 0755, true)) {
        echo "<p>✅ Directorio creado exitosamente</p>";
    } else {
        echo "<p>❌ Error al crear directorio</p>";
    }
}

// Verificar rutas
echo "<h2>🛣️ Verificación de Rutas</h2>";
$routes = [
    'galeria' => URL_ROOT . '/admin/galeria',
    'subirMedia' => URL_ROOT . '/admin/subirMedia',
    'eliminarMedia' => URL_ROOT . '/admin/eliminarMedia/test.jpg',
    'subirCarousel' => URL_ROOT . '/admin/subirCarousel',
    'eliminarCarousel' => URL_ROOT . '/admin/eliminarCarousel/test.jpg'
];

foreach ($routes as $name => $url) {
    echo "<p><strong>$name:</strong> <a href='$url' target='_blank'>$url</a></p>";
}

// Verificar función redirect
echo "<h2>🔄 Verificación de Función Redirect</h2>";
if (function_exists('redirect')) {
    echo "<p>✅ Función <code>redirect()</code> disponible</p>";
} else {
    echo "<p>❌ Función <code>redirect()</code> no encontrada</p>";
}

// Verificar función setFlashMessage
if (function_exists('setFlashMessage')) {
    echo "<p>✅ Función <code>setFlashMessage()</code> disponible</p>";
} else {
    echo "<p>❌ Función <code>setFlashMessage()</code> no encontrada</p>";
}

// Simular redirección (sin ejecutar realmente)
echo "<h2>🧪 Simulación de Redirecciones</h2>";
echo "<p>Las siguientes URLs deberían redirigir a la galería después de las operaciones:</p>";

$testUrls = [
    'Subir archivo a galería' => URL_ROOT . '/admin/subirMedia',
    'Eliminar archivo de galería' => URL_ROOT . '/admin/eliminarMedia/test.jpg',
    'Subir imagen al carrusel' => URL_ROOT . '/admin/subirCarousel',
    'Eliminar imagen del carrusel' => URL_ROOT . '/admin/eliminarCarousel/test.jpg'
];

echo "<ul>";
foreach ($testUrls as $action => $url) {
    echo "<li><strong>$action:</strong> <code>$url</code> → <code>" . URL_ROOT . "/admin/galeria</code></li>";
}
echo "</ul>";

// Verificar archivo de vista
echo "<h2>👁️ Verificación de Vista</h2>";
$viewFile = 'src/views/admin/gallery/index.php';
if (file_exists($viewFile)) {
    echo "<p>✅ Vista de galería existe: <code>$viewFile</code></p>";
    
    // Verificar formularios en la vista
    $viewContent = file_get_contents($viewFile);
    if (strpos($viewContent, 'action="<?= URL_ROOT ?>/admin/subirMedia"') !== false) {
        echo "<p>✅ Formulario de subir media configurado correctamente</p>";
    } else {
        echo "<p>❌ Formulario de subir media no encontrado o mal configurado</p>";
    }
    
    if (strpos($viewContent, 'action="<?= URL_ROOT ?>/admin/subirCarousel"') !== false) {
        echo "<p>✅ Formulario de subir carrusel configurado correctamente</p>";
    } else {
        echo "<p>❌ Formulario de subir carrusel no encontrado o mal configurado</p>";
    }
} else {
    echo "<p>❌ Vista de galería no encontrada: <code>$viewFile</code></p>";
}

echo "<h2>📝 Instrucciones de Prueba</h2>";
echo "<ol>";
echo "<li>Accede a <a href='" . URL_ROOT . "/admin/galeria' target='_blank'>Gestión de Galería</a></li>";
echo "<li>Intenta subir una imagen usando el botón 'Subir a Galería'</li>";
echo "<li>Verifica que después de subir te redirija a la página de galería</li>";
echo "<li>Intenta eliminar una imagen existente</li>";
echo "<li>Verifica que después de eliminar te redirija a la página de galería</li>";
echo "<li>Repite el proceso para el carrusel</li>";
echo "</ol>";

echo "<h2>🔗 Enlaces de Prueba</h2>";
echo "<p><a href='" . URL_ROOT . "/admin/galeria' class='btn btn-primary' target='_blank'>🚀 Ir a Gestión de Galería</a></p>";
echo "<p><a href='" . URL_ROOT . "/admin' class='btn btn-secondary' target='_blank'>🏠 Ir al Dashboard</a></p>";

echo "<hr>";
echo "<p><em>Script de prueba completado. Verifica que todas las redirecciones funcionen correctamente.</em></p>";
?>
