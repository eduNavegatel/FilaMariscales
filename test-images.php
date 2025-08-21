<?php
// Script de prueba para verificar la carga de imágenes
echo "<h1>Prueba de Carga de Imágenes</h1>";

// Probar carga de imágenes del carrusel
echo "<h2>Imágenes del Carrusel:</h2>";
$carouselDir = 'uploads/carousel/';
if (is_dir($carouselDir)) {
    $files = glob($carouselDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
    if (!empty($files)) {
        foreach ($files as $file) {
            echo "<div style='margin: 10px;'>";
            echo "<strong>Archivo:</strong> " . basename($file) . "<br>";
            echo "<strong>Ruta completa:</strong> " . $file . "<br>";
            echo "<strong>URL:</strong> /" . $file . "<br>";
            echo "<img src='/" . $file . "' style='max-width: 200px; max-height: 150px; border: 1px solid #ccc;'><br><br>";
            echo "</div>";
        }
    } else {
        echo "<p>No se encontraron imágenes en el carrusel</p>";
    }
} else {
    echo "<p>El directorio del carrusel no existe</p>";
}

// Probar carga de imágenes de la galería
echo "<h2>Imágenes de la Galería:</h2>";
$galleryDir = 'uploads/gallery/';
if (is_dir($galleryDir)) {
    $files = glob($galleryDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
    if (!empty($files)) {
        foreach ($files as $file) {
            echo "<div style='margin: 10px;'>";
            echo "<strong>Archivo:</strong> " . basename($file) . "<br>";
            echo "<strong>Ruta completa:</strong> " . $file . "<br>";
            echo "<strong>URL:</strong> /" . $file . "<br>";
            echo "<img src='/" . $file . "' style='max-width: 200px; max-height: 150px; border: 1px solid #ccc;'><br><br>";
            echo "</div>";
        }
    } else {
        echo "<p>No se encontraron imágenes en la galería</p>";
    }
} else {
    echo "<p>El directorio de la galería no existe</p>";
}

// Probar los métodos del controlador
echo "<h2>Prueba de Métodos del Controlador:</h2>";

// Simular el método getCarouselImages
function testGetCarouselImages() {
    $uploadDir = 'uploads/carousel/';
    $images = [];
    
    if (is_dir($uploadDir)) {
        $files = glob($uploadDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
        foreach ($files as $file) {
            if (is_file($file)) {
                $fileInfo = pathinfo($file);
                $images[] = [
                    'path' => $file,
                    'name' => $fileInfo['basename'],
                    'url' => '/' . $file
                ];
            }
        }
    }
    
    return $images;
}

// Simular el método getGalleryImages
function testGetGalleryImages() {
    $uploadDir = 'uploads/gallery/';
    $images = [];
    
    if (is_dir($uploadDir)) {
        $files = glob($uploadDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
        foreach ($files as $file) {
            if (is_file($file)) {
                $fileInfo = pathinfo($file);
                $images[] = [
                    'thumb' => '/' . $file,
                    'full' => '/' . $file,
                    'caption' => 'Imagen de la Filá Mariscales',
                    'alt' => 'Galería Filá Mariscales',
                    'name' => $fileInfo['basename']
                ];
            }
        }
    }
    
    return $images;
}

$carouselImages = testGetCarouselImages();
$galleryImages = testGetGalleryImages();

echo "<h3>Resultado de getCarouselImages():</h3>";
echo "<pre>" . print_r($carouselImages, true) . "</pre>";

echo "<h3>Resultado de getGalleryImages():</h3>";
echo "<pre>" . print_r($galleryImages, true) . "</pre>";

echo "<h2>Enlaces de Prueba:</h2>";
echo "<p><a href='/prueba-php/public/'>Página Principal</a></p>";
echo "<p><a href='/prueba-php/public/pages/galeria'>Página de Galería</a></p>";
echo "<p><a href='/prueba-php/public/admin/galeria'>Administración - Galería</a></p>";
?>
