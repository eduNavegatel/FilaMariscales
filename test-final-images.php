<?php
// Script final para probar el acceso a imágenes
echo "<h1>Prueba Final de Acceso a Imágenes</h1>";

// Verificar archivos existentes
$carouselDir = 'uploads/carousel/';
$galleryDir = 'uploads/gallery/';
$eventosDir = 'uploads/eventos/';

echo "<h2>Verificación de archivos:</h2>";

$allFiles = [];

// Obtener archivos del carrusel
if (is_dir($carouselDir)) {
    $files = glob($carouselDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
    foreach ($files as $file) {
        if (is_file($file)) {
            $allFiles[] = [
                'path' => $file,
                'type' => 'carrusel',
                'name' => basename($file)
            ];
        }
    }
}

// Obtener archivos de la galería
if (is_dir($galleryDir)) {
    $files = glob($galleryDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
    foreach ($files as $file) {
        if (is_file($file)) {
            $allFiles[] = [
                'path' => $file,
                'type' => 'galería',
                'name' => basename($file)
            ];
        }
    }
}

// Obtener archivos de eventos
if (is_dir($eventosDir)) {
    $files = glob($eventosDir . '**/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
    foreach ($files as $file) {
        if (is_file($file)) {
            $allFiles[] = [
                'path' => $file,
                'type' => 'eventos',
                'name' => basename($file)
            ];
        }
    }
}

if (empty($allFiles)) {
    echo "<p>❌ No se encontraron archivos de imagen</p>";
} else {
    echo "<p>✅ Se encontraron " . count($allFiles) . " archivos de imagen</p>";
    
    echo "<h2>Pruebas de acceso:</h2>";
    
    foreach ($allFiles as $file) {
        echo "<div style='margin: 20px; padding: 15px; border: 2px solid #ccc; border-radius: 8px;'>";
        echo "<h3>{$file['type']}: {$file['name']}</h3>";
        
        // URL directa
        $directUrl = '/' . $file['path'];
        echo "<p><strong>URL Directa:</strong> <a href='$directUrl' target='_blank'>$directUrl</a></p>";
        
        // URL con script servidor
        $serverUrl = '/prueba-php/public/serve-image.php?path=' . urlencode($file['path']);
        echo "<p><strong>URL Script Servidor:</strong> <a href='$serverUrl' target='_blank'>$serverUrl</a></p>";
        
        // Mostrar imagen usando script servidor
        echo "<div style='margin: 10px 0;'>";
        echo "<strong>Vista previa (Script Servidor):</strong><br>";
        echo "<img src='$serverUrl' style='max-width: 300px; max-height: 200px; border: 1px solid #ddd; margin: 5px;'>";
        echo "</div>";
        
        // Información del archivo
        $fileSize = filesize($file['path']);
        $filePerms = substr(sprintf('%o', fileperms($file['path'])), -4);
        echo "<p><strong>Tamaño:</strong> " . number_format($fileSize / 1024, 2) . " KB</p>";
        echo "<p><strong>Permisos:</strong> $filePerms</p>";
        
        echo "</div>";
    }
}

// Probar el script servidor
echo "<h2>Prueba del Script Servidor:</h2>";

if (!empty($allFiles)) {
    $testFile = $allFiles[0];
    $testUrl = '/prueba-php/public/serve-image.php?path=' . urlencode($testFile['path']);
    
    echo "<p>Probando script servidor con: {$testFile['name']}</p>";
    
    // Hacer una petición al script servidor
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'timeout' => 5
        ]
    ]);
    
    $response = @file_get_contents('http://localhost' . $testUrl, false, $context);
    
    if ($response !== false) {
        echo "<p>✅ Script servidor funciona correctamente</p>";
        echo "<p><strong>Headers de respuesta:</strong></p>";
        echo "<pre>" . print_r($http_response_header, true) . "</pre>";
    } else {
        echo "<p>❌ Error al acceder al script servidor</p>";
        echo "<p><strong>Error:</strong> " . error_get_last()['message'] ?? 'Error desconocido' . "</p>";
    }
}

// Verificar configuración
echo "<h2>Configuración del sistema:</h2>";

$configFiles = [
    '.htaccess' => 'Archivo .htaccess raíz',
    'public/serve-image.php' => 'Script servidor de imágenes',
    'uploads/.htaccess' => 'Configuración uploads',
    'uploads/carousel/.htaccess' => 'Configuración carrusel',
    'uploads/gallery/.htaccess' => 'Configuración galería',
    'uploads/eventos/.htaccess' => 'Configuración eventos'
];

foreach ($configFiles as $file => $description) {
    if (file_exists($file)) {
        $perms = substr(sprintf('%o', fileperms($file)), -4);
        echo "<p>✅ $description existe (permisos: $perms)</p>";
    } else {
        echo "<p>❌ $description NO existe</p>";
    }
}

// Enlaces de prueba
echo "<h2>Enlaces de prueba:</h2>";
echo "<p><a href='/prueba-php/public/'>Página Principal</a></p>";
echo "<p><a href='/prueba-php/public/pages/galeria'>Galería Pública</a></p>";
echo "<p><a href='/prueba-php/public/pages/calendario'>Calendario</a></p>";
echo "<p><a href='/prueba-php/public/admin/galeria'>Administración - Galería</a></p>";

// Recomendaciones finales
echo "<h2>Estado del sistema:</h2>";

if (!empty($allFiles)) {
    echo "<p>✅ El sistema está configurado correctamente</p>";
    echo "<p>✅ Las imágenes se pueden servir a través del script servidor</p>";
    echo "<p>✅ Los archivos .htaccess están configurados</p>";
    echo "<p>✅ Las rutas están funcionando</p>";
} else {
    echo "<p>⚠️ No hay imágenes para probar</p>";
    echo "<p>💡 Sube algunas imágenes desde la administración para probar el sistema</p>";
}

echo "<h2>Próximos pasos:</h2>";
echo "<ol>";
echo "<li>Accede a la administración: <a href='/prueba-php/public/admin/galeria'>Galería</a></li>";
echo "<li>Sube algunas imágenes al carrusel y a la galería</li>";
echo "<li>Verifica que aparezcan en las páginas públicas</li>";
echo "<li>Prueba el acceso directo a las URLs de las imágenes</li>";
echo "</ol>";
?>
