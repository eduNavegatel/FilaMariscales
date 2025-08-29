<?php

class BackupHelper {
    private static $instance = null;
    private $backupDir;
    private $config;
    private $logger;
    
    private function __construct() {
        $this->backupDir = __DIR__ . '/../../backups/';
        $this->config = require __DIR__ . '/../config/config.php';
        $this->logger = LogHelper::getInstance();
        
        if (!is_dir($this->backupDir)) {
            mkdir($this->backupDir, 0755, true);
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Crea un backup completo de la base de datos
     */
    public function createBackup($type = 'full') {
        try {
            $timestamp = date('Y-m-d_H-i-s');
            $filename = "backup_{$type}_{$timestamp}.sql";
            $filepath = $this->backupDir . $filename;
            
            $dbConfig = $this->config['database'];
            
            // Comando mysqldump
            $command = sprintf(
                'mysqldump --host=%s --user=%s --password=%s %s > %s',
                escapeshellarg($dbConfig['host']),
                escapeshellarg($dbConfig['username']),
                escapeshellarg($dbConfig['password']),
                escapeshellarg($dbConfig['database']),
                escapeshellarg($filepath)
            );
            
            // Ejecutar backup
            $output = [];
            $returnCode = 0;
            exec($command, $output, $returnCode);
            
            if ($returnCode !== 0) {
                throw new Exception('Error ejecutando mysqldump: ' . implode("\n", $output));
            }
            
            // Comprimir el archivo
            $this->compressFile($filepath);
            
            // Limpiar backups antiguos
            $this->cleanOldBackups();
            
            $this->logger->info('Backup creado exitosamente', [
                'filename' => $filename,
                'type' => $type,
                'size' => filesize($filepath . '.gz')
            ]);
            
            return $filename . '.gz';
            
        } catch (Exception $e) {
            $this->logger->error('Error creando backup', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    
    /**
     * Crea backup de archivos importantes
     */
    public function createFileBackup() {
        try {
            $timestamp = date('Y-m-d_H-i-s');
            $filename = "files_backup_{$timestamp}.tar.gz";
            $filepath = $this->backupDir . $filename;
            
            $projectRoot = __DIR__ . '/../../';
            $excludePatterns = [
                'vendor/',
                'node_modules/',
                'cache/',
                'logs/',
                'backups/',
                '.git/',
                '*.log',
                '*.tmp'
            ];
            
            $excludeArgs = '';
            foreach ($excludePatterns as $pattern) {
                $excludeArgs .= " --exclude='" . escapeshellarg($pattern) . "'";
            }
            
            $command = sprintf(
                'tar -czf %s %s -C %s .',
                escapeshellarg($filepath),
                $excludeArgs,
                escapeshellarg($projectRoot)
            );
            
            $output = [];
            $returnCode = 0;
            exec($command, $output, $returnCode);
            
            if ($returnCode !== 0) {
                throw new Exception('Error creando backup de archivos: ' . implode("\n", $output));
            }
            
            $this->logger->info('Backup de archivos creado', [
                'filename' => $filename,
                'size' => filesize($filepath)
            ]);
            
            return $filename;
            
        } catch (Exception $e) {
            $this->logger->error('Error creando backup de archivos', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    
    /**
     * Restaura un backup
     */
    public function restoreBackup($filename) {
        try {
            $filepath = $this->backupDir . $filename;
            
            if (!file_exists($filepath)) {
                throw new Exception('Archivo de backup no encontrado');
            }
            
            $dbConfig = $this->config['database'];
            
            // Descomprimir si es necesario
            if (pathinfo($filepath, PATHINFO_EXTENSION) === 'gz') {
                $filepath = $this->decompressFile($filepath);
            }
            
            // Comando mysql para restaurar
            $command = sprintf(
                'mysql --host=%s --user=%s --password=%s %s < %s',
                escapeshellarg($dbConfig['host']),
                escapeshellarg($dbConfig['username']),
                escapeshellarg($dbConfig['password']),
                escapeshellarg($dbConfig['database']),
                escapeshellarg($filepath)
            );
            
            $output = [];
            $returnCode = 0;
            exec($command, $output, $returnCode);
            
            if ($returnCode !== 0) {
                throw new Exception('Error restaurando backup: ' . implode("\n", $output));
            }
            
            $this->logger->info('Backup restaurado exitosamente', ['filename' => $filename]);
            
            return true;
            
        } catch (Exception $e) {
            $this->logger->error('Error restaurando backup', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    
    /**
     * Comprime un archivo
     */
    private function compressFile($filepath) {
        $command = sprintf('gzip %s', escapeshellarg($filepath));
        exec($command);
    }
    
    /**
     * Descomprime un archivo
     */
    private function decompressFile($filepath) {
        $command = sprintf('gunzip -c %s > %s', escapeshellarg($filepath), escapeshellarg($filepath . '.tmp'));
        exec($command);
        return $filepath . '.tmp';
    }
    
    /**
     * Limpia backups antiguos
     */
    private function cleanOldBackups($maxAge = 30) {
        $files = glob($this->backupDir . '*.sql.gz');
        $deleted = 0;
        
        foreach ($files as $file) {
            if (filemtime($file) < time() - ($maxAge * 24 * 60 * 60)) {
                unlink($file);
                $deleted++;
            }
        }
        
        if ($deleted > 0) {
            $this->logger->info('Backups antiguos eliminados', ['count' => $deleted]);
        }
        
        return $deleted;
    }
    
    /**
     * Lista todos los backups disponibles
     */
    public function listBackups() {
        $files = glob($this->backupDir . '*.sql.gz');
        $backups = [];
        
        foreach ($files as $file) {
            $backups[] = [
                'filename' => basename($file),
                'size' => filesize($file),
                'created' => date('Y-m-d H:i:s', filemtime($file)),
                'type' => strpos(basename($file), 'files_backup') !== false ? 'files' : 'database'
            ];
        }
        
        // Ordenar por fecha de creación (más reciente primero)
        usort($backups, function($a, $b) {
            return strtotime($b['created']) - strtotime($a['created']);
        });
        
        return $backups;
    }
    
    /**
     * Programa un backup automático
     */
    public function scheduleBackup($type = 'full', $time = '02:00') {
        $cronJob = sprintf(
            '0 %s * * * cd %s && php -r "require_once \'vendor/autoload.php\'; BackupHelper::getInstance()->createBackup(\'%s\');" >> logs/backup.log 2>&1',
            $time,
            escapeshellarg(__DIR__ . '/../../'),
            $type
        );
        
        // Nota: Esto es solo un ejemplo. En producción, deberías usar un sistema de cron real
        $this->logger->info('Backup programado', [
            'type' => $type,
            'time' => $time,
            'cron_job' => $cronJob
        ]);
        
        return $cronJob;
    }
    
    /**
     * Verifica la integridad de un backup
     */
    public function verifyBackup($filename) {
        try {
            $filepath = $this->backupDir . $filename;
            
            if (!file_exists($filepath)) {
                return false;
            }
            
            // Verificar que el archivo no esté corrupto
            if (pathinfo($filepath, PATHINFO_EXTENSION) === 'gz') {
                $command = sprintf('gunzip -t %s', escapeshellarg($filepath));
                $returnCode = 0;
                exec($command, $output, $returnCode);
                
                return $returnCode === 0;
            }
            
            return true;
            
        } catch (Exception $e) {
            $this->logger->error('Error verificando backup', ['error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
     * Obtiene estadísticas de backups
     */
    public function getBackupStats() {
        $backups = $this->listBackups();
        $totalSize = 0;
        $dbBackups = 0;
        $fileBackups = 0;
        
        foreach ($backups as $backup) {
            $totalSize += $backup['size'];
            if ($backup['type'] === 'database') {
                $dbBackups++;
            } else {
                $fileBackups++;
            }
        }
        
        return [
            'total_backups' => count($backups),
            'database_backups' => $dbBackups,
            'file_backups' => $fileBackups,
            'total_size' => $totalSize,
            'oldest_backup' => !empty($backups) ? end($backups)['created'] : null,
            'newest_backup' => !empty($backups) ? $backups[0]['created'] : null
        ];
    }
}


