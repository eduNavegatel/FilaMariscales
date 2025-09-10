<?php
session_start();

// Simular sesión de admin
$_SESSION['admin_logged_in'] = true;
$_SESSION['admin_username'] = 'admin';
$_SESSION['admin_role'] = 'admin';
$_SESSION['admin_login_time'] = time();

echo "=== TEST ADMIN VIEW ===\n";
echo "Session: " . print_r($_SESSION, true) . "\n";

// Verificar si los archivos existen
if (file_exists('src/config/config.php')) {
    require_once 'src/config/config.php';
    echo "Config cargado\n";
    
    if (file_exists('src/models/Database.php')) {
        require_once 'src/models/Database.php';
        echo "Database cargado\n";
        
        if (file_exists('src/models/User.php')) {
            require_once 'src/models/User.php';
            echo "User model cargado\n";
            
            if (file_exists('src/models/Event.php')) {
                require_once 'src/models/Event.php';
                echo "Event model cargado\n";
                
                // Simular el método loadAdminView
                echo "\n=== SIMULANDO LOADADMINVIEW ===\n";
                
                // Preparar datos
                $data = [
                    'title' => 'Panel de Administración',
                    'userCount' => 12,
                    'eventCount' => 0,
                    'galleryCount' => 0,
                    'recentUsers' => [],
                    'recentEvents' => []
                ];
                
                echo "Data preparada: " . print_r($data, true) . "\n";
                
                // Extraer datos
                extract($data);
                echo "Variables extraídas:\n";
                echo "  - title: " . $title . "\n";
                echo "  - userCount: " . $userCount . "\n";
                echo "  - eventCount: " . $eventCount . "\n";
                
                // Verificar si la vista existe
                $viewFile = 'src/views/admin/dashboard.php';
                if (file_exists($viewFile)) {
                    echo "Vista encontrada: " . $viewFile . "\n";
                    
                    // Verificar si el layout existe
                    $layoutFile = 'src/views/layouts/admin.php';
                    if (file_exists($layoutFile)) {
                        echo "Layout encontrado: " . $layoutFile . "\n";
                        echo "✅ Todos los archivos están en su lugar\n";
                    } else {
                        echo "❌ Layout NO encontrado: " . $layoutFile . "\n";
                    }
                } else {
                    echo "❌ Vista NO encontrada: " . $viewFile . "\n";
                }
                
            } else {
                echo "Event model NO encontrado\n";
            }
        } else {
            echo "User model NO encontrado\n";
        }
    } else {
        echo "Database NO encontrado\n";
    }
} else {
    echo "Config NO encontrado\n";
}

echo "\n=== FIN TEST ===\n";








