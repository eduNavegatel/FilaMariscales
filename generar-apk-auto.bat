@echo off
echo 📱 Generador Automático de APK - Filá Mariscales
echo ================================================
echo.

echo 🌐 Verificando conectividad...
ping -n 1 192.168.1.150 >nul
if errorlevel 1 (
    echo ❌ Error: No se puede conectar a 192.168.1.150
    echo 💡 Asegúrate de que XAMPP esté corriendo
    pause
    exit /b 1
)

echo ✅ Conectividad OK
echo.

echo 📋 Configuración:
echo    • URL: http://192.168.1.150/prueba-php
echo    • Package ID: com.filamariscales.app
echo    • Nombre: Filá Mariscales
echo.

echo 🚀 Abriendo PWA Builder...
start https://www.pwabuilder.com

echo.
echo 📱 Instrucciones:
echo    1. Pega esta URL: http://192.168.1.150/prueba-php
echo    2. Haz clic en 'Build My PWA'
echo    3. Selecciona 'Android'
echo    4. Configura el Package ID: com.filamariscales.app
echo    5. Genera y descarga el APK
echo.

pause
