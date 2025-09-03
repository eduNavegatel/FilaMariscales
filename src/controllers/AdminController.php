<?php
// Verificar si los archivos existen antes de incluirlos
if (file_exists(__DIR__ . '/../helpers/SecurityHelper.php')) {
    require_once __DIR__ . '/../helpers/SecurityHelper.php';
}

if (file_exists(__DIR__ . '/../config/admin_credentials.php')) {
    require_once __DIR__ . '/../config/admin_credentials.php';
}

class AdminController extends Controller {
    private $securityHelper;
    private $userModel;
    private $eventModel;

    public function __construct() {
        // Verify admin session using custom admin auth
        if (!isAdminLoggedIn()) {
            header('Location: /prueba-php/public/admin/login');
            exit;
        }
        
        // Initialize SecurityHelper only if it exists
        if (class_exists('SecurityHelper')) {
            $this->securityHelper = new SecurityHelper();
        }
        
        // Initialize models only if they exist
        if (class_exists('User')) {
            $this->userModel = $this->model('User');
            error_log("User model initialized successfully");
        } else {
            error_log("User class not found");
        }
        if (class_exists('Event')) {
            $this->eventModel = $this->model('Event');
        }
        
        // Set security headers if SecurityHelper exists
        if ($this->securityHelper) {
            $this->securityHelper->setSecurityHeaders();
        }
    }
    
    public function index() {
        $this->redirect('/admin/dashboard');
    }
    
