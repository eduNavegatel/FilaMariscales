<?php
session_start();

echo "=== DEBUG ADMIN PAGE ===\n";
echo "Session ID: " . session_id() . "\n";
echo "Session data: " . print_r($_SESSION, true) . "\n";

// Verificar si los archivos existen
echo "\n=== ARCHIVOS ===\n";
echo "AdminController: " . (file_exists('src/controllers/AdminController.php') ? 'EXISTE' : 'NO EXISTE') . "\n";
echo "Admin credentials: " . (file_exists('src/config/admin_credentials.php') ? 'EXISTE' : 'NO EXISTE') . "\n";

// Verificar si las funciones están disponibles
if (file_exists('src/config/admin_credentials.php')) {
    require_once 'src/config/admin_credentials.php';
    echo "isAdminLoggedIn(): " . (function_exists('isAdminLoggedIn') ? 'EXISTE' : 'NO EXISTE') . "\n";
    if (function_exists('isAdminLoggedIn')) {
        echo "Resultado isAdminLoggedIn(): " . (isAdminLoggedIn() ? 'TRUE' : 'FALSE') . "\n";
    }
}

echo "\n=== FIN DEBUG ===\n";

