<?php
// Script de prueba para verificar el sistema de mensajes
echo "<h2>🧪 Prueba del Sistema de Mensajes</h2>";

// Verificar que el directorio de mensajes existe
$messagesDir = 'uploads/messages/';
if (!is_dir($messagesDir)) {
    mkdir($messagesDir, 0755, true);
    echo "<p>✅ Directorio de mensajes creado: $messagesDir</p>";
} else {
    echo "<p>✅ Directorio de mensajes existe: $messagesDir</p>";
}

// Listar archivos de mensajes
$messageFiles = glob($messagesDir . '*.{txt,json,html}', GLOB_BRACE);
echo "<h3>📁 Archivos de Mensajes Encontrados:</h3>";

if (empty($messageFiles)) {
    echo "<p>❌ No se encontraron archivos de mensajes</p>";
} else {
    echo "<ul>";
    foreach ($messageFiles as $file) {
        $filename = basename($file);
        $size = filesize($file);
        $modified = date('Y-m-d H:i:s', filemtime($file));
        echo "<li><strong>$filename</strong> - Tamaño: $size bytes - Modificado: $modified</li>";
    }
    echo "</ul>";
}

// Probar acceso a las rutas
echo "<h3>🔗 Prueba de Rutas:</h3>";
echo "<ul>";
echo "<li><a href='/prueba-php/public/admin/mensajes' target='_blank'>Panel de Mensajes</a></li>";
echo "<li><a href='/prueba-php/public/admin' target='_blank'>Panel de Administración</a></li>";
echo "</ul>";

// Verificar permisos
echo "<h3>🔐 Verificación de Permisos:</h3>";
if (is_writable($messagesDir)) {
    echo "<p>✅ Directorio de mensajes es escribible</p>";
} else {
    echo "<p>❌ Directorio de mensajes NO es escribible</p>";
}

// Mostrar contenido de un archivo de ejemplo
if (!empty($messageFiles)) {
    $firstFile = $messageFiles[0];
    echo "<h3>📄 Contenido del primer archivo:</h3>";
    echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px;'>";
    echo "<pre>" . htmlspecialchars(file_get_contents($firstFile)) . "</pre>";
    echo "</div>";
}

echo "<h3>🎯 Instrucciones:</h3>";
echo "<ol>";
echo "<li>Ve al <a href='/prueba-php/public/admin' target='_blank'>Panel de Administración</a></li>";
echo "<li>Haz clic en 'Ver Mensajes'</li>";
echo "<li>Haz clic en el botón 'Ver' (👁️) de cualquier mensaje</li>";
echo "<li>Debería abrirse un modal con el contenido del mensaje</li>";
echo "</ol>";
?>
