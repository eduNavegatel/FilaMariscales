<?php

class CacheHelper {
    private static $instance = null;
    private $cacheDir;
    private $enabled = true;
    
    private function __construct() {
        $this->cacheDir = __DIR__ . '/../../cache/';
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Genera una clave de caché única
     */
    private function generateKey($key, $params = []) {
        $paramsString = !empty($params) ? '_' . md5(serialize($params)) : '';
        return md5($key . $paramsString);
    }
    
    /**
     * Obtiene el contenido del caché
     */
    public function get($key, $params = []) {
        if (!$this->enabled) {
            return false;
        }
        
        $cacheKey = $this->generateKey($key, $params);
        $cacheFile = $this->cacheDir . $cacheKey . '.cache';
        
        if (!file_exists($cacheFile)) {
            return false;
        }
        
        $data = unserialize(file_get_contents($cacheFile));
        
        // Verificar expiración
        if (isset($data['expires']) && time() > $data['expires']) {
            unlink($cacheFile);
            return false;
        }
        
        return $data['content'] ?? false;
    }
    
    /**
     * Guarda contenido en el caché
     */
    public function set($key, $content, $ttl = 3600, $params = []) {
        if (!$this->enabled) {
            return false;
        }
        
        $cacheKey = $this->generateKey($key, $params);
        $cacheFile = $this->cacheDir . $cacheKey . '.cache';
        
        $data = [
            'content' => $content,
            'expires' => time() + $ttl,
            'created' => time()
        ];
        
        return file_put_contents($cacheFile, serialize($data), LOCK_EX) !== false;
    }
    
    /**
     * Elimina un elemento del caché
     */
    public function delete($key, $params = []) {
        $cacheKey = $this->generateKey($key, $params);
        $cacheFile = $this->cacheDir . $cacheKey . '.cache';
        
        if (file_exists($cacheFile)) {
            return unlink($cacheFile);
        }
        
        return true;
    }
    
    /**
     * Limpia todo el caché
     */
    public function clear() {
        $files = glob($this->cacheDir . '*.cache');
        foreach ($files as $file) {
            unlink($file);
        }
        return true;
    }
    
    /**
     * Limpia caché expirado
     */
    public function clearExpired() {
        $files = glob($this->cacheDir . '*.cache');
        $cleared = 0;
        
        foreach ($files as $file) {
            $data = unserialize(file_get_contents($file));
            if (isset($data['expires']) && time() > $data['expires']) {
                unlink($file);
                $cleared++;
            }
        }
        
        return $cleared;
    }
    
    /**
     * Habilita o deshabilita el caché
     */
    public function setEnabled($enabled) {
        $this->enabled = $enabled;
    }
    
    /**
     * Obtiene estadísticas del caché
     */
    public function getStats() {
        $files = glob($this->cacheDir . '*.cache');
        $totalSize = 0;
        $expiredCount = 0;
        $validCount = 0;
        
        foreach ($files as $file) {
            $totalSize += filesize($file);
            $data = unserialize(file_get_contents($file));
            
            if (isset($data['expires']) && time() > $data['expires']) {
                $expiredCount++;
            } else {
                $validCount++;
            }
        }
        
        return [
            'total_files' => count($files),
            'valid_files' => $validCount,
            'expired_files' => $expiredCount,
            'total_size' => $totalSize,
            'enabled' => $this->enabled
        ];
    }
    
    /**
     * Caché para consultas de base de datos
     */
    public function remember($key, $callback, $ttl = 3600, $params = []) {
        $cached = $this->get($key, $params);
        
        if ($cached !== false) {
            return $cached;
        }
        
        $result = $callback();
        $this->set($key, $result, $ttl, $params);
        
        return $result;
    }
    
    /**
     * Caché para vistas
     */
    public function rememberView($view, $data = [], $ttl = 1800) {
        $key = 'view_' . $view;
        return $this->remember($key, function() use ($view, $data) {
            ob_start();
            extract($data);
            include __DIR__ . '/../views/' . $view . '.php';
            return ob_get_clean();
        }, $ttl, $data);
    }
}


