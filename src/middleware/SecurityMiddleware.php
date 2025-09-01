<?php

class SecurityMiddleware {
    private $security;
    
    public function __construct() {
        $this->security = SecurityHelper::getInstance();
    }
    
    /**
     * Aplica todas las medidas de seguridad
     */
    public function apply() {
        // Configurar sesión segura
        $this->security->configureSecureSession();
        
        // Establecer headers de seguridad
        $this->security->setSecurityHeaders();
        
        // Verificar rate limiting
        $this->checkRateLimit();
        
        // Log de la petición
        $this->logRequest();
    }
    
    /**
     * Verifica rate limiting para la petición actual
     */
    private function checkRateLimit() {
        $action = $this->getCurrentAction();
        
        if (!$this->security->checkRateLimit($action)) {
            $this->security->logSecurityEvent('rate_limit_exceeded', [
                'action' => $action,
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
            
            http_response_code(429);
            echo json_encode(['error' => 'Demasiadas peticiones. Intenta de nuevo en un momento.']);
            exit;
        }
    }
    
    /**
     * Obtiene la acción actual basada en la URL
     */
    private function getCurrentAction() {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        
        // Mapear rutas críticas a acciones específicas
        $criticalActions = [
            '/login' => 'login',
            '/register' => 'register',
            '/admin' => 'admin_access',
            '/api/' => 'api_access'
        ];
        
        foreach ($criticalActions as $pattern => $action) {
            if (strpos($path, $pattern) === 0) {
                return $action;
            }
        }
        
        return 'general_access';
    }
    
    /**
     * Registra la petición para auditoría
     */
    private function logRequest() {
        // Solo log para acciones críticas
        $criticalPaths = ['/login', '/register', '/admin', '/api/'];
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        
        foreach ($criticalPaths as $criticalPath) {
            if (strpos($path, $criticalPath) === 0) {
                $this->security->logSecurityEvent('critical_access', [
                    'path' => $path,
                    'method' => $_SERVER['REQUEST_METHOD'] ?? 'GET',
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
                ]);
                break;
            }
        }
    }
    
    /**
     * Valida CSRF para formularios POST
     */
    public function validateCSRF() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
            
            if (!$token || !$this->security->validateCSRFToken($token)) {
                $this->security->logSecurityEvent('csrf_validation_failed', [
                    'path' => $_SERVER['REQUEST_URI'] ?? '/',
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]);
                
                http_response_code(403);
                echo json_encode(['error' => 'Token CSRF inválido o expirado']);
                exit;
            }
        }
    }
    
    /**
     * Sanitiza todos los inputs de la petición
     */
    public function sanitizeInputs() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = $this->recursiveSanitize($_POST);
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $_GET = $this->recursiveSanitize($_GET);
        }
    }
    
    /**
     * Sanitiza recursivamente arrays
     */
    private function recursiveSanitize($data) {
        if (is_array($data)) {
            return array_map([$this, 'recursiveSanitize'], $data);
        }
        
        return $this->security->sanitizeInput($data);
    }
}



