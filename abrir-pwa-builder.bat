@echo off
echo 📱 Abriendo PWA Builder para Filá Mariscales
echo ============================================
echo.

echo 🌐 Verificando que XAMPP esté corriendo...
ping -n 1 192.168.1.150 >nul
if errorlevel 1 (
    echo ❌ Error: No se puede conectar a 192.168.1.150
    echo 💡 Asegúrate de que XAMPP esté corriendo
    echo.
    echo 🔧 Pasos para solucionar:
    echo    1. Abre XAMPP Control Panel
    echo    2. Inicia Apache y MySQL
    echo    3. Prueba acceder a: http://192.168.1.150/prueba-php
    echo.
    pause
    exit /b 1
)

echo ✅ XAMPP está funcionando correctamente
echo.

echo 📋 Configuración para PWA Builder:
echo    • URL: http://192.168.1.150/prueba-php
echo    • Package ID: com.filamariscales.app
echo    • Nombre: Filá Mariscales
echo    • Versión: 1.0.0
echo.

echo 🚀 Abriendo PWA Builder...
start https://www.pwabuilder.com

echo.
echo 📱 INSTRUCCIONES PASO A PASO:
echo =============================
echo.
echo 1️⃣ **En PWA Builder:**
echo    • Pega esta URL: http://192.168.1.150/prueba-php
echo    • Haz clic en "Build My PWA"
echo.
echo 2️⃣ **Configurar Android:**
echo    • Selecciona "Android"
echo    • Package ID: com.filamariscales.app
echo    • Package Name: Filá Mariscales
echo    • Version: 1.0.0
echo    • Haz clic en "Generate Package"
echo.
echo 3️⃣ **Descargar APK:**
echo    • Espera a que se genere el APK
echo    • Descarga el archivo .apk
echo.
echo 4️⃣ **Instalar en tu móvil:**
echo    • Transfiere el APK a tu móvil
echo    • Activa "Fuentes desconocidas" en Configuración
echo    • Instala el APK
echo    • ¡Disfruta tu app nativa!
echo.

echo 💡 **Consejos:**
echo    • Si tienes problemas, prueba con: http://localhost/prueba-php
echo    • El APK funcionará mejor si tu móvil está en la misma red WiFi
echo    • Una vez instalada, la app funcionará offline
echo.

pause