    // Admin dashboard
    public function dashboard() {
        // Get counts for dashboard if models exist
        $userCount = 0;
        $eventCount = 0;
        $recentUsers = [];
        $recentEvents = [];
        
        if ($this->userModel) {
            $userCount = $this->userModel->getUserCount();
            $recentUsers = $this->userModel->getRecentUsers(5);
        }
        
        if ($this->eventModel) {
            $eventCount = $this->eventModel->getEventCount();
            $recentEvents = $this->eventModel->getRecentEvents(5);
        }
        
        // Get gallery count
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
    
    // Export dashboard data as CSV
    public function exportDashboard() {
        // Prepare data
        $userCount = 0;
        $eventCount = 0;
        $galleryCount = 0;
        $recentUsers = [];
        $recentEvents = [];
        
        if ($this->userModel) {
            $userCount = $this->userModel->getUserCount();
            // Try to fetch up to 50 recent users for export
            if (method_exists($this->userModel, 'getRecentUsers')) {
                $recentUsers = $this->userModel->getRecentUsers(50);
            }
        }
        if ($this->eventModel) {
            $eventCount = $this->eventModel->getEventCount();
            if (method_exists($this->eventModel, 'getRecentEvents')) {
                $recentEvents = $this->eventModel->getRecentEvents(50);
            }
        }
        // Gallery count from filesystem
        $uploadDir = 'uploads/gallery/';
        if (is_dir($uploadDir)) {
            $files = glob($uploadDir . '*');
            $galleryCount = is_array($files) ? count($files) : 0;
        }
        
        // Output CSV headers
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="dashboard_export_'.date('Ymd_His').'.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        $output = fopen('php://output', 'w');
        // BOM for UTF-8 Excel compatibility
        fwrite($output, "\xEF\xBB\xBF");
        
        // Summary
        fputcsv($output, ['Resumen del Panel']);
        fputcsv($output, ['Usuarios', 'Eventos', 'Archivos Galería']);
        fputcsv($output, [$userCount, $eventCount, $galleryCount]);
        fputcsv($output, []);
        
        // Recent users
        fputcsv($output, ['Últimos Usuarios']);
        fputcsv($output, ['ID', 'Nombre', 'Apellidos', 'Email', 'Rol', 'Activo', 'Fecha Registro']);
        foreach ($recentUsers as $u) {
            $activo = isset($u->activo) ? ($u->activo ? 'Sí' : 'No') : '';
            $fecha = isset($u->fecha_registro) ? $u->fecha_registro : '';
            fputcsv($output, [
                $u->id ?? '',
                $u->nombre ?? '',
                $u->apellidos ?? '',
                $u->email ?? '',
                $u->rol ?? '',
                $activo,
                $fecha
            ]);
        }
        fputcsv($output, []);
        
        // Recent events
        fputcsv($output, ['Próximos Eventos']);
        fputcsv($output, ['ID', 'Título', 'Fecha', 'Hora', 'Lugar', 'Público']);
        foreach ($recentEvents as $e) {
            fputcsv($output, [
                $e->id ?? '',
                $e->titulo ?? '',
                $e->fecha ?? '',
                $e->hora ?? '',
                $e->lugar ?? ($e->ubicacion ?? ''),
                isset($e->es_publico) ? ($e->es_publico ? 'Sí' : 'No') : ''
            ]);
        }
        
        fclose($output);
        exit;
    }
    
    // User management
    public function usuarios($page = 1) {
        // Set headers to prevent caching
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        $perPage = 10;
        $users = [];
        $totalUsers = 0;
        
        if ($this->userModel) {
            $users = $this->userModel->getAllUsers($page, $perPage);
            $totalUsers = $this->userModel->countAllUsers();
        }
        
        $data = [
            'title' => 'Gestión de Usuarios',
            'users' => $users,
            'currentPage' => $page,
            'totalPages' => ceil($totalUsers / $perPage),
            'timestamp' => time() // Add timestamp to force refresh
        ];
        
        $this->view('admin/users/index', $data);
    }
    
    // Show edit user form
    public function editarUsuarioForm($id = null) {
        error_log("editarUsuarioForm called with ID: " . $id);
        
        if (!$id || !is_numeric($id)) {
            error_log("Invalid ID provided: " . $id);
            setFlashMessage('error', 'ID de usuario inválido');
            $this->redirect('/admin/usuarios');
        }
        
        // Get user data
        $user = null;
        if ($this->userModel) {
            error_log("User model available, getting user by ID");
            $user = $this->userModel->getUserById($id);
        } else {
            error_log("User model not available");
        }
        
        if (!$user) {
            error_log("User not found for ID: " . $id);
            setFlashMessage('error', 'Usuario no encontrado');
            $this->redirect('/admin/usuarios');
        }
        
        error_log("User found: " . print_r($user, true));
        
        $data = [
            'title' => 'Editar Usuario',
            'user' => $user,
            'errors' => []
        ];
        
        $this->loadViewDirectly('admin/users/index', $data);
    }
    
    // Edit user
    public function editarUsuario($id = null) {
        // Debug logging
        error_log("editarUsuario called with ID: " . $id);
        error_log("Request method: " . $_SERVER['REQUEST_METHOD']);
        error_log("POST data: " . print_r($_POST, true));
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate CSRF token if SecurityHelper exists and token is provided
            if ($this->securityHelper && isset($_POST['csrf_token'])) {
                if (!$this->securityHelper->validateCsrfToken($_POST['csrf_token'])) {
                    setFlashMessage('error', 'Token de seguridad inválido.');
                    $this->redirect('/admin/usuarios');
                }
            }
            // If no SecurityHelper or no token, continue without validation for now
            
            // Process form data safely (without deprecated FILTER_SANITIZE_STRING)
            $userData = [
                'id' => $id ?: ($_POST['user_id'] ?? null),
                'nombre' => trim(htmlspecialchars($_POST['nombre'] ?? '')),
                'apellidos' => trim(htmlspecialchars($_POST['apellidos'] ?? '')),
                'email' => filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL),
                'rol' => trim(htmlspecialchars($_POST['rol'] ?? 'user')),
                'activo' => isset($_POST['activo']) && $_POST['activo'] == '1' ? 1 : 0,
                'errors' => []
            ];
            
            // Validate data
            if (empty($userData['nombre'])) $userData['errors']['nombre'] = 'Nombre requerido';
            if (empty($userData['email'])) $userData['errors']['email'] = 'Email requerido';
            if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
                $userData['errors']['email'] = 'Email inválido';
            }
            
            // Validate role
            $validRoles = ['user', 'socio', 'admin'];
            if (!in_array($userData['rol'], $validRoles)) {
                $userData['errors']['rol'] = 'Rol inválido. Debe ser: ' . implode(', ', $validRoles);
            }
            
