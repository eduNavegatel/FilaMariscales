<?php

class SecurityHelper {
    private static $instance = null;
    private $config;
    
    private function __construct() {
        $this->config = require __DIR__ . '/../config/security.php';
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Genera un token CSRF
     */
    public function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes($this->config['csrf']['token_length']));
            $_SESSION['csrf_token_time'] = time();
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Valida un token CSRF
     */
    public function validateCSRFToken($token) {
        if (!isset($_SESSION['csrf_token']) || !isset($_SESSION['csrf_token_time'])) {
            return false;
        }
        
        // Verificar expiración
        if (time() - $_SESSION['csrf_token_time'] > $this->config['csrf']['expire_time']) {
            unset($_SESSION['csrf_token'], $_SESSION['csrf_token_time']);
            return false;
        }
        
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Sanitiza input de usuario
     */
    public function sanitizeInput($input, $type = 'string') {
        switch ($type) {
            case 'email':
                return filter_var(trim($input), FILTER_SANITIZE_EMAIL);
            case 'url':
                return filter_var(trim($input), FILTER_SANITIZE_URL);
            case 'int':
                return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
            case 'float':
                return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            case 'string':
            default:
                return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
        }
    }
    
    /**
     * Valida contraseña según políticas
     */
    public function validatePassword($password) {
        $errors = [];
        
        if (strlen($password) < $this->config['password']['min_length']) {
            $errors[] = "La contraseña debe tener al menos {$this->config['password']['min_length']} caracteres";
        }
        
        if ($this->config['password']['require_mixed_case'] && 
            (!preg_match('/[a-z]/', $password) || !preg_match('/[A-Z]/', $password))) {
            $errors[] = "La contraseña debe contener mayúsculas y minúsculas";
        }
        
        if ($this->config['password']['require_numbers'] && !preg_match('/[0-9]/', $password)) {
            $errors[] = "La contraseña debe contener al menos un número";
        }
        
        if ($this->config['password']['require_symbols'] && !preg_match('/[^a-zA-Z0-9]/', $password)) {
            $errors[] = "La contraseña debe contener al menos un símbolo";
        }
        
        return $errors;
    }
    
    /**
     * Rate limiting básico
     */
    public function checkRateLimit($action, $identifier = null) {
        if (!$this->config['rate_limit']['enabled']) {
            return true;
        }
        
        $identifier = $identifier ?: $_SERVER['REMOTE_ADDR'];
        $key = "rate_limit_{$action}_{$identifier}";
        
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = ['count' => 0, 'reset_time' => time() + 60];
        }
        
        // Reset si ha pasado el tiempo
        if (time() > $_SESSION[$key]['reset_time']) {
            $_SESSION[$key] = ['count' => 0, 'reset_time' => time() + 60];
        }
        
        $_SESSION[$key]['count']++;
        
        return $_SESSION[$key]['count'] <= $this->config['rate_limit']['requests_per_minute'];
    }
    
    /**
     * Log de seguridad
     */
    public function logSecurityEvent($event, $details = []) {
        if (!$this->config['logging']['enabled']) {
            return;
        }
        
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'event' => $event,
            'details' => $details,
            'user_id' => $_SESSION['user_id'] ?? null
        ];
        
        $logFile = __DIR__ . '/../../logs/security.log';
        $logDir = dirname($logFile);
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        file_put_contents($logFile, json_encode($logEntry) . "\n", FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Headers de seguridad
     */
    public function setSecurityHeaders() {
        foreach ($this->config['headers'] as $header => $value) {
            $headerName = str_replace('_', '-', $header);
            header("$headerName: $value");
        }
    }
    
    /**
     * Configuración de sesión segura
     */
    public function configureSecureSession() {
        $config = $this->config['session'];
        
        ini_set('session.cookie_httponly', $config['http_only'] ? 1 : 0);
        ini_set('session.cookie_secure', $config['secure_cookies'] ? 1 : 0);
        ini_set('session.cookie_samesite', $config['same_site']);
        ini_set('session.gc_maxlifetime', $config['lifetime']);
        
        if ($config['regenerate_id'] && !isset($_SESSION['last_regeneration'])) {
            session_regenerate_id(true);
            $_SESSION['last_regeneration'] = time();
        }
    }
}
