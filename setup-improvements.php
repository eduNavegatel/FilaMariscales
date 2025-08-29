<?php
/**
 * Script de configuración de mejoras
 * Configura automáticamente todas las mejoras implementadas
 */

require_once 'vendor/autoload.php';

echo "🚀 Configurando mejoras del sistema...\n\n";

try {
    // 1. Crear directorios necesarios
    echo "📁 Creando directorios...\n";
    $directories = [
        'logs',
        'cache', 
        'backups',
        'uploads/temp'
    ];
    
    foreach ($directories as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
            echo "  ✅ Creado: {$dir}\n";
        } else {
            echo "  ℹ️  Ya existe: {$dir}\n";
        }
    }
    
    // 2. Configurar logging
    echo "\n📝 Configurando sistema de logging...\n";
    $logger = LogHelper::getInstance();
    $logger->setLogLevel('info');
    $logger->setupErrorHandling();
    echo "  ✅ Sistema de logging configurado\n";
    
    // 3. Configurar caché
    echo "\n⚡ Configurando sistema de caché...\n";
    $cache = CacheHelper::getInstance();
    $cache->setEnabled(true);
    echo "  ✅ Sistema de caché configurado\n";
    
    // 4. Configurar seguridad
    echo "\n🔒 Configurando sistema de seguridad...\n";
    $security = SecurityHelper::getInstance();
    echo "  ✅ Sistema de seguridad configurado\n";
    
    // 5. Crear backup inicial
    echo "\n💾 Creando backup inicial...\n";
    $backup = BackupHelper::getInstance();
    try {
        $backupFile = $backup->createBackup('initial');
        echo "  ✅ Backup inicial creado: {$backupFile}\n";
    } catch (Exception $e) {
        echo "  ⚠️  No se pudo crear backup inicial: " . $e->getMessage() . "\n";
    }
    
    // 6. Limpiar caché expirado
    echo "\n🧹 Limpiando caché expirado...\n";
    $cleared = $cache->clearExpired();
    echo "  ✅ Archivos de caché expirados eliminados: {$cleared}\n";
    
    // 7. Limpiar logs antiguos
    echo "\n📋 Limpiando logs antiguos...\n";
    $cleared = $logger->cleanOldLogs(30);
    echo "  ✅ Logs antiguos eliminados: {$cleared}\n";
    
    // 8. Verificar permisos de archivos
    echo "\n🔐 Verificando permisos...\n";
    $criticalFiles = [
        'src/config/config.php',
        'src/config/security.php',
        'logs',
        'cache',
        'backups'
    ];
    
    foreach ($criticalFiles as $file) {
        if (file_exists($file)) {
            $perms = substr(sprintf('%o', fileperms($file)), -4);
            echo "  📄 {$file}: {$perms}\n";
        }
    }
    
    // 9. Mostrar estadísticas iniciales
    echo "\n📊 Estadísticas del sistema:\n";
    $cacheStats = $cache->getStats();
    $backupStats = $backup->getBackupStats();
    
    echo "  💾 Caché: {$cacheStats['total_files']} archivos, " . 
         number_format($cacheStats['total_size'] / 1024, 2) . " KB\n";
    echo "  💿 Backups: {$backupStats['total_backups']} archivos, " . 
         number_format($backupStats['total_size'] / 1024 / 1024, 2) . " MB\n";
    
    // 10. Crear archivo de configuración de ejemplo para API
    echo "\n🔑 Configurando API...\n";
    $apiConfigFile = 'src/config/api_keys.php';
    if (!file_exists($apiConfigFile)) {
        $apiConfig = "<?php\n\nreturn [\n";
        $apiConfig .= "    'valid_keys' => [\n";
        $apiConfig .= "        'test_key_" . bin2hex(random_bytes(8)) . "',\n";
        $apiConfig .= "        'prod_key_" . bin2hex(random_bytes(8)) . "',\n";
        $apiConfig .= "    ],\n";
        $apiConfig .= "    'rate_limit' => [\n";
        $apiConfig .= "        'requests_per_minute' => 60,\n";
        $apiConfig .= "        'burst_limit' => 10\n";
        $apiConfig .= "    ]\n";
        $apiConfig .= "];\n";
        
        file_put_contents($apiConfigFile, $apiConfig);
        echo "  ✅ Archivo de configuración de API creado\n";
    } else {
        echo "  ℹ️  Archivo de configuración de API ya existe\n";
    }
    
    // 11. Crear archivo .htaccess para seguridad adicional
    echo "\n🛡️ Configurando seguridad adicional...\n";
    $htaccessContent = "# Configuración de seguridad adicional\n";
    $htaccessContent .= "RewriteEngine On\n\n";
    $htaccessContent .= "# Proteger archivos sensibles\n";
    $htaccessContent .= "<Files \"*.php\">\n";
    $htaccessContent .= "    Order Allow,Deny\n";
    $htaccessContent .= "    Allow from all\n";
    $htaccessContent .= "</Files>\n\n";
    $htaccessContent .= "# Denegar acceso a archivos de configuración\n";
    $htaccessContent .= "<FilesMatch \"^(config|security|api_keys)\\.php$\">\n";
    $htaccessContent .= "    Order Allow,Deny\n";
    $htaccessContent .= "    Deny from all\n";
    $htaccessContent .= "</FilesMatch>\n\n";
    $htaccessContent .= "# Proteger directorios sensibles\n";
    $htaccessContent .= "RewriteRule ^(logs|cache|backups)/ - [F,L]\n\n";
    $htaccessContent .= "# Headers de seguridad\n";
    $htaccessContent .= "<IfModule mod_headers.c>\n";
    $htaccessContent .= "    Header always set X-Content-Type-Options nosniff\n";
    $htaccessContent .= "    Header always set X-Frame-Options DENY\n";
    $htaccessContent .= "    Header always set X-XSS-Protection \"1; mode=block\"\n";
    $htaccessContent .= "</IfModule>\n";
    
    file_put_contents('src/.htaccess', $htaccessContent);
    echo "  ✅ Archivo .htaccess de seguridad creado\n";
    
    // 12. Crear script de mantenimiento
    echo "\n🔧 Creando script de mantenimiento...\n";
    $maintenanceScript = "#!/usr/bin/env php\n<?php\n";
    $maintenanceScript .= "require_once 'vendor/autoload.php';\n\n";
    $maintenanceScript .= "echo \"🧹 Ejecutando mantenimiento del sistema...\\n\";\n\n";
    $maintenanceScript .= "// Limpiar caché expirado\n";
    $maintenanceScript .= "\$cache = CacheHelper::getInstance();\n";
    $maintenanceScript .= "\$cleared = \$cache->clearExpired();\n";
    $maintenanceScript .= "echo \"📦 Caché expirado eliminado: {\$cleared} archivos\\n\";\n\n";
    $maintenanceScript .= "// Limpiar logs antiguos\n";
    $maintenanceScript .= "\$logger = LogHelper::getInstance();\n";
    $maintenanceScript .= "\$cleared = \$logger->cleanOldLogs(30);\n";
    $maintenanceScript .= "echo \"📋 Logs antiguos eliminados: {\$cleared} archivos\\n\";\n\n";
    $maintenanceScript .= "// Crear backup diario\n";
    $maintenanceScript .= "try {\n";
    $maintenanceScript .= "    \$backup = BackupHelper::getInstance();\n";
    $maintenanceScript .= "    \$backupFile = \$backup->createBackup('daily');\n";
    $maintenanceScript .= "    echo \"💾 Backup diario creado: {\$backupFile}\\n\";\n";
    $maintenanceScript .= "} catch (Exception \$e) {\n";
    $maintenanceScript .= "    echo \"❌ Error creando backup: \" . \$e->getMessage() . \"\\n\";\n";
    $maintenanceScript .= "}\n\n";
    $maintenanceScript .= "echo \"✅ Mantenimiento completado\\n\";\n";
    
    file_put_contents('maintenance.php', $maintenanceScript);
    chmod('maintenance.php', 0755);
    echo "  ✅ Script de mantenimiento creado\n";
    
    echo "\n🎉 ¡Configuración completada exitosamente!\n\n";
    echo "📋 Resumen de mejoras implementadas:\n";
    echo "  ✅ Sistema de seguridad mejorado con CSRF, rate limiting y sanitización\n";
    echo "  ✅ Sistema de caché para mejorar performance\n";
    echo "  ✅ Sistema de logging estructurado\n";
    echo "  ✅ API REST completa para integraciones externas\n";
    echo "  ✅ Sistema de backup automático\n";
    echo "  ✅ Middleware de seguridad global\n";
    echo "  ✅ Script de mantenimiento automático\n\n";
    
    echo "🚀 Próximos pasos:\n";
    echo "  1. Configurar cron job para mantenimiento diario:\n";
    echo "     crontab -e\n";
    echo "     0 2 * * * /ruta/a/tu/proyecto/maintenance.php\n\n";
    echo "  2. Revisar y ajustar configuraciones en src/config/\n";
    echo "  3. Probar la API en /api/events\n";
    echo "  4. Verificar logs en /logs/\n\n";
    
    echo "🔗 Documentación de la API:\n";
    echo "  GET /api/events - Listar eventos\n";
    echo "  GET /api/events/{id} - Obtener evento específico\n";
    echo "  POST /api/events - Crear evento\n";
    echo "  PUT /api/events/{id} - Actualizar evento\n";
    echo "  DELETE /api/events/{id} - Eliminar evento\n";
    echo "  GET /api/stats - Estadísticas del sistema\n\n";
    
} catch (Exception $e) {
    echo "❌ Error durante la configuración: " . $e->getMessage() . "\n";
    exit(1);
}

