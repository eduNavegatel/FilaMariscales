<?php

class LogHelper {
    private static $instance = null;
    private $logDir;
    private $logLevel = 'info';
    
    private $levels = [
        'emergency' => 0,
        'alert'     => 1,
        'critical'  => 2,
        'error'     => 3,
        'warning'   => 4,
        'notice'    => 5,
        'info'      => 6,
        'debug'     => 7
    ];
    
    private function __construct() {
        $this->logDir = __DIR__ . '/../../logs/';
        if (!is_dir($this->logDir)) {
            mkdir($this->logDir, 0755, true);
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Establece el nivel de logging
     */
    public function setLogLevel($level) {
        if (isset($this->levels[$level])) {
            $this->logLevel = $level;
        }
    }
    
    /**
     * Registra un mensaje de log
     */
    public function log($level, $message, $context = []) {
        if ($this->levels[$level] > $this->levels[$this->logLevel]) {
            return;
        }
        
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'level' => strtoupper($level),
            'message' => $message,
            'context' => $context,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'user_id' => $_SESSION['user_id'] ?? null,
            'url' => $_SERVER['REQUEST_URI'] ?? 'unknown',
            'method' => $_SERVER['REQUEST_METHOD'] ?? 'GET'
        ];
        
        $logFile = $this->logDir . date('Y-m-d') . '.log';
        $logLine = json_encode($logEntry) . "\n";
        
        file_put_contents($logFile, $logLine, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Métodos de conveniencia para diferentes niveles
     */
    public function emergency($message, $context = []) {
        $this->log('emergency', $message, $context);
    }
    
    public function alert($message, $context = []) {
        $this->log('alert', $message, $context);
    }
    
    public function critical($message, $context = []) {
        $this->log('critical', $message, $context);
    }
    
    public function error($message, $context = []) {
        $this->log('error', $message, $context);
    }
    
    public function warning($message, $context = []) {
        $this->log('warning', $message, $context);
    }
    
    public function notice($message, $context = []) {
        $this->log('notice', $message, $context);
    }
    
    public function info($message, $context = []) {
        $this->log('info', $message, $context);
    }
    
    public function debug($message, $context = []) {
        $this->log('debug', $message, $context);
    }
    
    /**
     * Registra errores de PHP
     */
    public function logError($errno, $errstr, $errfile, $errline) {
        $context = [
            'file' => $errfile,
            'line' => $errline,
            'error_type' => $this->getErrorType($errno)
        ];
        
        $this->error($errstr, $context);
    }
    
    /**
     * Registra excepciones
     */
    public function logException($exception) {
        $context = [
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'code' => $exception->getCode()
        ];
        
        $this->error($exception->getMessage(), $context);
    }
    
    /**
     * Obtiene el tipo de error de PHP
     */
    private function getErrorType($errno) {
        switch ($errno) {
            case E_ERROR:
                return 'E_ERROR';
            case E_WARNING:
                return 'E_WARNING';
            case E_PARSE:
                return 'E_PARSE';
            case E_NOTICE:
                return 'E_NOTICE';
            case E_CORE_ERROR:
                return 'E_CORE_ERROR';
            case E_CORE_WARNING:
                return 'E_CORE_WARNING';
            case E_COMPILE_ERROR:
                return 'E_COMPILE_ERROR';
            case E_COMPILE_WARNING:
                return 'E_COMPILE_WARNING';
            case E_USER_ERROR:
                return 'E_USER_ERROR';
            case E_USER_WARNING:
                return 'E_USER_WARNING';
            case E_USER_NOTICE:
                return 'E_USER_NOTICE';
            case E_STRICT:
                return 'E_STRICT';
            case E_RECOVERABLE_ERROR:
                return 'E_RECOVERABLE_ERROR';
            case E_DEPRECATED:
                return 'E_DEPRECATED';
            case E_USER_DEPRECATED:
                return 'E_USER_DEPRECATED';
            default:
                return 'UNKNOWN';
        }
    }
    
    /**
     * Configura el manejo de errores de PHP
     */
    public function setupErrorHandling() {
        set_error_handler([$this, 'logError']);
        set_exception_handler([$this, 'logException']);
        
        // Registrar errores fatales al finalizar
        register_shutdown_function(function() {
            $error = error_get_last();
            if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
                $this->critical('Fatal Error: ' . $error['message'], [
                    'file' => $error['file'],
                    'line' => $error['line']
                ]);
            }
        });
    }
    
    /**
     * Obtiene logs de un día específico
     */
    public function getLogs($date = null, $level = null) {
        $date = $date ?: date('Y-m-d');
        $logFile = $this->logDir . $date . '.log';
        
        if (!file_exists($logFile)) {
            return [];
        }
        
        $logs = [];
        $lines = file($logFile, FILE_IGNORE_NEW_LINES);
        
        foreach ($lines as $line) {
            $logEntry = json_decode($line, true);
            if ($logEntry && (!$level || $logEntry['level'] === strtoupper($level))) {
                $logs[] = $logEntry;
            }
        }
        
        return $logs;
    }
    
    /**
     * Limpia logs antiguos
     */
    public function cleanOldLogs($days = 30) {
        $files = glob($this->logDir . '*.log');
        $deleted = 0;
        
        foreach ($files as $file) {
            if (filemtime($file) < time() - ($days * 24 * 60 * 60)) {
                unlink($file);
                $deleted++;
            }
        }
        
        return $deleted;
    }
    
    /**
     * Obtiene estadísticas de logs
     */
    public function getStats($days = 7) {
        $stats = [];
        
        for ($i = 0; $i < $days; $i++) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $logs = $this->getLogs($date);
            
            $levelCounts = [];
            foreach ($logs as $log) {
                $level = $log['level'];
                $levelCounts[$level] = ($levelCounts[$level] ?? 0) + 1;
            }
            
            $stats[$date] = $levelCounts;
        }
        
        return $stats;
    }
}



