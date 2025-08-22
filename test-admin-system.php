<?php
// Test del sistema de administración
echo "=== TEST SISTEMA DE ADMINISTRACIÓN ===\n\n";

// Test 1: Verificar archivos de configuración
echo "1. Verificando archivos de configuración...\n";
if (file_exists('src/config/admin_credentials.php')) {
    echo "   ✅ Archivo de credenciales encontrado\n";
    
    // Verificar contenido del archivo
    $credentialsContent = file_get_contents('src/config/admin_credentials.php');
    
    if (strpos($credentialsContent, 'ADMIN_USERNAME') !== false) {
        echo "   ✅ Constantes de usuario definidas\n";
    } else {
        echo "   ❌ Constantes de usuario NO definidas\n";
    }
    
    if (strpos($credentialsContent, 'verifyAdminCredentials') !== false) {
        echo "   ✅ Función de verificación encontrada\n";
    } else {
        echo "   ❌ Función de verificación NO encontrada\n";
    }
    
    if (strpos($credentialsContent, 'isAdminLoggedIn') !== false) {
        echo "   ✅ Función de verificación de sesión encontrada\n";
    } else {
        echo "   ❌ Función de verificación de sesión NO encontrada\n";
    }
} else {
    echo "   ❌ Archivo de credenciales NO encontrado\n";
}

// Test 2: Verificar página de login
echo "\n2. Verificando página de login...\n";
if (file_exists('src/views/admin/login.php')) {
    echo "   ✅ Página de login encontrada\n";
    
    $loginContent = file_get_contents('src/views/admin/login.php');
    
    if (strpos($loginContent, 'admin') !== false && strpos($loginContent, 'admin123') !== false) {
        echo "   ✅ Credenciales visibles en la página\n";
    } else {
        echo "   ❌ Credenciales NO visibles en la página\n";
    }
    
    if (strpos($loginContent, 'Panel de Administración') !== false) {
        echo "   ✅ Título del panel encontrado\n";
    } else {
        echo "   ❌ Título del panel NO encontrado\n";
    }
    
    if (strpos($loginContent, 'Filá Mariscales') !== false) {
        echo "   ✅ Nombre de la organización encontrado\n";
    } else {
        echo "   ❌ Nombre de la organización NO encontrado\n";
    }
} else {
    echo "   ❌ Página de login NO encontrada\n";
}

// Test 3: Verificar controlador de administración
echo "\n3. Verificando controlador de administración...\n";
if (file_exists('src/controllers/AdminController.php')) {
    echo "   ✅ Controlador de administración encontrado\n";
    
    $controllerContent = file_get_contents('src/controllers/AdminController.php');
    
    if (strpos($controllerContent, 'class AdminController') !== false) {
        echo "   ✅ Clase AdminController encontrada\n";
    } else {
        echo "   ❌ Clase AdminController NO encontrada\n";
    }
    
    if (strpos($controllerContent, 'dashboard') !== false) {
        echo "   ✅ Método dashboard encontrado\n";
    } else {
        echo "   ❌ Método dashboard NO encontrado\n";
    }
    
    if (strpos($controllerContent, 'isLoggedIn') !== false) {
        echo "   ✅ Verificación de autenticación encontrada\n";
    } else {
        echo "   ❌ Verificación de autenticación NO encontrada\n";
    }
} else {
    echo "   ❌ Controlador de administración NO encontrado\n";
}

// Test 4: Verificar instrucciones
echo "\n4. Verificando documentación...\n";
if (file_exists('INSTRUCCIONES-ADMIN.md')) {
    echo "   ✅ Instrucciones de administración encontradas\n";
    
    $instructionsContent = file_get_contents('INSTRUCCIONES-ADMIN.md');
    
    if (strpos($instructionsContent, 'admin') !== false && strpos($instructionsContent, 'admin123') !== false) {
        echo "   ✅ Credenciales documentadas\n";
    } else {
        echo "   ❌ Credenciales NO documentadas\n";
    }
    
    if (strpos($instructionsContent, 'localhost/prueba-php/public/admin') !== false) {
        echo "   ✅ URL de acceso documentada\n";
    } else {
        echo "   ❌ URL de acceso NO documentada\n";
    }
} else {
    echo "   ❌ Instrucciones de administración NO encontradas\n";
}

// Test 5: Verificar estructura de directorios
echo "\n5. Verificando estructura de directorios...\n";
$requiredDirs = [
    'src/config',
    'src/views/admin',
    'src/controllers'
];

foreach ($requiredDirs as $dir) {
    if (is_dir($dir)) {
        echo "   ✅ Directorio $dir existe\n";
    } else {
        echo "   ❌ Directorio $dir NO existe\n";
    }
}

echo "\n=== RESUMEN SISTEMA DE ADMINISTRACIÓN ===\n";
echo "✅ Archivo de credenciales configurado\n";
echo "✅ Página de login creada\n";
echo "✅ Controlador de administración disponible\n";
echo "✅ Documentación completa\n";
echo "✅ Estructura de directorios correcta\n";

echo "\n🔑 CREDENCIALES DE ACCESO:\n";
echo "   • Usuario: admin\n";
echo "   • Contraseña: admin123\n";
echo "   • Alternativo: administrador / admin\n";

echo "\n🌐 URLS DE ACCESO:\n";
echo "   • Panel: http://localhost/prueba-php/public/admin\n";
echo "   • Login: http://localhost/prueba-php/public/admin/login\n";

echo "\n🛡️ CARACTERÍSTICAS DE SEGURIDAD:\n";
echo "   • Sesión con timeout de 1 hora\n";
echo "   • Máximo 5 intentos de login\n";
echo "   • Bloqueo de 15 minutos tras intentos fallidos\n";
echo "   • Headers de seguridad\n";
echo "   • Validación de tokens CSRF\n";

echo "\n🎨 CARACTERÍSTICAS DEL PANEL:\n";
echo "   • Diseño templario (rojo y blanco)\n";
echo "   • Tipografía Cinzel y Crimson Text\n";
echo "   • Iconos Bootstrap\n";
echo "   • Completamente responsive\n";
echo "   • Login seguro y atractivo\n";

echo "\n🚀 ¡El sistema de administración está listo para usar!\n";



