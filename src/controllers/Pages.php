<?php
class Pages extends Controller {
    private $userModel;
    private $eventModel;

    public function __construct() {
        // Cargar el modelo de usuario para autenticación
        $this->userModel = $this->model('User');
        
        // Cargar el modelo de eventos
        if (class_exists('Event')) {
            $this->eventModel = $this->model('Event');
        }
    }

    // Página de inicio
    public function index() {
        // Cargar imágenes del carrusel dinámicamente
        $carouselImages = $this->getCarouselImages();
        
        // Cargar imágenes de la galería dinámicamente
        $galleryImages = $this->getGalleryImages();
        
        // Cargar eventos próximos dinámicamente
        $upcomingEvents = $this->getUpcomingEvents();
        
        $data = [
            'title' => 'Inicio',
            'description' => 'Bienvenidos a la Filá Mariscales de Caballeros Templarios de Elche',
            'carousel_images' => $carouselImages,
            'upcoming_events' => $upcomingEvents,
            'gallery' => $galleryImages
        ];
        $this->view('pages/home', $data);
    }

    // Página de blog
    public function blog() {
        $data = [
            'title' => 'Blog',
            'description' => 'Artículos y publicaciones de la Filá Mariscales'
        ];
        $this->view('pages/blog', $data);
    }

    // Página de calendario
    public function calendario() {
        // Cargar eventos dinámicamente
        $events = $this->getAllEvents();
        
        $data = [
            'title' => 'Calendario',
            'description' => 'Calendario de eventos de la Filá Mariscales',
            'events' => $events
        ];
        $this->view('pages/calendario', $data);
    }

    // Página de descargas
    public function descargas() {
        $data = [
            'title' => 'Descargas',
            'description' => 'Documentos y archivos para descargar'
        ];
        $this->view('pages/descargas', $data);
    }

    // Página de directiva
    public function directiva() {
        $data = [
            'title' => 'Directiva',
            'description' => 'Conoce a los miembros de la junta directiva de la Filá Mariscales'
        ];
        $this->view('pages/directiva', $data);
    }

    // Página de galería
    public function galeria() {
        // Cargar imágenes de la galería dinámicamente
        $galleryImages = $this->getGalleryImages();
        
        $data = [
            'title' => 'Galería',
            'description' => 'Galería de imágenes de la Filá Mariscales',
            'gallery_images' => $galleryImages
        ];
        $this->view('pages/galeria', $data);
    }

    // Página de hermanamientos
    public function hermanamientos() {
        $data = [
            'title' => 'Hermanamientos',
            'description' => 'Nuestras relaciones con otras filás y entidades'
        ];
        $this->view('pages/hermanamientos', $data);
    }

    // Página del libro de la filá
    public function libro() {
        $data = [
            'title' => 'Libro de la Filá',
            'description' => 'Historia y anécdotas de la Filá Mariscales'
        ];
        $this->view('pages/libro', $data);
    }

    // Página de música
    public function musica() {
        $data = [
            'title' => 'Himno y Música',
            'description' => 'Escucha nuestro himno y otras piezas musicales de la Filá Mariscales'
        ];
        $this->view('pages/musica', $data);
    }

    // Página de noticias
    public function noticias() {
        $data = [
            'title' => 'Noticias',
            'description' => 'Últimas noticias y actualizaciones de la Filá Mariscales'
        ];
        $this->view('pages/noticias', $data);
    }

    // Página de patrocinadores
    public function patrocinadores() {
        $data = [
            'title' => 'Patrocinadores',
            'description' => 'Nuestros patrocinadores y colaboradores'
        ];
        $this->view('pages/patrocinadores', $data);
    }

    // Página de socios
    public function socios() {
        $data = [
            'title' => 'Zona de Socios',
            'description' => 'Área exclusiva para socios de la Filá Mariscales'
        ];
        $this->view('pages/socios', $data);
    }

