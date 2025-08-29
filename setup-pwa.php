<?php
/**
 * 🚀 Script de Configuración PWA para Filá Mariscales
 * Configura automáticamente todos los archivos necesarios para la PWA
 */

require_once 'vendor/autoload.php';

echo "📱 Configurando PWA para Filá Mariscales...\n\n";

try {
    // 1. Crear directorios necesarios
    echo "📁 Creando directorios...\n";
    $directories = [
        'public/assets/images/icons',
        'public/assets/images/screenshots',
        'apk-output'
    ];
    
    foreach ($directories as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
            echo "  ✅ Creado: {$dir}\n";
        } else {
            echo "  ℹ️  Ya existe: {$dir}\n";
        }
    }
    
    // 2. Verificar archivos PWA
    echo "\n📋 Verificando archivos PWA...\n";
    $pwaFiles = [
        'public/manifest.json' => 'Manifest de la PWA',
        'public/sw.js' => 'Service Worker',
        'public/offline.html' => 'Página offline',
        'public/assets/js/pwa.js' => 'JavaScript de PWA',
        'pwa-builder-config.json' => 'Configuración PWA Builder',
        'generate-apk.js' => 'Script de generación APK'
    ];
    
    foreach ($pwaFiles as $file => $description) {
        if (file_exists($file)) {
            echo "  ✅ {$description}: {$file}\n";
        } else {
            echo "  ❌ Faltante: {$description} ({$file})\n";
        }
    }
    
    // 3. Verificar iconos
    echo "\n🎨 Verificando iconos...\n";
    $requiredIcons = [
        'icon-72x72.png',
        'icon-96x96.png',
        'icon-128x128.png',
        'icon-144x144.png',
        'icon-152x152.png',
        'icon-192x192.png',
        'icon-384x384.png',
        'icon-512x512.png',
        'history-icon.png',
        'gallery-icon.png',
        'events-icon.png'
    ];
    
    $iconsDir = 'public/assets/images/icons/';
    $missingIcons = [];
    
    foreach ($requiredIcons as $icon) {
        $iconPath = $iconsDir . $icon;
        if (file_exists($iconPath)) {
            echo "  ✅ Icono: {$icon}\n";
        } else {
            echo "  ⚠️  Faltante: {$icon}\n";
            $missingIcons[] = $icon;
        }
    }
    
    // 4. Crear iconos de ejemplo si faltan
    if (!empty($missingIcons)) {
        echo "\n🎨 Creando iconos de ejemplo...\n";
        echo "  💡 Nota: Estos son iconos de ejemplo. Reemplázalos con los iconos reales de la Filá Mariscales.\n";
        
        foreach ($missingIcons as $icon) {
            $iconPath = $iconsDir . $icon;
            createSampleIcon($iconPath, $icon);
            echo "  ✅ Creado: {$icon}\n";
        }
    }
    
    // 5. Verificar screenshots
    echo "\n📸 Verificando screenshots...\n";
    $screenshots = [
        'home-mobile.png',
        'libro-mobile.png',
        'galeria-mobile.png'
    ];
    
    $screenshotsDir = 'public/assets/images/screenshots/';
    foreach ($screenshots as $screenshot) {
        $screenshotPath = $screenshotsDir . $screenshot;
        if (file_exists($screenshotPath)) {
            echo "  ✅ Screenshot: {$screenshot}\n";
        } else {
            echo "  ⚠️  Faltante: {$screenshot} (opcional)\n";
        }
    }
    
    // 6. Configurar .htaccess para PWA
    echo "\n🔧 Configurando .htaccess...\n";
    $htaccessPath = 'public/.htaccess';
    $htaccessContent = getHtaccessContent();
    
    if (file_exists($htaccessPath)) {
        $currentContent = file_get_contents($htaccessPath);
        if (strpos($currentContent, 'Service-Worker-Allowed') === false) {
            file_put_contents($htaccessPath, $htaccessContent . "\n" . $currentContent);
            echo "  ✅ Headers PWA agregados a .htaccess existente\n";
        } else {
            echo "  ℹ️  Headers PWA ya configurados\n";
        }
    } else {
        file_put_contents($htaccessPath, $htaccessContent);
        echo "  ✅ .htaccess creado con headers PWA\n";
    }
    
    // 7. Verificar configuración del servidor
    echo "\n🌐 Verificando configuración del servidor...\n";
    $serverInfo = [
        'HTTPS' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
        'HTTP/2' => isset($_SERVER['SERVER_PROTOCOL']) && strpos($_SERVER['SERVER_PROTOCOL'], 'HTTP/2') !== false,
        'Gzip' => extension_loaded('zlib'),
        'PHP Version' => PHP_VERSION
    ];
    
    foreach ($serverInfo as $feature => $enabled) {
        $status = $enabled ? '✅' : '⚠️';
        echo "  {$status} {$feature}: " . ($enabled ? 'Habilitado' : 'Deshabilitado') . "\n";
    }
    
    // 8. Generar reporte de configuración
    echo "\n📊 Generando reporte de configuración...\n";
    $report = generatePWAReport($pwaFiles, $requiredIcons, $serverInfo);
    file_put_contents('pwa-configuration-report.txt', $report);
    echo "  ✅ Reporte guardado: pwa-configuration-report.txt\n";
    
    // 9. Instrucciones finales
    echo "\n🎉 ¡Configuración PWA completada!\n";
    echo "================================\n\n";
    
    echo "📋 Próximos pasos:\n";
    echo "1. 🔄 Reemplaza los iconos de ejemplo con los iconos reales de la Filá Mariscales\n";
    echo "2. 📸 Toma screenshots de las páginas principales en móvil\n";
    echo "3. 🌐 Configura HTTPS en tu servidor (obligatorio para PWA)\n";
    echo "4. 📱 Prueba la instalación en dispositivos móviles\n";
    echo "5. 🚀 Genera el APK usando PWA Builder o el script automático\n\n";
    
    echo "🔗 Enlaces útiles:\n";
    echo "- PWA Builder: https://www.pwabuilder.com\n";
    echo "- Validación PWA: https://web.dev/measure/\n";
    echo "- Lighthouse: https://developers.google.com/web/tools/lighthouse\n\n";
    
    echo "📱 Para generar el APK:\n";
    echo "node generate-apk.js\n\n";
    
    echo "✅ ¡La Filá Mariscales está lista para ser una PWA!\n";
    
} catch (Exception $e) {
    echo "❌ Error durante la configuración: " . $e->getMessage() . "\n";
    exit(1);
}

