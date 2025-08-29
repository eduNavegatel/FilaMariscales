<?php
// Set the current directory
chdir(dirname(__DIR__));

// Load configuration
require_once 'src/config/config.php';
require_once 'src/config/helpers.php';
require_once 'src/config/admin_credentials.php';

// Load controllers
require_once 'src/controllers/Controller.php';
require_once 'src/controllers/Pages.php';
require_once 'src/controllers/SociosController.php';

// Parse the URL
$url = isset($_GET['url']) ? explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL)) : [''];

// Create controller instance
$controller = new Pages();

// Route the request
if (empty($url[0])) {
    // Default to home page
    $controller->index();
} elseif ($url[0] === 'historia') {
    $controller->historia();
} elseif ($url[0] === 'directiva') {
    $controller->directiva();
} elseif ($url[0] === 'noticias') {
    $controller->noticias();
} elseif ($url[0] === 'blog') {
    $controller->blog();
} elseif ($url[0] === 'calendario') {
    $controller->calendario();
} elseif ($url[0] === 'galeria') {
    $controller->galeria();
} elseif ($url[0] === 'musica') {
    $controller->musica();
} elseif ($url[0] === 'libro') {
    $controller->libro();
} elseif ($url[0] === 'descargas') {
    $controller->descargas();
} elseif ($url[0] === 'tienda') {
    $controller->tienda();
} elseif ($url[0] === 'patrocinadores') {
    $controller->patrocinadores();
} elseif ($url[0] === 'hermanamientos') {
    $controller->hermanamientos();
} elseif ($url[0] === 'login') {
    $controller->login();
} elseif ($url[0] === 'registro') {
    $controller->registro();
} elseif ($url[0] === 'socios') {
    // Handle socios routes
    $sociosController = new SociosController();
    
    if (isset($url[1])) {
        $action = $url[1];
        if (method_exists($sociosController, $action)) {
            $sociosController->$action();
        } else {
            $sociosController->index();
        }
    } else {
        $sociosController->index();
    }
} elseif ($url[0] === 'admin') {
    // Admin routes (simple guard + custom login/logout)
    $action = isset($url[1]) ? $url[1] : (isAdminLoggedIn() ? 'dashboard' : 'login');

    if ($action === 'login') {
        // Serve admin login page without constructing the controller
        if (file_exists('src/views/admin/login.php')) {
            require 'src/views/admin/login.php';
        } else {
            echo "Error: No se encuentra la vista de login de administrador";
        }
        return;
    }

    if ($action === 'logout') {
        logoutAdmin();
        header('Location: /prueba-php/public/admin/login');
        exit;
    }

    // Any other admin route requires authentication
    if (!isAdminLoggedIn()) {
        header('Location: /prueba-php/public/admin/login');
        exit;
    }

    // Check if AdminController exists before requiring it
    if (file_exists('src/controllers/AdminController.php')) {
        require_once 'src/controllers/AdminController.php';
        $adminController = new AdminController();

        if (method_exists($adminController, $action)) {
            // Handle dynamic routes with parameters
            if (($action === 'editarUsuario' || $action === 'obtenerPassword') && isset($url[2])) {
                $userId = $url[2];
                call_user_func_array([$adminController, $action], [$userId]);
            } else {
                call_user_func_array([$adminController, $action], array_slice($url, 2));
            }
        } else {
            $adminController->dashboard();
        }
    } else {
        echo "Error: No se encuentra el controlador de administrador";
    }
} else {
    // Page not found
    $controller->notFound();
}
?>