    // Página de tienda
    public function tienda() {
        $data = [
            'title' => 'Tienda Online',
            'description' => 'Compra los artículos oficiales de la Filá Mariscales'
        ];
        $this->view('pages/tienda', $data);
    }

    // Página de contacto
    public function contacto() {
        $data = [
            'title' => 'Contacto',
            'description' => 'Ponte en contacto con la Filá Mariscales de Caballeros Templarios de Elche'
        ];
        $this->view('pages/contacto', $data);
    }

    // Página de historia
    public function historia() {
        $data = [
            'title' => 'Historia',
            'description' => 'Descubre la rica tradición y el legado de los Caballeros Templarios de Elche'
        ];
        $this->view('pages/historia', $data);
    }

    // Login page
    public function login() {
        // If already logged in, redirect to dashboard
        if (isLoggedIn()) {
            $this->redirect('/zona-privada');
        }

        $data = [
            'title' => 'Iniciar Sesión',
            'email' => '',
            'password' => '',
            'email_err' => '',
            'password_err' => ''
        ];

        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'title' => 'Iniciar Sesión',
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => ''
            ];

            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Por favor ingrese su email';
            }

            // Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Por favor ingrese su contraseña';
            }

            // Check for user/email
            if ($this->userModel->findUserByEmail($data['email'])) {
                // User found
            } else {
                // User not found
                $data['email_err'] = 'No se encontró ningún usuario con ese email';
            }

            // Make sure errors are empty
            if (empty($data['email_err']) && empty($data['password_err'])) {
                // Validated
                // Check and set logged in user
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);

                if ($loggedInUser) {
                    // Create session
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'Contraseña incorrecta';
                    $this->view('auth/login', $data);
                }
            } else {
                // Load view with errors
                $this->view('auth/login', $data);
            }
        } else {
            // Init data
            $data = [
                'title' => 'Iniciar Sesión',
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => ''
            ];

            // Load view
            $this->view('auth/login', $data);
        }
    }

    // Register page
    public function registro() {
        // If already logged in, redirect to dashboard
        if (isLoggedIn()) {
            $this->redirect('/zona-privada');
        }

        $data = [
            'title' => 'Registro',
            'nombre' => '',
            'apellidos' => '',
            'email' => '',
            'password' => '',
            'confirm_password' => '',
            'nombre_err' => '',
            'apellidos_err' => '',
            'email_err' => '',
            'password_err' => '',
            'confirm_password_err' => ''
        ];

        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'title' => 'Registro',
                'nombre' => trim($_POST['nombre']),
                'apellidos' => trim($_POST['apellidos']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'nombre_err' => '',
                'apellidos_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Validate Nombre
            if (empty($data['nombre'])) {
                $data['nombre_err'] = 'Por favor ingrese su nombre';
            }

            // Validate Apellidos
            if (empty($data['apellidos'])) {
                $data['apellidos_err'] = 'Por favor ingrese sus apellidos';
            }

            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Por favor ingrese su email';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Por favor ingrese un email válido';
            } else {
                // Check if email exists
                if ($this->userModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'El email ya está registrado';
                }
            }

            // Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Por favor ingrese una contraseña';
            } elseif (strlen($data['password']) < 6) {
                $data['password_err'] = 'La contraseña debe tener al menos 6 caracteres';
            }

            // Validate Confirm Password
            if (empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Por favor confirme su contraseña';
            } else {
                if ($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Las contraseñas no coinciden';
                }
            }

            // Make sure errors are empty
            if (empty($data['nombre_err']) && empty($data['apellidos_err']) && 
                empty($data['email_err']) && empty($data['password_err']) && 
                empty($data['confirm_password_err'])) {
                // Validated
                
                // Hash Password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                
                // Register User
                if ($this->userModel->register($data)) {
                    setFlashMessage('success', 'Registro exitoso. Por favor inicie sesión.');
                    $this->redirect('/login');
                } else {
                    die('Algo salió mal');
                }
            } else {
                // Load view with errors
                $this->view('auth/register', $data);
            }
        } else {
            // Init data
            $data = [
                'title' => 'Registro',
                'nombre' => '',
                'apellidos' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'nombre_err' => '',
                'apellidos_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Load view
            $this->view('auth/register', $data);
        }
    }

    // Create user session
    public function createUserSession($user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->nombre . ' ' . $user->apellidos;
        $_SESSION['user_role'] = $user->rol;
        
        if ($user->rol === 'admin') {
            $this->redirect('/admin');
        } else {
            $this->redirect('/zona-privada');
        }
    }

    // Logout
    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_role']);
        session_destroy();
        $this->redirect('/');
    }

    // Private area
    public function zonaPrivada() {
        $this->requireLogin();
        
        $data = [
            'title' => 'Zona Privada',
            'description' => 'Área privada de miembros de la Filá Mariscales'
        ];
        $this->view('zona-privada', $data);
    }

    // Admin dashboard
    public function admin() {
        // Redirect to admin controller
        $this->redirect('/admin');
    }

    // Página interactiva
    public function interactiva() {
        $data = [
            'title' => 'Zona Interactiva',
            'description' => 'Descubre la tradición templaria de forma interactiva'
        ];
        $this->view('pages/interactiva', $data);
    }

    // 404 page
    public function notFound() {
        $data = [
            'title' => 'Página no encontrada',
            'description' => 'La página que buscas no existe'
        ];
        $this->view('404', $data);
    }

    // Método para obtener eventos próximos
    private function getUpcomingEvents() {
        if ($this->eventModel) {
            try {
                $events = $this->eventModel->getRecentEvents(3);
                $formattedEvents = [];
                
                foreach ($events as $event) {
                    $formattedEvents[] = [
                        'title' => $event->titulo,
                        'description' => $event->descripcion ?? 'Evento de la Filá Mariscales',
                        'date' => $event->fecha,
                        'time' => $event->hora,
                        'location' => $event->lugar ?? 'Por determinar',
                        'status' => $event->es_publico ? 'Confirmado' : 'Próximamente',
                        'image' => !empty($event->imagen_url) ? '/' . $event->imagen_url : 'https://via.placeholder.com/400x300/8B4513/FFFFFF?text=Evento'
                    ];
                }
                
                return $formattedEvents;
            } catch (Exception $e) {
                // Si hay error, usar eventos por defecto
            }
        }
        
        // Eventos por defecto si no hay modelo o hay error
        return [
            [
                'title' => 'Presentación de la Filá',
                'description' => 'Presentación oficial de la Filá Mariscales para las fiestas 2024',
                'date' => '2024-10-15',
                'time' => '20:00',
                'location' => 'Sede Social',
                'status' => 'Próximamente',
                'image' => 'https://via.placeholder.com/400x300/8B4513/FFFFFF?text=Evento+1'
            ],
            [
                'title' => 'Cena de Hermandad',
                'description' => 'Cena de hermandad para todos los miembros de la filá',
                'date' => '2024-10-20',
                'time' => '21:00',
                'location' => 'Restaurante El Rincón',
                'status' => 'Confirmado',
                'image' => 'https://via.placeholder.com/400x300/8B4513/FFFFFF?text=Evento+2'
            ],
            [
                'title' => 'Ensayo General',
                'description' => 'Ensayo general del desfile de Moros y Cristianos',
                'date' => '2024-10-25',
                'time' => '18:00',
                'location' => 'Punto de encuentro: Ayuntamiento',
                'status' => 'Próximamente',
                'image' => 'https://via.placeholder.com/400x300/8B4513/FFFFFF?text=Evento+3'
            ]
        ];
    }

    // Método para obtener todos los eventos
    private function getAllEvents() {
        if ($this->eventModel) {
            try {
                $events = $this->eventModel->getAllEvents(1, 50); // Obtener hasta 50 eventos
                return $events;
            } catch (Exception $e) {
                // Si hay error, devolver array vacío
            }
        }
        
        return [];
    }

    // Método para obtener imágenes del carrusel
    private function getCarouselImages() {
        $uploadDir = 'uploads/carousel/';
        $images = [];
        
        if (is_dir($uploadDir)) {
            $files = glob($uploadDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
            foreach ($files as $file) {
                if (is_file($file)) {
                    $fileInfo = pathinfo($file);
                    $images[] = [
                        'path' => $file,
                        'name' => $fileInfo['basename'],
                        'url' => $this->getImageUrl($file)
                    ];
                }
            }
        }
        
        // Si no hay imágenes subidas, usar imágenes por defecto
        if (empty($images)) {
            $images = [
                [
                    'path' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80',
                    'name' => 'Caballeros Templarios',
                    'url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80'
                ],
                [
                    'path' => 'https://images.unsplash.com/photo-1518709268805-4e9042af2176?ixlib=rb-4.0.3&auto=format&fit=crop&w=2025&q=80',
                    'name' => 'Desfile Medieval',
                    'url' => 'https://images.unsplash.com/photo-1518709268805-4e9042af2176?ixlib=rb-4.0.3&auto=format&fit=crop&w=2025&q=80'
                ],
                [
                    'path' => 'https://images.unsplash.com/photo-1544966503-7cc5ac882d5f?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80',
                    'name' => 'Castillo Medieval',
                    'url' => 'https://images.unsplash.com/photo-1544966503-7cc5ac882d5f?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80'
                ]
            ];
        }
        
        return $images;
    }

    // Método para obtener imágenes de la galería
    private function getGalleryImages() {
        $uploadDir = 'uploads/gallery/';
        $images = [];
        
        if (is_dir($uploadDir)) {
            $files = glob($uploadDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
            foreach ($files as $file) {
                if (is_file($file)) {
                    $fileInfo = pathinfo($file);
                    $images[] = [
                        'thumb' => $this->getImageUrl($file),
                        'full' => $this->getImageUrl($file),
                        'caption' => 'Imagen de la Filá Mariscales',
                        'alt' => 'Galería Filá Mariscales',
                        'name' => $fileInfo['basename']
                    ];
                }
            }
        }
        
        // Si no hay imágenes subidas, usar imágenes por defecto
        if (empty($images)) {
            $images = [
                [
                    'thumb' => 'https://via.placeholder.com/300x200/8B4513/FFFFFF?text=Galería+1',
                    'full' => 'https://via.placeholder.com/800x600/8B4513/FFFFFF?text=Galería+1',
                    'caption' => 'Desfile de Moros y Cristianos 2023',
                    'alt' => 'Desfile de Moros y Cristianos'
                ],
                [
                    'thumb' => 'https://via.placeholder.com/300x200/8B4513/FFFFFF?text=Galería+2',
                    'full' => 'https://via.placeholder.com/800x600/8B4513/FFFFFF?text=Galería+2',
                    'caption' => 'Cena de Hermandad',
                    'alt' => 'Cena de Hermandad'
                ],
                [
                    'thumb' => 'https://via.placeholder.com/300x200/8B4513/FFFFFF?text=Galería+3',
                    'full' => 'https://via.placeholder.com/800x600/8B4513/FFFFFF?text=Galería+3',
                    'caption' => 'Presentación de la Filá',
                    'alt' => 'Presentación de la Filá'
                ]
            ];
        }
        
        return $images;
    }

    // Método para generar URLs de imágenes
    private function getImageUrl($filePath) {
        // Si es una URL externa, devolverla tal como está
        if (strpos($filePath, 'http') === 0) {
            return $filePath;
        }
        
        // Verificar si el acceso directo funciona
        $directUrl = '/' . $filePath;
        
        // Por ahora, usar el script servidor para asegurar que funcione
        return '/prueba-php/public/serve-image.php?path=' . urlencode($filePath);
    }
}
