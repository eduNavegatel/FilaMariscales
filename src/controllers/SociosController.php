<?php

class SociosController extends Controller {
    private $userModel;

    public function __construct() {
        // Iniciar sesión si no está iniciada (antes de cualquier output)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->userModel = $this->model('User');
    }

    // Mostrar página de socios (con login si no está autenticado)
    public function index() {
        // Si ya está logueado como socio, mostrar dashboard
        if (isLoggedIn() && $_SESSION['user_role'] === 'socio') {
            $this->dashboard();
            return;
        }
        
        // Si está logueado pero no es socio, redirigir
        if (isLoggedIn() && $_SESSION['user_role'] !== 'socio') {
            setFlashMessage('error', 'No tienes permisos para acceder al área de socios');
            redirect('/');
            return;
        }
        
        // Obtener estadísticas de la base de datos
        $stats = $this->getSociosStats();
        
        // Mostrar página de login para socios
        $data = [
            'title' => 'Login de Socios',
            'email' => '',
            'password' => '',
            'email_err' => '',
            'password_err' => '',
            'stats' => $stats
        ];
        $this->viewSimple('socios/login', $data);
    }

    // Procesar login de socios
    public function login() {
        // Verificar que sea POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/socios');
            return;
        }

        // Sanitizar datos de entrada
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        // Validar email
        if (empty($email)) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Por favor ingrese su correo electrónico']);
            return;
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Por favor ingrese un correo electrónico válido']);
            return;
        }

        // Validar contraseña
        if (empty($password)) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Por favor ingrese su contraseña']);
            return;
        }

        // Buscar usuario en la base de datos
        $user = $this->userModel->findUserByEmail($email);
        
        if (!$user) {
            $this->logSecurityEvent('failed_login_socio', 'Intento de login con email inexistente: ' . $email);
            $this->sendJsonResponse(['success' => false, 'message' => 'Las credenciales no son correctas']);
            return;
        }
        
        // Verificar que sea socio
        if ($user->rol !== 'socio') {
            $this->logSecurityEvent('failed_login_socio', 'Intento de login con usuario no socio: ' . $email);
            $this->sendJsonResponse(['success' => false, 'message' => 'Las credenciales no son correctas']);
            return;
        }
        
        // Verificar que esté activo
        if (!$user->activo) {
            $this->logSecurityEvent('failed_login_socio', 'Intento de login con socio inactivo: ' . $email);
            $this->sendJsonResponse(['success' => false, 'message' => 'Su cuenta de socio está desactivada. Contacte con la directiva.']);
            return;
        }
        
        // Verificar contraseña
        if (!password_verify($password, $user->password)) {
            $this->logSecurityEvent('failed_login_socio', 'Intento de login con contraseña incorrecta: ' . $email);
            $this->sendJsonResponse(['success' => false, 'message' => 'Las credenciales no son correctas']);
            return;
        }
        
        // Login exitoso
        $this->createSocioSession($user);
        $this->logActivity($user->id, 'login_socio', 'Login exitoso en área de socios');
        $this->sendJsonResponse(['success' => true, 'redirect' => '/prueba-php/public/socios/dashboard']);
    }

    // Crear sesión de socio
    private function createSocioSession($user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->nombre . ' ' . $user->apellidos;
        $_SESSION['user_role'] = $user->rol;
        $_SESSION['socio_logged_in'] = true;
        
        // Actualizar último acceso
        $this->userModel->updateLastLogin($user->id);
    }

    // Dashboard de socios
    public function dashboard() {
        // Verificar que esté logueado como socio
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'socio') {
            setFlashMessage('error', 'Debe iniciar sesión como socio para acceder a esta área');
            redirect('/socios');
            return;
        }

        // Obtener datos del socio
        $user = $this->userModel->findUserById($_SESSION['user_id']);
        
        if (!$user) {
            $this->logout();
            return;
        }

        // Obtener datos para el dashboard
        $data = [
            'title' => 'Dashboard de Socio',
            'user' => $user,
            'socio_data' => $this->getSocioData($user),
            'eventos' => $this->getEventosSocio(),
            'documentos' => $this->getDocumentosSocio()
        ];

        $this->viewSimple('socios/dashboard', $data);
    }

    // Obtener datos específicos del socio
    private function getSocioData($user) {
        return [
            'nombre' => $user->nombre . ' ' . $user->apellidos,
            'numero_socio' => 'MS-' . date('Y') . '-' . str_pad($user->id, 3, '0', STR_PAD_LEFT),
            'fecha_ingreso' => date('d/m/Y', strtotime($user->fecha_registro)),
            'categoria' => 'Socio Activo',
            'cuota_al_dia' => true, // Se puede implementar lógica de cuotas
            'ultima_cuota' => date('F Y', strtotime('-1 month')),
            'proximo_evento' => 'Reunión de Directiva - ' . date('d/m/Y', strtotime('+1 week'))
        ];
    }

    // Obtener eventos para socios
    private function getEventosSocio() {
        $db = new Database();
        $db->query("SELECT * FROM eventos WHERE fecha_inicio >= NOW() AND estado = 'activo' ORDER BY fecha_inicio ASC LIMIT 5");
        $eventos = $db->resultSet();
        
        if (!$eventos) {
            return [
                [
                    'titulo' => 'Reunión de Directiva',
                    'fecha' => '25/02/2024',
                    'hora' => '20:00',
                    'lugar' => 'Sede Social',
                    'tipo' => 'reunion'
                ],
                [
                    'titulo' => 'Ensayos de Bandas',
                    'fecha' => '28/02/2024',
                    'hora' => '19:30',
                    'lugar' => 'Sala de Ensayos',
                    'tipo' => 'ensayo'
                ]
            ];
        }
        
        $eventos_formateados = [];
        foreach ($eventos as $evento) {
            $eventos_formateados[] = [
                'titulo' => $evento->titulo,
                'fecha' => date('d/m/Y', strtotime($evento->fecha_inicio)),
                'hora' => date('H:i', strtotime($evento->fecha_inicio)),
                'lugar' => $evento->ubicacion ?: 'Sede Social',
                'tipo' => 'evento'
            ];
        }
        
        return $eventos_formateados;
    }

    // Obtener documentos para socios
    private function getDocumentosSocio() {
        return [
            [
                'nombre' => 'Estatutos de la Filá',
                'tipo' => 'PDF',
                'tamaño' => '2.5 MB',
                'fecha' => '15/01/2024'
            ],
            [
                'nombre' => 'Calendario 2024',
                'tipo' => 'PDF',
                'tamaño' => '1.8 MB',
                'fecha' => '10/01/2024'
            ],
            [
                'nombre' => 'Reglamento Interno',
                'tipo' => 'PDF',
                'tamaño' => '3.2 MB',
                'fecha' => '05/01/2024'
            ]
        ];
    }

    // Logout de socios
    public function logout() {
        if (isset($_SESSION['user_id'])) {
            $this->logActivity($_SESSION['user_id'], 'logout_socio', 'Logout del área de socios');
        }
        
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_role']);
        unset($_SESSION['socio_logged_in']);
        session_destroy();
        
        setFlashMessage('success', 'Has cerrado sesión correctamente');
        redirect('/socios');
    }

    // Cargar vista simple sin layout
    private function viewSimple($view, $data = []) {
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

    // Enviar respuesta JSON
    private function sendJsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    // Obtener estadísticas de socios
    private function getSociosStats() {
        try {
            // Usar la instancia de Database directamente
            $db = new Database();
            
            // Obtener número de socios activos
            $db->query("SELECT COUNT(*) as count FROM users WHERE rol = 'socio' AND activo = 1");
            $result = $db->single();
            $sociosActivos = $result ? $result->count : 0;
            
            // Obtener años de historia (desde 1985)
            $aniosHistoria = date('Y') - 1985;
            
            // Obtener número de eventos anuales (simulado)
            $eventosAnuales = 25; // Esto se puede cambiar para obtener de la tabla eventos
            
            return [
                'socios_activos' => $sociosActivos,
                'anios_historia' => $aniosHistoria,
                'eventos_anuales' => $eventosAnuales
            ];
        } catch (Exception $e) {
            // En caso de error, devolver valores por defecto
            return [
                'socios_activos' => 150,
                'anios_historia' => 39,
                'eventos_anuales' => 25
            ];
        }
    }

    // Registrar actividad del usuario
    private function logActivity($userId, $action, $details = '') {
        try {
            $db = new Database();
            $db->query('INSERT INTO user_activity_logs (user_id, action, ip_address, user_agent, details, created_at) 
                       VALUES (:user_id, :action, :ip, :ua, :details, NOW())');
            $db->bind(':user_id', $userId);
            $db->bind(':action', $action);
            $db->bind(':ip', $_SERVER['REMOTE_ADDR']);
            $db->bind(':ua', $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown');
            $db->bind(':details', $details);
            $db->execute();
        } catch (Exception $e) {
            // Si la tabla no existe, simplemente ignorar el logging
            // error_log("Logging failed: " . $e->getMessage());
        }
    }
    
    // Registrar error de seguridad
    private function logSecurityEvent($eventType, $details) {
        try {
            $db = new Database();
            $db->query('INSERT INTO security_logs (event_type, ip_address, user_agent, details, created_at) 
                       VALUES (:event_type, :ip, :ua, :details, NOW())');
            $db->bind(':event_type', $eventType);
            $db->bind(':ip', $_SERVER['REMOTE_ADDR']);
            $db->bind(':ua', $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown');
            $db->bind(':details', $details);
            $db->execute();
        } catch (Exception $e) {
            // Si la tabla no existe, simplemente ignorar el logging
            // error_log("Security logging failed: " . $e->getMessage());
        }
    }

    // Subir foto de perfil
    public function uploadPhoto() {
        // Verificar que esté logueado como socio
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'socio') {
            $this->sendJsonResponse(['success' => false, 'message' => 'No autorizado']);
            return;
        }

        // Verificar que sea POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendJsonResponse(['success' => false, 'message' => 'Método no permitido']);
            return;
        }

        // Verificar que se haya subido un archivo
        if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Error al subir el archivo']);
            return;
        }

        $file = $_FILES['photo'];
        
        // Validar tipo de archivo
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Solo se permiten imágenes (JPG, PNG, GIF)']);
            return;
        }

        // Validar tamaño (máximo 5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            $this->sendJsonResponse(['success' => false, 'message' => 'El archivo es demasiado grande (máximo 5MB)']);
            return;
        }

        // Crear directorio si no existe
        $uploadDir = dirname(dirname(dirname(__DIR__))) . '/public/uploads/socios/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generar nombre único para el archivo
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'socio_' . $_SESSION['user_id'] . '_' . time() . '.' . $extension;
        $filepath = $uploadDir . $filename;

        // Mover archivo
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            // Actualizar base de datos
            try {
                $db = new Database();
                $db->query('UPDATE users SET foto = ? WHERE id = ?');
                $db->bind(1, $filename);
                $db->bind(2, $_SESSION['user_id']);
                $db->execute();

                $this->sendJsonResponse([
                    'success' => true, 
                    'message' => 'Foto actualizada correctamente',
                    'photo_url' => '/prueba-php/public/uploads/socios/' . $filename
                ]);
            } catch (Exception $e) {
                $this->sendJsonResponse(['success' => false, 'message' => 'Error al actualizar la base de datos']);
            }
        } else {
            $this->sendJsonResponse(['success' => false, 'message' => 'Error al guardar el archivo']);
        }
    }

    // Obtener lista de amigos
    public function getFriends() {
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'socio') {
            $this->sendJsonResponse(['success' => false, 'message' => 'No autorizado']);
            return;
        }

        try {
            $db = new Database();
            // Por ahora devolvemos una lista simulada
            $friends = [
                ['id' => 1, 'name' => 'María García', 'email' => 'maria.garcia@mariscales.com'],
                ['id' => 2, 'name' => 'Antonio Rodríguez', 'email' => 'antonio.rodriguez@mariscales.com'],
                ['id' => 3, 'name' => 'Carmen López', 'email' => 'carmen.lopez@mariscales.com']
            ];

            $this->sendJsonResponse(['success' => true, 'friends' => $friends]);
        } catch (Exception $e) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Error al obtener amigos']);
        }
    }

    // Añadir amigo
    public function addFriend() {
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'socio') {
            $this->sendJsonResponse(['success' => false, 'message' => 'No autorizado']);
            return;
        }

        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        
        if (empty($email)) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Email requerido']);
            return;
        }

        try {
            $db = new Database();
            $db->query('SELECT id, nombre, apellidos, email FROM users WHERE email = ? AND rol = "socio" AND activo = 1');
            $db->bind(1, $email);
            $user = $db->single();

            if ($user) {
                $this->sendJsonResponse([
                    'success' => true, 
                    'message' => 'Usuario encontrado',
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->nombre . ' ' . $user->apellidos,
                        'email' => $user->email
                    ]
                ]);
            } else {
                $this->sendJsonResponse(['success' => false, 'message' => 'Usuario no encontrado']);
            }
        } catch (Exception $e) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Error al buscar usuario']);
        }
    }

    // Obtener comentarios
    public function getComments() {
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'socio') {
            $this->sendJsonResponse(['success' => false, 'message' => 'No autorizado']);
            return;
        }

        try {
            // Por ahora devolvemos comentarios simulados
            $comments = [
                [
                    'id' => 1,
                    'author' => 'María García',
                    'text' => '¡Hola a todos! ¿Alguien va al próximo ensayo?',
                    'date' => '2024-01-15 10:30'
                ],
                [
                    'id' => 2,
                    'author' => 'Antonio Rodríguez',
                    'text' => 'Yo sí voy, nos vemos allí',
                    'date' => '2024-01-15 11:15'
                ]
            ];

            $this->sendJsonResponse(['success' => true, 'comments' => $comments]);
        } catch (Exception $e) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Error al obtener comentarios']);
        }
    }

    // Enviar comentario
    public function sendComment() {
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'socio') {
            $this->sendJsonResponse(['success' => false, 'message' => 'No autorizado']);
            return;
        }

        $text = trim($_POST['text'] ?? '');
        
        if (empty($text)) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Comentario requerido']);
            return;
        }

        try {
            // Por ahora simulamos el guardado
            $comment = [
                'id' => time(),
                'author' => $_SESSION['user_name'],
                'text' => $text,
                'date' => date('Y-m-d H:i:s')
            ];

            $this->sendJsonResponse([
                'success' => true, 
                'message' => 'Comentario enviado correctamente',
                'comment' => $comment
            ]);
        } catch (Exception $e) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Error al enviar comentario']);
        }
    }
}
