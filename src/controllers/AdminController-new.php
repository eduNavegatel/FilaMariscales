<?php
// AdminController NUEVO y FUNCIONAL
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cargar solo lo esencial
$adminCredPath = __DIR__ . '/../config/admin_credentials.php';
if (file_exists($adminCredPath)) {
    require_once $adminCredPath;
    error_log("AdminController-new: admin_credentials.php cargado");
}

// Clase base simple
class ControllerBase {
    protected function redirect($url) {
        header('Location: ' . $url);
        exit;
    }
    
    protected function view($view, $data = []) {
        // Extraer datos
        extract($data);
        
        // Incluir vista
        $viewFile = dirname(dirname(__DIR__)) . '/src/views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            echo "Error: Vista no encontrada: " . $viewFile;
        }
    }
}

// Clase principal del AdminController
class AdminController extends ControllerBase {
    
    private $userModel;
    private $eventModel;
    
    public function __construct() {
        error_log("AdminController-new::__construct() iniciando");
        
        // Verificación de sesión
        if (function_exists('isAdminLoggedIn')) {
            if (!isAdminLoggedIn()) {
                error_log("AdminController-new: Usuario NO autenticado");
                header('Location: /prueba-php/public/admin/login');
                exit;
            }
            error_log("AdminController-new: Usuario autenticado correctamente");
        } else {
            error_log("AdminController-new: ⚠️ isAdminLoggedIn no disponible");
        }
        
        // Inicializar modelos de forma segura
        try {
            if (file_exists(dirname(dirname(__DIR__)) . '/src/models/User.php')) {
                require_once dirname(dirname(__DIR__)) . '/src/models/User.php';
                if (class_exists('User')) {
                    $this->userModel = new User();
                    error_log("AdminController-new: User model inicializado");
                }
            }
        } catch (Exception $e) {
            error_log("AdminController-new: Error inicializando User model: " . $e->getMessage());
            $this->userModel = null;
        }
        
        try {
            if (file_exists(dirname(dirname(__DIR__)) . '/src/models/Event.php')) {
                require_once dirname(dirname(__DIR__)) . '/src/models/Event.php';
                if (class_exists('Event')) {
                    $this->eventModel = new Event();
                    error_log("AdminController-new: Event model inicializado");
                }
            }
        } catch (Exception $e) {
            error_log("AdminController-new: Error inicializando Event model: " . $e->getMessage());
            $this->eventModel = null;
        }
        
        error_log("AdminController-new::__construct() completado");
    }
    
    public function index() {
        $this->redirect('/prueba-php/public/admin/dashboard');
    }
    
    public function dashboard() {
        error_log("AdminController-new::dashboard() llamado");
        
        // Obtener datos para el dashboard
        $userCount = 0;
        $eventCount = 0;
        $recentUsers = [];
        $recentEvents = [];
        
        if ($this->userModel) {
            try {
                $userCount = $this->userModel->getUserCount();
                $recentUsers = $this->userModel->getRecentUsers(5);
            } catch (Exception $e) {
                error_log("Error obteniendo datos de usuarios: " . $e->getMessage());
            }
        }
        
        if ($this->eventModel) {
            try {
                $eventCount = $this->eventModel->getEventCount();
                $recentEvents = $this->eventModel->getRecentEvents(5);
            } catch (Exception $e) {
                error_log("Error obteniendo datos de eventos: " . $e->getMessage());
            }
        }
        
        // Contar archivos de galería
        $galleryCount = 0;
        $uploadDir = 'uploads/gallery/';
        if (is_dir($uploadDir)) {
            $files = glob($uploadDir . '*');
            $galleryCount = count($files);
        }
        
        $data = [
            'title' => 'Panel de Administración',
            'userCount' => $userCount,
            'eventCount' => $eventCount,
            'galleryCount' => $galleryCount,
            'recentUsers' => $recentUsers,
            'recentEvents' => $recentEvents
        ];
        
        $this->view('admin/dashboard', $data);
    }
    
    public function usuarios($page = 1) {
        error_log("AdminController-new::usuarios() llamado");
        
        $perPage = 10;
        $users = [];
        $totalUsers = 0;
        
        if ($this->userModel) {
            try {
                $users = $this->userModel->getAllUsers($page, $perPage);
                $totalUsers = $this->userModel->countAllUsers();
            } catch (Exception $e) {
                error_log("Error obteniendo usuarios: " . $e->getMessage());
            }
        }
        
        $data = [
            'title' => 'Gestión de Usuarios',
            'users' => $users,
            'currentPage' => $page,
            'totalPages' => ceil($totalUsers / $perPage)
        ];
        
        $this->view('admin/users/index', $data);
    }
    
