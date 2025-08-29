<?php
/**
 * 🎨 Script simple para crear iconos básicos
 */

echo "🎨 Creando iconos básicos para la PWA...\n\n";

$sizes = [72, 96, 128, 144, 152, 192, 384, 512];
$iconsDir = 'public/assets/images/icons/';

// Crear directorio si no existe
if (!is_dir($iconsDir)) {
    mkdir($iconsDir, 0755, true);
}

foreach ($sizes as $size) {
    $filename = "icon-{$size}x{$size}.png";
    $filepath = $iconsDir . $filename;
    
    // Crear un archivo SVG simple como PNG
    $svg = createSimpleIcon($size);
    file_put_contents($filepath, $svg);
    
    echo "  ✅ Creado: {$filename}\n";
}

// Crear iconos adicionales
$additionalIcons = ['history-icon.png', 'gallery-icon.png', 'events-icon.png'];
foreach ($additionalIcons as $icon) {
    $filepath = $iconsDir . $icon;
    $svg = createSimpleIcon(96);
    file_put_contents($filepath, $svg);
    echo "  ✅ Creado: {$icon}\n";
}

echo "\n🎉 ¡Iconos creados exitosamente!\n";
echo "💡 Estos son iconos básicos. Reemplázalos con los iconos reales de la Filá Mariscales.\n";

function createSimpleIcon($size) {
    $svg = '<?xml version="1.0" encoding="UTF-8"?>
<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 ' . $size . ' ' . $size . '" xmlns="http://www.w3.org/2000/svg">
    <rect width="' . $size . '" height="' . $size . '" fill="#dc143c"/>
    <rect x="2" y="2" width="' . ($size-4) . '" height="' . ($size-4) . '" fill="#dc143c" stroke="#8b0000" stroke-width="2"/>
    <text x="' . ($size/2) . '" y="' . ($size/2 + $size/8) . '" font-family="Arial, sans-serif" font-size="' . ($size/3) . '" font-weight="bold" text-anchor="middle" fill="white">FM</text>
</svg>';
    
    return $svg;
}
?>

