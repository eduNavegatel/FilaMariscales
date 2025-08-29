<?php

class ApiController extends Controller {
    private $security;
    private $logger;
    private $cache;
    
    public function __construct() {
        parent::__construct();
        $this->security = SecurityHelper::getInstance();
        $this->logger = LogHelper::getInstance();
        $this->cache = CacheHelper::getInstance();
        
        // Configurar headers para API
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-API-Key');
    }
    
    /**
     * Autenticación de API
     */
    private function authenticateApi() {
        $apiKey = $_SERVER['HTTP_X_API_KEY'] ?? $_GET['api_key'] ?? null;
        
        if (!$apiKey) {
            $this->sendError('API key requerida', 401);
        }
        
        // Validar API key (implementar según tu lógica)
        $validKeys = ['test_key_123', 'prod_key_456']; // Mover a configuración
        
        if (!in_array($apiKey, $validKeys)) {
            $this->logger->warning('API key inválida', ['api_key' => $apiKey]);
            $this->sendError('API key inválida', 401);
        }
    }
    
    /**
     * Envía respuesta de error
     */
    private function sendError($message, $code = 400) {
        http_response_code($code);
        echo json_encode([
            'success' => false,
            'error' => $message,
            'timestamp' => date('c')
        ]);
        exit;
    }
    
    /**
     * Envía respuesta exitosa
     */
    private function sendSuccess($data, $code = 200) {
        http_response_code($code);
        echo json_encode([
            'success' => true,
            'data' => $data,
            'timestamp' => date('c')
        ]);
    }
    
    /**
     * GET /api/events - Obtener eventos
     */
    public function getEvents() {
        $this->authenticateApi();
        
        try {
            $events = $this->cache->remember('api_events', function() {
                $eventModel = new Event();
                return $eventModel->getAllEvents();
            }, 1800); // 30 minutos
            
            $this->sendSuccess($events);
            
        } catch (Exception $e) {
            $this->logger->error('Error obteniendo eventos API', ['error' => $e->getMessage()]);
            $this->sendError('Error interno del servidor', 500);
        }
    }
    
    /**
     * GET /api/events/{id} - Obtener evento específico
     */
    public function getEvent($id) {
        $this->authenticateApi();
        
        try {
            $event = $this->cache->remember("api_event_{$id}", function() use ($id) {
                $eventModel = new Event();
                return $eventModel->getEventById($id);
            }, 3600); // 1 hora
            
            if (!$event) {
                $this->sendError('Evento no encontrado', 404);
            }
            
            $this->sendSuccess($event);
            
        } catch (Exception $e) {
            $this->logger->error('Error obteniendo evento API', ['error' => $e->getMessage(), 'id' => $id]);
            $this->sendError('Error interno del servidor', 500);
        }
    }
    
    /**
     * POST /api/events - Crear evento
     */
    public function createEvent() {
        $this->authenticateApi();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendError('Método no permitido', 405);
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            $this->sendError('Datos JSON inválidos');
        }
        
        // Validar datos requeridos
        $required = ['title', 'description', 'event_date'];
        foreach ($required as $field) {
            if (empty($input[$field])) {
                $this->sendError("Campo requerido: {$field}");
            }
        }
        
        try {
            $eventModel = new Event();
            $eventId = $eventModel->createEvent([
                'title' => $this->security->sanitizeInput($input['title']),
                'description' => $this->security->sanitizeInput($input['description']),
                'event_date' => $input['event_date'],
                'location' => $this->security->sanitizeInput($input['location'] ?? ''),
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
            // Limpiar caché
            $this->cache->delete('api_events');
            
            $this->logger->info('Evento creado vía API', ['event_id' => $eventId]);
            $this->sendSuccess(['id' => $eventId], 201);
            
        } catch (Exception $e) {
            $this->logger->error('Error creando evento API', ['error' => $e->getMessage()]);
            $this->sendError('Error interno del servidor', 500);
        }
    }
    
    /**
     * PUT /api/events/{id} - Actualizar evento
     */
    public function updateEvent($id) {
        $this->authenticateApi();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
            $this->sendError('Método no permitido', 405);
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            $this->sendError('Datos JSON inválidos');
        }
        
        try {
            $eventModel = new Event();
            $event = $eventModel->getEventById($id);
            
            if (!$event) {
                $this->sendError('Evento no encontrado', 404);
            }
            
            $updateData = [];
            $allowedFields = ['title', 'description', 'event_date', 'location'];
            
            foreach ($allowedFields as $field) {
                if (isset($input[$field])) {
                    $updateData[$field] = $this->security->sanitizeInput($input[$field]);
                }
            }
            
            if (empty($updateData)) {
                $this->sendError('No hay datos para actualizar');
            }
            
            $eventModel->updateEvent($id, $updateData);
            
            // Limpiar caché
            $this->cache->delete('api_events');
            $this->cache->delete("api_event_{$id}");
            
            $this->logger->info('Evento actualizado vía API', ['event_id' => $id]);
            $this->sendSuccess(['message' => 'Evento actualizado correctamente']);
            
        } catch (Exception $e) {
            $this->logger->error('Error actualizando evento API', ['error' => $e->getMessage(), 'id' => $id]);
            $this->sendError('Error interno del servidor', 500);
        }
    }
    
    /**
     * DELETE /api/events/{id} - Eliminar evento
     */
    public function deleteEvent($id) {
        $this->authenticateApi();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            $this->sendError('Método no permitido', 405);
        }
        
        try {
            $eventModel = new Event();
            $event = $eventModel->getEventById($id);
            
            if (!$event) {
                $this->sendError('Evento no encontrado', 404);
            }
            
            $eventModel->deleteEvent($id);
            
            // Limpiar caché
            $this->cache->delete('api_events');
            $this->cache->delete("api_event_{$id}");
            
            $this->logger->info('Evento eliminado vía API', ['event_id' => $id]);
            $this->sendSuccess(['message' => 'Evento eliminado correctamente']);
            
        } catch (Exception $e) {
            $this->logger->error('Error eliminando evento API', ['error' => $e->getMessage(), 'id' => $id]);
            $this->sendError('Error interno del servidor', 500);
        }
    }
    
    /**
     * GET /api/stats - Estadísticas generales
     */
    public function getStats() {
        $this->authenticateApi();
        
        try {
            $stats = $this->cache->remember('api_stats', function() {
                $eventModel = new Event();
                $userModel = new User();
                
                return [
                    'total_events' => $eventModel->getTotalEvents(),
                    'upcoming_events' => $eventModel->getUpcomingEvents(),
                    'total_users' => $userModel->getTotalUsers(),
                    'cache_stats' => $this->cache->getStats(),
                    'system_info' => [
                        'php_version' => PHP_VERSION,
                        'server_time' => date('c'),
                        'memory_usage' => memory_get_usage(true)
                    ]
                ];
            }, 300); // 5 minutos
            
            $this->sendSuccess($stats);
            
        } catch (Exception $e) {
            $this->logger->error('Error obteniendo estadísticas API', ['error' => $e->getMessage()]);
            $this->sendError('Error interno del servidor', 500);
        }
    }
    
    /**
     * OPTIONS - Manejar preflight requests
     */
    public function handleOptions() {
        http_response_code(200);
        exit;
    }
}