    public function editarUsuarioForm($id = null) {
        error_log("AdminController-new::editarUsuarioForm() llamado con ID: " . $id);
        
        if (!$id || !is_numeric($id)) {
            error_log("ID inválido: " . $id);
            $this->redirect('/prueba-php/public/admin/usuarios');
        }
        
        $user = null;
        if ($this->userModel) {
            try {
                $user = $this->userModel->getUserById($id);
            } catch (Exception $e) {
                error_log("Error obteniendo usuario: " . $e->getMessage());
            }
        }
        
        if (!$user) {
            error_log("Usuario no encontrado para ID: " . $id);
            $this->redirect('/prueba-php/public/admin/usuarios');
        }
        
        // Obtener todos los usuarios para la vista
        $users = [];
        $totalUsers = 0;
        if ($this->userModel) {
            try {
                $users = $this->userModel->getAllUsers(1, 10);
                $totalUsers = $this->userModel->countAllUsers();
            } catch (Exception $e) {
                error_log("Error obteniendo lista de usuarios: " . $e->getMessage());
            }
        }
        
        $data = [
            'title' => 'Editar Usuario',
            'user' => $user,
            'users' => $users,
            'currentPage' => 1,
            'totalPages' => ceil($totalUsers / 10),
            'errors' => []
        ];
        
        $this->view('admin/users/index', $data);
    }
    
    public function editarUsuario($id = null) {
        error_log("AdminController-new::editarUsuario() llamado con ID: " . $id);
        error_log("Request method: " . $_SERVER['REQUEST_METHOD']);
        error_log("POST data: " . print_r($_POST, true));
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Procesar datos del formulario
            $userData = [
                'id' => $id ?: ($_POST['user_id'] ?? null),
                'nombre' => trim(htmlspecialchars($_POST['nombre'] ?? '')),
                'apellidos' => trim(htmlspecialchars($_POST['apellidos'] ?? '')),
                'email' => filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL),
                'rol' => trim(htmlspecialchars($_POST['rol'] ?? 'user')),
                'activo' => isset($_POST['activo']) && $_POST['activo'] == '1' ? 1 : 0,
                'errors' => []
            ];
            
            // Validar datos
            if (empty($userData['nombre'])) $userData['errors']['nombre'] = 'Nombre requerido';
            if (empty($userData['email'])) $userData['errors']['email'] = 'Email requerido';
            if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
                $userData['errors']['email'] = 'Email inválido';
            }
            
            $validRoles = ['user', 'socio', 'admin'];
            if (!in_array($userData['rol'], $validRoles)) {
                $userData['errors']['rol'] = 'Rol inválido';
            }
            
            if (empty($userData['errors']) && $this->userModel) {
                try {
                    $result = $this->userModel->updateUser($userData);
                    if ($result) {
                        error_log("Usuario actualizado correctamente");
                        $this->redirect('/prueba-php/public/admin/usuarios?updated=1');
                    } else {
                        error_log("Error al actualizar usuario");
                        $this->redirect('/prueba-php/public/admin/usuarios?error=1');
                    }
                } catch (Exception $e) {
                    error_log("Excepción al actualizar usuario: " . $e->getMessage());
                    $this->redirect('/prueba-php/public/admin/usuarios?error=1');
                }
            } else {
                error_log("Errores de validación: " . print_r($userData['errors'], true));
                $this->redirect('/prueba-php/public/admin/usuarios?error=validation');
            }
        } else {
            $this->redirect('/prueba-php/public/admin/usuarios');
        }
    }
    
    public function eliminarUsuario($id) {
        error_log("AdminController-new::eliminarUsuario() llamado con ID: " . $id);
        
        if ($this->userModel) {
            try {
                $result = $this->userModel->deleteUser($id);
                if ($result) {
                    error_log("Usuario eliminado correctamente");
                    $this->redirect('/prueba-php/public/admin/usuarios?deleted=1');
                } else {
                    error_log("Error al eliminar usuario");
                    $this->redirect('/prueba-php/public/admin/usuarios?error=1');
                }
            } catch (Exception $e) {
                error_log("Excepción al eliminar usuario: " . $e->getMessage());
                $this->redirect('/prueba-php/public/admin/usuarios?error=1');
            }
        } else {
            $this->redirect('/prueba-php/public/admin/usuarios?error=1');
        }
    }
    
    public function eventos($page = 1) {
        error_log("AdminController-new::eventos() llamado");
        
        $events = [];
        $totalEvents = 0;
        
        if ($this->eventModel) {
            try {
                $events = $this->eventModel->getAllEvents($page, 10);
                $totalEvents = $this->eventModel->getEventCount();
            } catch (Exception $e) {
                error_log("Error obteniendo eventos: " . $e->getMessage());
            }
        }
        
        $data = [
            'title' => 'Gestión de Eventos',
            'events' => $events,
            'currentPage' => $page,
            'totalPages' => ceil($totalEvents / 10)
        ];
        
        $this->view('admin/eventos/index', $data);
    }
    
    public function galeria() {
        error_log("AdminController-new::galeria() llamado");
        
        $data = [
            'title' => 'Gestión de Galería'
        ];
        
        $this->view('admin/gallery/index', $data);
    }
}
