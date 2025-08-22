<?php
// Script para probar la funcionalidad de edición de descripciones
echo "<h1>📝 Prueba de Edición de Descripciones de Galería</h1>";

// Incluir configuración
require_once 'src/config/config.php';
require_once 'src/controllers/AdminController.php';

echo "<h2>📋 Verificación de Configuración</h2>";
echo "<p><strong>URL_ROOT:</strong> " . URL_ROOT . "</p>";

// Verificar que el controlador existe
if (class_exists('AdminController')) {
    echo "<p>✅ <strong>AdminController</strong> cargado correctamente</p>";
} else {
    echo "<p>❌ <strong>AdminController</strong> no encontrado</p>";
}

// Verificar directorios de descripciones
$galleryDescFile = 'uploads/gallery/descriptions.json';
$carouselDescFile = 'uploads/carousel/descriptions.json';

echo "<h2>📁 Verificación de Archivos de Descripciones</h2>";

if (file_exists($galleryDescFile)) {
    $galleryDesc = json_decode(file_get_contents($galleryDescFile), true);
    echo "<p>✅ Archivo de descripciones de galería existe: <code>$galleryDescFile</code></p>";
    echo "<p>📊 Descripciones en galería: " . count($galleryDesc ?? []) . "</p>";
} else {
    echo "<p>❌ Archivo de descripciones de galería no existe: <code>$galleryDescFile</code></p>";
    echo "<p>Se creará automáticamente al añadir la primera descripción</p>";
}

if (file_exists($carouselDescFile)) {
    $carouselDesc = json_decode(file_get_contents($carouselDescFile), true);
    echo "<p>✅ Archivo de descripciones de carrusel existe: <code>$carouselDescFile</code></p>";
    echo "<p>📊 Descripciones en carrusel: " . count($carouselDesc ?? []) . "</p>";
} else {
    echo "<p>❌ Archivo de descripciones de carrusel no existe: <code>$carouselDescFile</code></p>";
    echo "<p>Se creará automáticamente al añadir la primera descripción</p>";
}

// Verificar rutas de actualización
echo "<h2>🛣️ Verificación de Rutas de Actualización</h2>";
$updateRoutes = [
    'actualizarDescripcionGaleria' => URL_ROOT . '/admin/actualizarDescripcionGaleria',
    'actualizarDescripcionCarousel' => URL_ROOT . '/admin/actualizarDescripcionCarousel'
];

foreach ($updateRoutes as $name => $url) {
    echo "<p><strong>$name:</strong> <code>$url</code></p>";
}

// Simular actualización de descripción
echo "<h2>🧪 Simulación de Actualización</h2>";
echo "<p>Para probar la funcionalidad:</p>";
echo "<ol>";
echo "<li>Accede a <a href='" . URL_ROOT . "/admin/galeria' target='_blank'>Gestión de Galería</a></li>";
echo "<li>Busca una imagen en la galería o carrusel</li>";
echo "<li>Haz clic en el botón <i class='fas fa-edit'></i> junto a la descripción</li>";
echo "<li>Escribe una nueva descripción</li>";
echo "<li>Haz clic en <i class='fas fa-save'></i> para guardar</li>";
echo "<li>Verifica que la descripción se actualice</li>";
echo "</ol>";

// Mostrar ejemplo de estructura JSON
echo "<h2>📄 Estructura de Archivos de Descripciones</h2>";
echo "<p><strong>Galería:</strong> <code>uploads/gallery/descriptions.json</code></p>";
echo "<pre><code>{
  \"imagen1.jpg\": \"Descripción de la imagen 1\",
  \"imagen2.jpg\": \"Descripción de la imagen 2\",
  \"video1.mp4\": \"Descripción del video 1\"
}</code></pre>";

echo "<p><strong>Carrusel:</strong> <code>uploads/carousel/descriptions.json</code></p>";
echo "<pre><code>{
  \"carousel_imagen1.jpg\": \"Descripción del carrusel 1\",
  \"carousel_imagen2.jpg\": \"Descripción del carrusel 2\"
}</code></pre>";

// Verificar funciones del controlador
echo "<h2>🔧 Verificación de Funciones del Controlador</h2>";

$adminController = new AdminController();

// Verificar métodos usando reflection
$reflection = new ReflectionClass($adminController);
$methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

$requiredMethods = [
    'actualizarDescripcionGaleria',
    'actualizarDescripcionCarousel',
    'galeria'
];

foreach ($requiredMethods as $method) {
    if ($reflection->hasMethod($method)) {
        echo "<p>✅ Método <code>$method</code> existe</p>";
    } else {
        echo "<p>❌ Método <code>$method</code> no encontrado</p>";
    }
}

// Verificar métodos privados
$privateMethods = [
    'getImageDescription',
    'saveImageDescription'
];

foreach ($privateMethods as $method) {
    if ($reflection->hasMethod($method)) {
        echo "<p>✅ Método privado <code>$method</code> existe</p>";
    } else {
        echo "<p>❌ Método privado <code>$method</code> no encontrado</p>";
    }
}

// Mostrar instrucciones de uso
echo "<h2>📝 Instrucciones de Uso</h2>";
echo "<div style='background: #f8f9fa; padding: 20px; border-radius: 8px;'>";
echo "<h4>🎯 Cómo usar la edición de descripciones:</h4>";
echo "<ol>";
echo "<li><strong>Ver descripción actual:</strong> Cada imagen muestra su descripción actual o 'Sin descripción'</li>";
echo "<li><strong>Editar descripción:</strong> Haz clic en el botón <i class='fas fa-edit'></i> junto a la descripción</li>";
echo "<li><strong>Escribir nueva descripción:</strong> Aparecerá un campo de texto donde puedes escribir</li>";
echo "<li><strong>Guardar cambios:</strong> Haz clic en <i class='fas fa-save'></i> para guardar</li>";
echo "<li><strong>Cancelar cambios:</strong> Haz clic en <i class='fas fa-times'></i> para cancelar</li>";
echo "</ol>";

echo "<h4>🎨 Características:</h4>";
echo "<ul>";
echo "<li>✅ Descripciones separadas para galería y carrusel</li>";
echo "<li>✅ Guardado automático en archivos JSON</li>";
echo "<li>✅ Interfaz intuitiva con botones de edición</li>";
echo "<li>✅ Validación y mensajes de éxito/error</li>";
echo "<li>✅ Redirección automática después de guardar</li>";
echo "</ul>";
echo "</div>";

// Enlaces de prueba
echo "<h2>🔗 Enlaces de Prueba</h2>";
echo "<p><a href='" . URL_ROOT . "/admin/galeria' class='btn btn-primary' target='_blank'>🚀 Ir a Gestión de Galería</a></p>";
echo "<p><a href='" . URL_ROOT . "/admin' class='btn btn-secondary' target='_blank'>🏠 Ir al Dashboard</a></p>";

echo "<hr>";
echo "<p><em>Script de prueba completado. Verifica que la funcionalidad de edición de descripciones funcione correctamente.</em></p>";
?>
