<?php
session_start();

echo "=== TEST DASHBOARD ===\n";

// Simular sesión de admin
$_SESSION['admin_logged_in'] = true;
$_SESSION['admin_username'] = 'admin';
$_SESSION['admin_role'] = 'admin';
$_SESSION['admin_login_time'] = time();

echo "Session creada: " . print_r($_SESSION, true) . "\n";

// Verificar si las funciones están disponibles
if (file_exists('src/config/admin_credentials.php')) {
    require_once 'src/config/admin_credentials.php';
    echo "isAdminLoggedIn(): " . (isAdminLoggedIn() ? 'TRUE' : 'FALSE') . "\n";
}

// Verificar si los modelos existen
if (file_exists('src/config/config.php')) {
    require_once 'src/config/config.php';
    require_once 'src/models/Database.php';
    
    if (file_exists('src/models/User.php')) {
        require_once 'src/models/User.php';
        echo "User model: EXISTE\n";
        
        try {
            $user = new User();
            $userCount = $user->getUserCount();
            echo "User count: " . $userCount . "\n";
        } catch (Exception $e) {
            echo "Error User model: " . $e->getMessage() . "\n";
        }
    } else {
        echo "User model: NO EXISTE\n";
    }
    
    if (file_exists('src/models/Event.php')) {
        require_once 'src/models/Event.php';
        echo "Event model: EXISTE\n";
        
        try {
            $event = new Event();
            $eventCount = $event->getEventCount();
            echo "Event count: " . $eventCount . "\n";
        } catch (Exception $e) {
            echo "Error Event model: " . $e->getMessage() . "\n";
        }
    } else {
        echo "Event model: NO EXISTE\n";
    }
} else {
    echo "Config: NO EXISTE\n";
}

echo "\n=== FIN TEST ===\n";