/**
 * Crear icono de ejemplo
 */
function createSampleIcon($path, $filename) {
    // Crear un icono simple con el texto "FM" (Filá Mariscales)
    $size = 512;
    $image = imagecreatetruecolor($size, $size);
    
    // Colores
    $red = imagecolorallocate($image, 220, 20, 60); // #dc143c
    $white = imagecolorallocate($image, 255, 255, 255);
    $dark = imagecolorallocate($image, 139, 0, 0);
    
    // Fondo
    imagefill($image, 0, 0, $red);
    
    // Borde
    imagerectangle($image, 0, 0, $size-1, $size-1, $dark);
    
    // Texto "FM"
    $fontSize = $size / 4;
    $text = "FM";
    $fontFile = __DIR__ . '/assets/fonts/arial.ttf'; // Usar fuente del sistema si está disponible
    
    if (file_exists($fontFile)) {
        // Calcular posición centrada
        $bbox = imagettfbbox($fontSize, 0, $fontFile, $text);
        $x = ($size - ($bbox[4] - $bbox[0])) / 2;
        $y = ($size + ($bbox[1] - $bbox[5])) / 2;
        
        imagettftext($image, $fontSize, 0, $x, $y, $white, $fontFile, $text);
    } else {
        // Usar fuente básica
        $fontSize = 5;
        $textWidth = strlen($text) * imagefontwidth($fontSize);
        $textHeight = imagefontheight($fontSize);
        $x = ($size - $textWidth) / 2;
        $y = ($size - $textHeight) / 2;
        
        imagestring($image, $fontSize, $x, $y, $text, $white);
    }
    
    // Guardar imagen
    imagepng($image, $path);
    imagedestroy($image);
}

/**
 * Obtener contenido del .htaccess
 */
function getHtaccessContent() {
    return '# PWA Headers
<IfModule mod_headers.c>
    # Service Worker
    Header set Service-Worker-Allowed "/"
    
    # Security Headers
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "DENY"
    Header set X-XSS-Protection "1; mode=block"
    Header set Referrer-Policy "strict-origin-when-cross-origin"
    
    # PWA Headers
    Header set Cache-Control "public, max-age=31536000" "/manifest.json"
    Header set Cache-Control "public, max-age=31536000" "/sw.js"
</IfModule>

# MIME Types for PWA
<IfModule mod_mime.c>
    AddType application/manifest+json .json
    AddType application/x-web-app-manifest+json .webapp
</IfModule>

# Compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
    AddOutputFilterByType DEFLATE application/json
</IfModule>';
}

/**
 * Generar reporte de configuración
 */
function generatePWAReport($pwaFiles, $requiredIcons, $serverInfo) {
    $report = "📱 Reporte de Configuración PWA - Filá Mariscales\n";
    $report .= "================================================\n\n";
    $report .= "Fecha: " . date('Y-m-d H:i:s') . "\n\n";
    
    $report .= "📋 Archivos PWA:\n";
    foreach ($pwaFiles as $file => $description) {
        $status = file_exists($file) ? '✅' : '❌';
        $report .= "  {$status} {$description}: {$file}\n";
    }
    
    $report .= "\n🎨 Iconos:\n";
    $iconsDir = 'public/assets/images/icons/';
    foreach ($requiredIcons as $icon) {
        $iconPath = $iconsDir . $icon;
        $status = file_exists($iconPath) ? '✅' : '❌';
        $report .= "  {$status} {$icon}\n";
    }
    
    $report .= "\n🌐 Configuración del Servidor:\n";
    foreach ($serverInfo as $feature => $enabled) {
        $status = $enabled ? '✅' : '⚠️';
        $report .= "  {$status} {$feature}: " . ($enabled ? 'Habilitado' : 'Deshabilitado') . "\n";
    }
    
    $report .= "\n📊 Recomendaciones:\n";
    if (!$serverInfo['HTTPS']) {
        $report .= "- ⚠️  Configurar HTTPS (obligatorio para PWA)\n";
    }
    if (!$serverInfo['Gzip']) {
        $report .= "- ⚠️  Habilitar compresión Gzip para mejor performance\n";
    }
    
    $report .= "\n✅ Configuración completada exitosamente!\n";
    
    return $report;
}
?>

