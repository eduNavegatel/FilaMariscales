<?php
/**
 * Generador Automático de APK - Versión Simplificada
 * Filá Mariscales de Caballeros Templarios
 */

echo "📱 Generador Automático de APK - Versión Simplificada\n";
echo "====================================================\n\n";

// Configuración
$siteUrl = "http://localhost/prueba-php/public/index-simple.php";
$packageId = "com.filamariscales.app";
$appName = "Filá Mariscales";
$version = "1.0.0";

echo "🌐 Configuración:\n";
echo "   • URL: $siteUrl\n";
echo "   • Package ID: $packageId\n";
echo "   • Nombre: $appName\n";
echo "   • Versión: $version\n\n";

// Verificar que XAMPP esté corriendo
echo "🔍 Verificando XAMPP...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $siteUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    echo "✅ XAMPP está funcionando correctamente\n\n";
} else {
    echo "❌ Error: XAMPP no responde correctamente (HTTP $httpCode)\n";
    echo "💡 Asegúrate de que Apache esté corriendo en XAMPP\n\n";
    exit(1);
}

// Generar URL para PWA Builder
$pwaBuilderUrl = "https://www.pwabuilder.com?url=" . urlencode($siteUrl);

echo "🚀 Generando APK con PWA Builder...\n\n";

echo "📋 Pasos a seguir:\n";
echo "1. Se abrirá PWA Builder en tu navegador\n";
echo "2. La URL ya estará pre-llenada\n";
echo "3. Haz clic en 'Build My PWA'\n";
echo "4. Selecciona 'Android'\n";
echo "5. Configura:\n";
echo "   • Package ID: $packageId\n";
echo "   • Package Name: $appName\n";
echo "   • Version: $version\n";
echo "6. Haz clic en 'Generate Package'\n";
echo "7. Descarga el APK\n\n";

echo "🔗 Abriendo PWA Builder...\n";

// Abrir PWA Builder en el navegador
if (PHP_OS_FAMILY === 'Windows') {
    exec("start \"$pwaBuilderUrl\"");
} else {
    exec("xdg-open \"$pwaBuilderUrl\"");
}

echo "✅ PWA Builder abierto en tu navegador\n\n";

echo "📱 Instrucciones para instalar el APK:\n";
echo "1. Transfiere el APK a tu móvil Android\n";
echo "2. Ve a Configuración → Seguridad\n";
echo "3. Activa 'Fuentes desconocidas'\n";
echo "4. Abre el APK y sigue las instrucciones\n";
echo "5. ¡Listo! La app aparecerá en tu pantalla de inicio\n\n";

echo "🎉 ¡Tu APK estará lista en unos minutos!\n";
echo "La app incluirá:\n";
echo "• Flip Book espectacular con animaciones 3D\n";
echo "• Todas las páginas de la Filá\n";
echo "• Funcionamiento offline\n";
echo "• Experiencia nativa como app real\n\n";

echo "📞 Si necesitas ayuda, revisa la documentación en:\n";
echo "• GUIA-APK-DIRECTA.md\n";
echo "• PWA-APK-IMPLEMENTACION.md\n";
?>