            // Debug: Log the data being processed
            error_log("User data to update: " . print_r($userData, true));
            error_log("POST data received: " . print_r($_POST, true));
            error_log("ID from URL: " . $id);
            error_log("ID from POST: " . ($_POST['user_id'] ?? 'not set'));
            error_log("Activo field: " . (isset($_POST['activo']) ? $_POST['activo'] : 'not set'));
            error_log("Activo processed: " . (isset($_POST['activo']) && $_POST['activo'] == '1' ? 1 : 0));
            error_log("Rol field: " . ($_POST['rol'] ?? 'not set'));
            error_log("Rol processed: " . trim(htmlspecialchars($_POST['rol'] ?? 'user')));
            error_log("Email field: " . ($_POST['email'] ?? 'not set'));
            error_log("Email processed: " . filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL));
            error_log("Nombre field: " . ($_POST['nombre'] ?? 'not set'));
            error_log("Nombre processed: " . trim(htmlspecialchars($_POST['nombre'] ?? '')));
            error_log("Apellidos field: " . ($_POST['apellidos'] ?? 'not set'));
            error_log("Apellidos processed: " . trim(htmlspecialchars($_POST['apellidos'] ?? '')));
            
            if (empty($userData['errors']) && $this->userModel) {
                // Update user
                if ($id && is_numeric($id)) {
                    error_log("Attempting to update user with ID: " . $id);
                    $result = $this->userModel->updateUser($userData);
                    error_log("Update result: " . ($result ? 'success' : 'failed'));
                    
                    if ($result) {
                        setFlashMessage('success', 'Usuario actualizado correctamente');
                        // Redirigir con parámetros para forzar la recarga
                        $this->redirect('/admin/usuarios?updated=1&t=' . time() . '&refresh=1');
                    } else {
                        setFlashMessage('error', 'Error al actualizar el usuario');
                    }
                } else {
                    error_log("Invalid ID provided: " . $id);
                    setFlashMessage('error', 'ID de usuario requerido para edición');
                    $this->redirect('/admin/usuarios');
                }
            } else {
                // Show validation errors
                setFlashMessage('error', 'Errores de validación: ' . implode(', ', $userData['errors']));
                $this->redirect('/admin/usuarios');
            }
        } else {
            // GET request - redirect to users list
            $this->redirect('/admin/usuarios');
        }
    }
    
    // Delete user
    public function eliminarUsuario($id) {
        if ($this->userModel && $this->userModel->deleteUser($id)) {
            setFlashMessage('success', 'Usuario eliminado correctamente');
        } else {
            setFlashMessage('error', 'Error al eliminar el usuario');
        }
        $this->redirect('/admin/usuarios');
    }
    
    // Event management
    public function eventos($page = 1) {
        // Usar datos de ejemplo por ahora
        $events = [];
        $totalEvents = 0;
        
        // Intentar cargar el modelo si no está cargado
        if (!$this->eventModel && class_exists('Event')) {
            try {
                $this->eventModel = $this->model('Event');
            } catch (Exception $e) {
                // Si no se puede cargar el modelo, continuar con datos vacíos
            }
        }
        
        if ($this->eventModel) {
            try {
                $events = $this->eventModel->getAllEvents($page, 10);
                // Usar un valor por defecto si el método no existe
                $totalEvents = method_exists($this->eventModel, 'countAllEvents') 
                    ? $this->eventModel->countAllEvents() 
                    : count($events);
            } catch (Exception $e) {
                // Si hay error, usar datos vacíos
                $events = [];
                $totalEvents = 0;
            }
        }
        
        $data = [
            'title' => 'Gestión de Eventos',
            'events' => $events,
            'currentPage' => $page,
            'totalPages' => ceil($totalEvents / 10)
        ];
        
        // Cargar la vista directamente sin layout
        $this->loadViewDirectly('admin/eventos/index', $data);
    }
    
    // Método para cargar vistas directamente sin layout
    private function loadViewDirectly($view, $data = []) {
        // Extract data array to individual variables
        extract($data);
        
        // Include the view file directly
        $viewFile = dirname(dirname(__DIR__)) . '/src/views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die('View does not exist: ' . $viewFile);
        }
    }
    
    // Método de prueba para eventos
    public function test() {
        echo "<!DOCTYPE html>";
        echo "<html lang='es'>";
        echo "<head>";
        echo "<meta charset='UTF-8'>";
        echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
        echo "<title>Test Eventos - Filá Mariscales</title>";
        echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
        echo "</head>";
        echo "<body>";
        echo "<div class='container mt-4'>";
        echo "<h1>Test de Eventos</h1>";
        echo "<p>Esta es una vista de prueba para verificar que funciona.</p>";
        echo "<p>Si ves esto, significa que el sistema de vistas funciona correctamente.</p>";
        echo "<a href='/prueba-php/public/admin/dashboard' class='btn btn-primary'>Volver al Dashboard</a>";
        echo "</div>";
        echo "</body>";
        echo "</html>";
    }
    
    // Create new event
    public function nuevoEvento() {
        $eventData = ['errors' => []]; // Inicializar variable
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate CSRF token if SecurityHelper exists and token is provided
            if ($this->securityHelper && isset($_POST['csrf_token'])) {
                if (!$this->securityHelper->validateCsrfToken($_POST['csrf_token'])) {
                    setFlashMessage('error', 'Token de seguridad inválido.');
                    $this->redirect('/admin/eventos');
                }
            }
            // If no SecurityHelper or no token, continue without validation for now
            
            // Process form
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $eventData = [
                'titulo' => trim($_POST['titulo']),
                'descripcion' => trim($_POST['descripcion']),
                'fecha' => trim($_POST['fecha']),
                'hora' => trim($_POST['hora']),
                'ubicacion' => trim($_POST['ubicacion']),
                'errors' => []
            ];
            
            // Validate data
            if (empty($eventData['titulo'])) $eventData['errors']['titulo'] = 'Título requerido';
            if (empty($eventData['fecha'])) $eventData['errors']['fecha'] = 'Fecha requerida';
            
            if (empty($eventData['errors']) && $this->eventModel) {
                // Create event
                $result = $this->eventModel->createEvent($eventData);
                
                if ($result) {
                    setFlashMessage('success', 'Evento creado correctamente');
                    $this->redirect('/admin/eventos');
                } else {
                    setFlashMessage('error', 'Error al crear el evento');
                }
            }
        }
        
        $data = [
            'title' => 'Nuevo Evento',
            'event' => null,
            'errors' => $eventData['errors'] ?? []
        ];
        
        $this->loadViewDirectly('admin/eventos/editar', $data);
    }
    
    // Edit event
    public function editarEvento($id = null) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate CSRF token if SecurityHelper exists and token is provided
            if ($this->securityHelper && isset($_POST['csrf_token'])) {
                if (!$this->securityHelper->validateCsrfToken($_POST['csrf_token'])) {
                    setFlashMessage('error', 'Token de seguridad inválido.');
                    $this->redirect('/admin/eventos');
                }
            }
            // If no SecurityHelper or no token, continue without validation for now
            
            // Process form
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $eventData = [
                'id' => $id,
                'titulo' => trim($_POST['titulo']),
                'descripcion' => trim($_POST['descripcion']),
                'fecha' => trim($_POST['fecha']),
                'hora' => trim($_POST['hora']),
                'ubicacion' => trim($_POST['ubicacion']),
                'errors' => []
            ];
            
            // Validate data
            if (empty($eventData['titulo'])) $eventData['errors']['titulo'] = 'Título requerido';
            if (empty($eventData['fecha'])) $eventData['errors']['fecha'] = 'Fecha requerida';
            
            if (empty($eventData['errors']) && $this->eventModel) {
                // Update or create event
                if ($id) {
                    $result = $this->eventModel->updateEvent($eventData);
                } else {
                    $result = $this->eventModel->createEvent($eventData);
                }
                
                if ($result) {
                    setFlashMessage('success', 'Evento guardado correctamente');
                    $this->redirect('/admin/eventos');
                } else {
                    setFlashMessage('error', 'Error al guardar el evento');
                }
            }
        }
        
        // Get event data for editing
        $event = null;
        if ($id && $this->eventModel) {
            $event = $this->eventModel->getEventById($id);
        }
        
        $data = [
            'title' => $id ? 'Editar Evento' : 'Nuevo Evento',
            'event' => $event,
            'errors' => $eventData['errors'] ?? []
        ];
        
        $this->loadViewDirectly('admin/eventos/editar', $data);
    }
    
    // Delete event
    public function eliminarEvento($id) {
        if ($this->eventModel && $this->eventModel->deleteEvent($id)) {
            setFlashMessage('success', 'Evento eliminado correctamente');
        } else {
            setFlashMessage('error', 'Error al eliminar el evento');
        }
        $this->redirect('/admin/eventos');
    }
    
    // Settings
    public function configuracion() {
        $data = [
            'title' => 'Configuración del Sistema'
        ];
        
        $this->view('admin/settings', $data);
    }
    
    // Profile
    public function perfil() {
        $data = [
            'title' => 'Mi Perfil',
            'admin' => getAdminInfo()
        ];
        
        $this->view('admin/profile', $data);
    }
    
    // Gallery Management
    public function galeria() {
        $mediaFiles = $this->getMediaFiles();
        $carouselFiles = $this->getCarouselFiles();
        
        $data = [
            'title' => 'Gestión de Galería',
            'mediaFiles' => $mediaFiles,
            'carouselFiles' => $carouselFiles
        ];
        
        $this->loadViewDirectly('admin/gallery/index', $data);
    }
    
    // Upload media to gallery
    public function subirMedia() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $uploadDir = 'uploads/gallery/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $uploadedFiles = [];
            $errors = [];
            
            foreach ($_FILES['media']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['media']['error'][$key] === UPLOAD_ERR_OK) {
                    $fileName = $_FILES['media']['name'][$key];
                    $fileType = $_FILES['media']['type'][$key];
                    $fileSize = $_FILES['media']['size'][$key];
                    
                    // Validate file type
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'video/mp4', 'video/avi', 'video/mov'];
                    if (!in_array($fileType, $allowedTypes)) {
                        $errors[] = "Tipo de archivo no permitido: $fileName";
                        continue;
                    }
                    
                    // Validate file size (max 50MB)
                    if ($fileSize > 52428800) {
                        $errors[] = "Archivo demasiado grande: $fileName";
                        continue;
                    }
                    
                    // Generate unique filename
                    $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                    $newFileName = uniqid() . '_' . time() . '.' . $extension;
                    $targetPath = $uploadDir . $newFileName;
                    
                    if (move_uploaded_file($tmp_name, $targetPath)) {
                        $uploadedFiles[] = [
                            'original_name' => $fileName,
                            'file_name' => $newFileName,
                            'file_path' => $targetPath,
                            'file_type' => $fileType,
                            'file_size' => $fileSize,
                            'upload_date' => date('Y-m-d H:i:s')
                        ];
                    } else {
                        $errors[] = "Error al subir: $fileName";
                    }
                }
            }
            
            if (!empty($uploadedFiles)) {
                setFlashMessage('success', count($uploadedFiles) . ' archivo(s) subido(s) correctamente');
            }
            if (!empty($errors)) {
                setFlashMessage('error', 'Errores: ' . implode(', ', $errors));
            }
        }
        
        $this->redirect('/admin/galeria');
    }
    
    // Delete media from gallery
    public function eliminarMedia($fileName) {
        $filePath = 'uploads/gallery/' . $fileName;
        
        if (file_exists($filePath) && unlink($filePath)) {
            setFlashMessage('success', 'Archivo eliminado correctamente');
        } else {
            setFlashMessage('error', 'Error al eliminar el archivo');
        }
        
        $this->redirect('/admin/galeria');
    }
    
    // Upload images to carousel
    public function subirCarousel() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $uploadDir = 'uploads/carousel/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $uploadedFiles = [];
            $errors = [];
            
            foreach ($_FILES['carouselImages']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['carouselImages']['error'][$key] === UPLOAD_ERR_OK) {
                    $fileName = $_FILES['carouselImages']['name'][$key];
                    $fileType = $_FILES['carouselImages']['type'][$key];
                    $fileSize = $_FILES['carouselImages']['size'][$key];
                    
                    // Validate file type (only images)
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                    if (!in_array($fileType, $allowedTypes)) {
                        $errors[] = "Solo se permiten imágenes: $fileName";
                        continue;
                    }
                    
                    // Validate file size (max 10MB)
                    if ($fileSize > 10485760) {
                        $errors[] = "Archivo demasiado grande (máx 10MB): $fileName";
                        continue;
                    }
                    
                    // Generate unique filename
                    $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                    $newFileName = 'carousel_' . uniqid() . '_' . time() . '.' . $extension;
                    $targetPath = $uploadDir . $newFileName;
                    
                    if (move_uploaded_file($tmp_name, $targetPath)) {
                        $uploadedFiles[] = [
                            'original_name' => $fileName,
                            'file_name' => $newFileName,
                            'file_path' => $targetPath,
                            'file_type' => $fileType,
                            'file_size' => $fileSize,
                            'upload_date' => date('Y-m-d H:i:s')
                        ];
                    } else {
                        $errors[] = "Error al subir: $fileName";
                    }
                }
            }
            
            if (!empty($uploadedFiles)) {
                setFlashMessage('success', count($uploadedFiles) . ' imagen(es) subida(s) al carrusel correctamente');
            }
            if (!empty($errors)) {
                setFlashMessage('error', 'Errores: ' . implode(', ', $errors));
            }
        }
        
        $this->redirect('/admin/galeria');
    }
    
    // Delete image from carousel
    public function eliminarCarousel($fileName) {
        $filePath = 'uploads/carousel/' . $fileName;
        
        if (file_exists($filePath) && unlink($filePath)) {
            setFlashMessage('success', 'Imagen eliminada del carrusel correctamente');
        } else {
            setFlashMessage('error', 'Error al eliminar la imagen del carrusel');
        }
        
        $this->redirect('/admin/galeria');
    }
    
    // Get media files from gallery
    private function getMediaFiles() {
        $uploadDir = 'uploads/gallery/';
        $files = [];
        
        if (is_dir($uploadDir)) {
            $mediaFiles = glob($uploadDir . '*');
            foreach ($mediaFiles as $file) {
                if (is_file($file)) {
                    $fileInfo = pathinfo($file);
                    $fileName = $fileInfo['basename'];
                    
                    // Excluir archivos de configuración y descripciones
                    if ($fileName === 'descriptions.json' || $fileName === '.htaccess' || $fileName === 'index.html') {
                        continue;
                    }
                    
                    $files[] = [
                        'name' => $fileName,
                        'path' => $file,
                        'url' => $this->getImageUrl($file),
                        'size' => filesize($file),
                        'type' => mime_content_type($file),
                        'date' => date('Y-m-d H:i:s', filemtime($file)),
                        'description' => $this->getImageDescription($fileName, 'gallery')
                    ];
                }
            }
        }
        
        return $files;
    }
    
    // Get carousel files
    private function getCarouselFiles() {
        $uploadDir = 'uploads/carousel/';
        $files = [];
        
        if (is_dir($uploadDir)) {
            $mediaFiles = glob($uploadDir . '*');
            foreach ($mediaFiles as $file) {
                if (is_file($file)) {
                    $fileInfo = pathinfo($file);
                    $fileName = $fileInfo['basename'];
                    
                    // Excluir archivos de configuración y descripciones
                    if ($fileName === 'descriptions.json' || $fileName === '.htaccess' || $fileName === 'index.html') {
                        continue;
                    }
                    
                    $files[] = [
                        'name' => $fileName,
                        'path' => $file,
                        'url' => $this->getImageUrl($file),
                        'size' => filesize($file),
                        'type' => mime_content_type($file),
                        'date' => date('Y-m-d H:i:s', filemtime($file)),
                        'description' => $this->getImageDescription($fileName, 'carousel')
                    ];
                }
            }
        }
        
        return $files;
    }

    // Método para generar URLs de imágenes
    private function getImageUrl($filePath) {
        // Si es una URL externa, devolverla tal como está
        if (strpos($filePath, 'http') === 0) {
            return $filePath;
        }
        
        // Usar el script servidor para asegurar que funcione
        return '/prueba-php/public/serve-image.php?path=' . urlencode($filePath);
    }
    
    // Obtener descripción de una imagen
    private function getImageDescription($fileName, $type = 'gallery') {
        $descriptionsFile = 'uploads/' . $type . '/descriptions.json';
        
        if (file_exists($descriptionsFile)) {
            $descriptions = json_decode(file_get_contents($descriptionsFile), true);
            return $descriptions[$fileName] ?? '';
        }
        
        return '';
    }
    
    // Guardar descripción de una imagen
    private function saveImageDescription($fileName, $description, $type = 'gallery') {
        $descriptionsFile = 'uploads/' . $type . '/descriptions.json';
        $descriptions = [];
        
        if (file_exists($descriptionsFile)) {
            $descriptions = json_decode(file_get_contents($descriptionsFile), true) ?? [];
        }
        
        $descriptions[$fileName] = trim($description);
        
        // Asegurar que el directorio existe
        $dir = dirname($descriptionsFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        return file_put_contents($descriptionsFile, json_encode($descriptions, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
    
    // Update gallery image description
    public function actualizarDescripcionGaleria() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $fileName = $_POST['fileName'] ?? '';
            $description = $_POST['description'] ?? '';
            
            if (!empty($fileName)) {
                if ($this->saveImageDescription($fileName, $description, 'gallery')) {
                    setFlashMessage('success', 'Descripción actualizada correctamente');
                } else {
                    setFlashMessage('error', 'Error al actualizar la descripción');
                }
            } else {
                setFlashMessage('error', 'Nombre de archivo requerido');
            }
        }
        
        $this->redirect('/admin/galeria');
    }
    
    // Update carousel image description
    public function actualizarDescripcionCarousel() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $fileName = $_POST['fileName'] ?? '';
            $description = $_POST['description'] ?? '';
            
            if (!empty($fileName)) {
                if ($this->saveImageDescription($fileName, $description, 'carousel')) {
                    setFlashMessage('success', 'Descripción del carrusel actualizada correctamente');
                } else {
                    setFlashMessage('error', 'Error al actualizar la descripción del carrusel');
                }
            } else {
                setFlashMessage('error', 'Nombre de archivo requerido');
            }
        }
        
        $this->redirect('/admin/galeria');
    }
    
    // User Management - Create custom user
    public function crearUsuario() {
        $userData = [
            'nombre' => '',
            'apellidos' => '',
            'email' => '',
            'password' => '',
            'rol' => 'user',
            'activo' => 1,
            'errors' => []
        ];
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $userData = [
                'nombre' => trim($_POST['nombre'] ?? ''),
                'apellidos' => trim($_POST['apellidos'] ?? ''),
                'email' => filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL),
                'password' => trim($_POST['password'] ?? ''),
                'confirm_password' => trim($_POST['confirm_password'] ?? ''),
                'rol' => trim($_POST['rol'] ?? 'user'),
                'activo' => isset($_POST['activo']) ? 1 : 0,
                'errors' => []
            ];
            
            // Validate data
            if (empty($userData['nombre'])) {
                $userData['errors']['nombre'] = 'Nombre requerido';
            }
            
            if (empty($userData['email'])) {
                $userData['errors']['email'] = 'Email requerido';
            } elseif (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
                $userData['errors']['email'] = 'Email inválido';
            } elseif ($this->userModel && $this->userModel->findUserByEmail($userData['email'])) {
                $userData['errors']['email'] = 'El email ya está registrado';
            }
            
            if (empty($userData['password'])) {
                $userData['errors']['password'] = 'Contraseña requerida';
            } elseif (strlen($userData['password']) < 6) {
                $userData['errors']['password'] = 'La contraseña debe tener al menos 6 caracteres';
            }
            
            if (empty($userData['confirm_password'])) {
                $userData['errors']['confirm_password'] = 'Confirmar contraseña requerida';
            } elseif ($userData['password'] !== $userData['confirm_password']) {
                $userData['errors']['confirm_password'] = 'Las contraseñas no coinciden';
            }
            
            // Validar rol
            $rolesPermitidos = ['user', 'socio', 'admin'];
            if (!in_array($userData['rol'], $rolesPermitidos)) {
                $userData['errors']['rol'] = 'Rol inválido. Debe ser: ' . implode(', ', $rolesPermitidos);
            }
            
            if (empty($userData['errors']) && $this->userModel) {
                try {
                    // Hash password
                    $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
                    
                    $result = $this->userModel->register($userData);
                    
                    if ($result) {
                        setFlashMessage('success', 'Usuario creado correctamente');
                        $this->redirect('/admin/usuarios');
                        return;
                    } else {
                        $userData['errors']['general'] = 'Error al crear el usuario en la base de datos';
                    }
                } catch (Exception $e) {
                    $userData['errors']['general'] = 'Error interno: ' . $e->getMessage();
                }
            }
        }
        
        $data = [
            'title' => 'Crear Nuevo Usuario',
            'userData' => $userData,
            'errors' => $userData['errors'] ?? []
        ];
        
        $this->loadViewDirectly('admin/users/create', $data);
    }
    
    // User Management - Deactivate user
    public function desactivarUsuario($id) {
        if ($this->userModel) {
            $result = $this->userModel->updateUserStatus($id, 0);
            if ($result) {
                setFlashMessage('success', 'Usuario desactivado correctamente');
            } else {
                setFlashMessage('error', 'Error al desactivar el usuario');
            }
        }
        $this->redirect('/admin/usuarios');
    }
    
    // User Management - Activate user
    public function activarUsuario($id) {
        if ($this->userModel) {
            $result = $this->userModel->updateUserStatus($id, 1);
            if ($result) {
                setFlashMessage('success', 'Usuario activado correctamente');
            } else {
                setFlashMessage('error', 'Error al activar el usuario');
            }
        }
        $this->redirect('/admin/usuarios');
    }
    
    // User Management - Reset password
    public function resetearPassword($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $newPassword = trim($_POST['new_password']);
            
            if (strlen($newPassword) >= 6) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                
                if ($this->userModel) {
                    $result = $this->userModel->updatePassword($id, $hashedPassword);
                    if ($result) {
                        setFlashMessage('success', 'Contraseña actualizada correctamente');
                    } else {
                        setFlashMessage('error', 'Error al actualizar la contraseña');
                    }
                }
            } else {
                setFlashMessage('error', 'La contraseña debe tener al menos 6 caracteres');
            }
        }
        
        $this->redirect('/admin/usuarios');
    }
    
    // User Management - Toggle user status (AJAX)
    public function toggleUserStatus($id) {
        // Verificar que sea una petición AJAX
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Petición inválida']);
            return;
        }
        
        // Obtener el contenido JSON de la petición
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
            return;
        }
        
        // Validar token CSRF si SecurityHelper existe y se proporciona el token
        if ($this->securityHelper && isset($input['csrf_token'])) {
            if (!$this->securityHelper->validateCsrfToken($input['csrf_token'])) {
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => 'Token de seguridad inválido']);
                return;
            }
        }
        // Si no hay SecurityHelper o no se proporciona token, continuar sin validación por ahora
        
        // Validar el estado
        $status = $input['status'] ?? null;
        if (!in_array($status, [0, 1])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Estado inválido']);
            return;
        }
        
        // Actualizar el estado del usuario
        if ($this->userModel) {
            $result = $this->userModel->updateUserStatus($id, $status);
            
            if ($result) {
                $action = $status ? 'activado' : 'desactivado';
                echo json_encode(['success' => true, 'message' => "Usuario {$action} correctamente"]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar el estado del usuario']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Error interno del servidor']);
        }
    }
}
