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
require_once 'src/controllers/CartController.php';
require_once 'src/controllers/OrderController.php';

// Load AdminController early to ensure functions are available
if (file_exists('src/controllers/AdminController.php')) {
    require_once 'src/controllers/AdminController.php';
} elseif (file_exists('src/controllers/AdminController-new.php')) {
    require_once 'src/controllers/AdminController-new.php';
} elseif (file_exists('src/controllers/AdminController-minimal.php')) {
    require_once 'src/controllers/AdminController-minimal.php';
}

// Parse the URL
$url = isset($_GET['url']) ? explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL)) : [''];

// Create controller instances
$controller = new Pages();
$cartController = new CartController();
$orderController = new OrderController();

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
} elseif ($url[0] === 'socios') {
    $controller->socios();
} elseif ($url[0] === 'login') {
    $controller->login();
} elseif ($url[0] === 'registro') {
    $controller->registro();
} elseif ($url[0] === 'contacto') {
    $controller->contacto();
} elseif ($url[0] === 'interactiva') {
    $controller->interactiva();
} elseif ($url[0] === 'profile') {
    $controller->profile();
} elseif ($url[0] === 'update-profile') {
    $controller->updateProfile();
} elseif ($url[0] === 'change-password') {
    $controller->changePassword();
} elseif ($url[0] === 'upload-avatar') {
    $controller->uploadAvatar();
} elseif ($url[0] === 'cart') {
    // Cart routes
    $action = isset($url[1]) ? $url[1] : 'show';
    
    if ($action === 'add') {
        $cartController->addToCart();
    } elseif ($action === 'update') {
        $cartController->updateCart();
    } elseif ($action === 'remove') {
        $cartController->removeFromCart();
    } elseif ($action === 'clear') {
        $cartController->clearCart();
    } elseif ($action === 'info') {
        $cartController->getCartInfo();
    } else {
        $cartController->showCart();
    }
} elseif ($url[0] === 'order') {
    // Order routes
    $action = isset($url[1]) ? $url[1] : 'checkout';
    
    if ($action === 'checkout') {
        $orderController->checkout();
    } elseif ($action === 'process') {
        $orderController->processOrder();
    } elseif ($action === 'validate-coupon') {
        $orderController->validateCoupon();
    } elseif ($action === 'add-wishlist') {
        $orderController->addToWishlist();
    } elseif ($action === 'remove-wishlist') {
        $orderController->removeFromWishlist();
    } elseif ($action === 'clear-wishlist') {
        $orderController->clearWishlist();
    } elseif ($action === 'wishlist') {
        $orderController->getWishlist();
    } else {
        $orderController->checkout();
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

    // AdminController should already be loaded
    if (class_exists('AdminController')) {
        try {
            $adminController = new AdminController();
            
            if (method_exists($adminController, $action)) {
                call_user_func_array([$adminController, $action], array_slice($url, 2));
            } elseif ($action === 'mensajes' && isset($url[2]) && isset($url[3])) {
                // Manejar acciones de mensajes: /admin/mensajes/view/filename, /admin/mensajes/download/filename, etc.
                $subAction = $url[2];
                $filename = $url[3];
                
                if ($subAction === 'view') {
                    $adminController->viewMessage($filename);
                } elseif ($subAction === 'download') {
                    $adminController->downloadMessage($filename);
                } elseif ($subAction === 'delete') {
                    $adminController->deleteMessage($filename);
                } else {
                    $adminController->mensajes();
                }
            } elseif ($action === 'crearUsuario') {
                // Redirigir al formulario directo que funciona
                header('Location: /prueba-php/public/admin/crear-usuario.php');
                exit;
            } elseif ($action === 'nuevoEvento') {
                // Redirigir al formulario directo que funciona
                header('Location: /prueba-php/public/admin/nuevo-evento.php');
                exit;
            } elseif ($action === 'nuevo-producto') {
                // Manejar la ruta nuevo-producto
                $adminController->nuevoProducto();
            } elseif ($action === 'productos') {
                // Manejar la ruta productos
                $adminController->productos();
            } elseif ($action === 'editar-producto') {
                // Manejar la ruta editar-producto
                $id = isset($url[2]) ? $url[2] : null;
                $adminController->editarProducto($id);
            } elseif ($action === 'eliminar-producto') {
                // Manejar la ruta eliminar-producto
                $id = isset($url[2]) ? $url[2] : null;
                $adminController->eliminarProducto($id);
            } elseif ($action === 'upload-product-photo') {
                // Manejar la subida de fotos de productos
                $adminController->uploadProductPhoto();
            } else {
                $adminController->dashboard();
            }
        } catch (Exception $e) {
            echo "Error interno del servidor. Revisa los logs para más detalles.";
        }
    } else {
        echo "Error: No se puede cargar el controlador de administrador";
    }
} else {
    // Page not found
    $controller->notFound();
}
