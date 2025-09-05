<?php
session_start();

// Simular sesión de admin
$_SESSION['admin_logged_in'] = true;
$_SESSION['admin_username'] = 'admin';
$_SESSION['admin_role'] = 'admin';
$_SESSION['admin_login_time'] = time();

echo "=== TEST DASHBOARD SIMPLE ===\n";
echo "Session: " . print_r($_SESSION, true) . "\n";

// Cargar el controlador
require_once 'src/config/config.php';
require_once 'src/config/admin_credentials.php';
require_once 'src/controllers/AdminController.php';

try {
    echo "Verificando isAdminLoggedIn: " . (isAdminLoggedIn() ? 'TRUE' : 'FALSE') . "\n";
    
    $admin = new AdminController();
    echo "AdminController creado correctamente\n";
    
    // Llamar al dashboard
    echo "Llamando al dashboard...\n";
    $admin->dashboard();
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "=== FIN TEST ===\n";
