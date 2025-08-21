<?php
// Script para probar el acceso directo a imágenes
echo "<h1>Prueba de Acceso Directo a Imágenes</h1>";

// Verificar si las imágenes existen físicamente
echo "<h2>Verificación de archivos físicos:</h2>";

$carouselDir = 'uploads/carousel/';
$galleryDir = 'uploads/gallery/';
$eventosDir = 'uploads/eventos/';

// Verificar carpeta carrusel
if (is_dir($carouselDir)) {
    echo "<p>✅ La carpeta carrusel existe: $carouselDir</p>";
    $files = glob($carouselDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
    if (!empty($files)) {
        echo "<p>📁 Archivos encontrados en carrusel:</p>";
        foreach ($files as $file) {
            if (is_file($file)) {
                $fileSize = filesize($file);
                $filePerms = substr(sprintf('%o', fileperms($file)), -4);
                echo "<div style='margin: 10px; padding: 10px; border: 1px solid #ccc;'>";
                echo "<strong>Archivo:</strong> " . basename($file) . "<br>";
                echo "<strong>Ruta completa:</strong> " . $file . "<br>";
                echo "<strong>Tamaño:</strong> " . number_format($fileSize / 1024, 2) . " KB<br>";
                echo "<strong>Permisos:</strong> " . $filePerms . "<br>";
                echo "<strong>URL directa:</strong> <a href='/" . $file . "' target='_blank'>/" . $file . "</a><br>";
                echo "<strong>URL con public:</strong> <a href='/prueba-php/public/" . $file . "' target='_blank'>/prueba-php/public/" . $file . "</a><br>";
                echo "<strong>URL relativa:</strong> <a href='./" . $file . "' target='_blank'>./" . $file . "</a><br>";
                echo "<img src='./" . $file . "' style='max-width: 200px; max-height: 150px; border: 1px solid #ccc;'><br><br>";
                echo "</div>";
            }
        }
    } else {
        echo "<p>📭 No hay archivos en la carpeta carrusel</p>";
    }
} else {
    echo "<p>❌ La carpeta carrusel NO existe</p>";
}

// Verificar carpeta galería
if (is_dir($galleryDir)) {
    echo "<p>✅ La carpeta galería existe: $galleryDir</p>";
    $files = glob($galleryDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
    if (!empty($files)) {
        echo "<p>📁 Archivos encontrados en galería:</p>";
        foreach ($files as $file) {
            if (is_file($file)) {
                $fileSize = filesize($file);
                $filePerms = substr(sprintf('%o', fileperms($file)), -4);
                echo "<div style='margin: 10px; padding: 10px; border: 1px solid #ccc;'>";
                echo "<strong>Archivo:</strong> " . basename($file) . "<br>";
                echo "<strong>Ruta completa:</strong> " . $file . "<br>";
                echo "<strong>Tamaño:</strong> " . number_format($fileSize / 1024, 2) . " KB<br>";
                echo "<strong>Permisos:</strong> " . $filePerms . "<br>";
                echo "<strong>URL directa:</strong> <a href='/" . $file . "' target='_blank'>/" . $file . "</a><br>";
                echo "<strong>URL con public:</strong> <a href='/prueba-php/public/" . $file . "' target='_blank'>/prueba-php/public/" . $file . "</a><br>";
                echo "<img src='./" . $file . "' style='max-width: 200px; max-height: 150px; border: 1px solid #ccc;'><br><br>";
                echo "</div>";
            }
        }
    } else {
        echo "<p>📭 No hay archivos en la carpeta galería</p>";
    }
} else {
    echo "<p>❌ La carpeta galería NO existe</p>";
}

// Verificar carpeta eventos
if (is_dir($eventosDir)) {
    echo "<p>✅ La carpeta eventos existe: $eventosDir</p>";
    $files = glob($eventosDir . '**/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
    if (!empty($files)) {
        echo "<p>📁 Archivos encontrados en eventos:</p>";
        foreach ($files as $file) {
            if (is_file($file)) {
                $fileSize = filesize($file);
                $filePerms = substr(sprintf('%o', fileperms($file)), -4);
                echo "<div style='margin: 10px; padding: 10px; border: 1px solid #ccc;'>";
                echo "<strong>Archivo:</strong> " . basename($file) . "<br>";
                echo "<strong>Ruta completa:</strong> " . $file . "<br>";
                echo "<strong>Tamaño:</strong> " . number_format($fileSize / 1024, 2) . " KB<br>";
                echo "<strong>Permisos:</strong> " . $filePerms . "<br>";
                echo "<strong>URL directa:</strong> <a href='/" . $file . "' target='_blank'>/" . $file . "</a><br>";
                echo "<strong>URL con public:</strong> <a href='/prueba-php/public/" . $file . "' target='_blank'>/prueba-php/public/" . $file . "</a><br>";
                echo "<img src='./" . $file . "' style='max-width: 200px; max-height: 150px; border: 1px solid #ccc;'><br><br>";
                echo "</div>";
            }
        }
    } else {
        echo "<p>📭 No hay archivos en la carpeta eventos</p>";
    }
} else {
    echo "<p>❌ La carpeta eventos NO existe</p>";
}

// Verificar configuración del servidor
echo "<h2>Configuración del servidor:</h2>";
echo "<p><strong>Document Root:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p><strong>Script Name:</strong> " . $_SERVER['SCRIPT_NAME'] . "</p>";
echo "<p><strong>Request URI:</strong> " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p><strong>Current Directory:</strong> " . getcwd() . "</p>";

// Verificar si .htaccess está siendo procesado
echo "<h2>Verificación de .htaccess:</h2>";
$htaccessFiles = [
    'uploads/.htaccess',
    'uploads/carousel/.htaccess',
    'uploads/gallery/.htaccess',
    'uploads/eventos/.htaccess'
];

foreach ($htaccessFiles as $htaccess) {
    if (file_exists($htaccess)) {
        $perms = substr(sprintf('%o', fileperms($htaccess)), -4);
        echo "<p>✅ $htaccess existe (permisos: $perms)</p>";
    } else {
        echo "<p>❌ $htaccess NO existe</p>";
    }
}

// Crear un script de prueba para servir imágenes
echo "<h2>Script de prueba para servir imágenes:</h2>";
echo "<p>Si el acceso directo no funciona, puedes usar este script para servir las imágenes:</p>";

$testImageScript = '<?php
// Script para servir imágenes
$imagePath = $_GET["path"] ?? "";
$allowedDirs = ["uploads/carousel/", "uploads/gallery/", "uploads/eventos/"];

if (!empty($imagePath)) {
    $isAllowed = false;
    foreach ($allowedDirs as $dir) {
        if (strpos($imagePath, $dir) === 0) {
            $isAllowed = true;
            break;
        }
    }
    
    if ($isAllowed && file_exists($imagePath)) {
        $extension = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
        $mimeTypes = [
            "jpg" => "image/jpeg",
            "jpeg" => "image/jpeg", 
            "png" => "image/png",
            "gif" => "image/gif"
        ];
        
        if (isset($mimeTypes[$extension])) {
            header("Content-Type: " . $mimeTypes[$extension]);
            header("Cache-Control: max-age=31536000, public");
            readfile($imagePath);
            exit;
        }
    }
}

// Si no se puede servir la imagen, mostrar error
header("HTTP/1.0 404 Not Found");
echo "Imagen no encontrada";
?>';

echo "<pre style='background: #f5f5f5; padding: 10px; border: 1px solid #ccc;'>";
echo htmlspecialchars($testImageScript);
echo "</pre>";

echo "<h2>Enlaces de prueba:</h2>";
echo "<p><a href='/prueba-php/public/'>Página Principal</a></p>";
echo "<p><a href='/prueba-php/public/pages/galeria'>Galería</a></p>";
echo "<p><a href='/prueba-php/public/pages/calendario'>Calendario</a></p>";
echo "<p><a href='/prueba-php/public/admin/galeria'>Administración - Galería</a></p>";

echo "<h2>Posibles soluciones:</h2>";
echo "<ol>";
echo "<li><strong>Verificar permisos:</strong> Asegúrate de que las carpetas tengan permisos 755 y los archivos 644</li>";
echo "<li><strong>Verificar .htaccess:</strong> Asegúrate de que el servidor esté procesando archivos .htaccess</li>";
echo "<li><strong>Usar rutas relativas:</strong> En lugar de URLs absolutas, usa rutas relativas desde la raíz del proyecto</li>";
echo "<li><strong>Crear script servidor:</strong> Usa el script de arriba para servir imágenes a través de PHP</li>";
echo "<li><strong>Verificar configuración del servidor:</strong> Asegúrate de que el Document Root esté configurado correctamente</li>";
echo "</ol>";
?>
