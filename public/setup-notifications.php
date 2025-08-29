<?php
/**
 * Script para configurar el sistema de notificaciones
 * Ejecutar: php public/setup-notifications.php
 */

// Cargar configuración
require_once 'src/config/config.php';
require_once 'src/config/Database.php';

echo "=== CONFIGURACIÓN DEL SISTEMA DE NOTIFICACIONES ===\n\n";

try {
    // Crear conexión PDO directamente
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Conexión a la base de datos establecida\n";
    
    // Leer el archivo SQL de notificaciones
    $sqlFile = 'database/notifications.sql';
    
    if (!file_exists($sqlFile)) {
        throw new Exception("Archivo SQL no encontrado: $sqlFile");
    }
    
    $sql = file_get_contents($sqlFile);
    
    // Dividir el SQL en consultas individuales y filtrar comentarios
    $lines = explode("\n", $sql);
    $queries = [];
    $currentQuery = '';
    
    foreach ($lines as $line) {
        $line = trim($line);
        
        // Saltar comentarios y líneas vacías
        if (empty($line) || strpos($line, '--') === 0) {
            continue;
        }
        
        $currentQuery .= $line . ' ';
        
        // Si la línea termina con ;, es el final de una consulta
        if (substr($line, -1) === ';') {
            $queries[] = trim($currentQuery);
            $currentQuery = '';
        }
    }
    
    echo "📋 Ejecutando consultas SQL...\n\n";
    
    foreach ($queries as $query) {
        if (empty($query)) continue;
        
        echo "Ejecutando: " . substr($query, 0, 50) . "...\n";
        
        try {
            $result = $pdo->exec($query);
            
            if ($result !== false) {
                echo "✅ Consulta ejecutada correctamente\n";
            } else {
                echo "⚠️ Consulta ejecutada (puede que la tabla ya exista)\n";
            }
        } catch (Exception $e) {
            echo "⚠️ Error en consulta: " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    // Verificar que las tablas se crearon correctamente
    echo "🔍 Verificando tablas creadas...\n";
    
    $stmt = $pdo->query("SHOW TABLES LIKE 'system_notifications'");
    $notificationsTable = $stmt->fetch();
    
    $stmt = $pdo->query("SHOW TABLES LIKE 'email_logs'");
    $emailLogsTable = $stmt->fetch();
    
    if ($notificationsTable) {
        echo "✅ Tabla 'system_notifications' creada correctamente\n";
    } else {
        echo "❌ Error: Tabla 'system_notifications' no encontrada\n";
    }
    
    if ($emailLogsTable) {
        echo "✅ Tabla 'email_logs' creada correctamente\n";
    } else {
        echo "❌ Error: Tabla 'email_logs' no encontrada\n";
    }
    
    // Mostrar estructura de las tablas
    echo "\n📊 Estructura de la tabla system_notifications:\n";
    $stmt = $pdo->query("DESCRIBE system_notifications");
    $columns = $stmt->fetchAll();
    
    foreach ($columns as $column) {
        echo "  - {$column['Field']}: {$column['Type']} ({$column['Null']})\n";
    }
    
    echo "\n📊 Estructura de la tabla email_logs:\n";
    $stmt = $pdo->query("DESCRIBE email_logs");
    $columns = $stmt->fetchAll();
    
    foreach ($columns as $column) {
        echo "  - {$column['Field']}: {$column['Type']} ({$column['Null']})\n";
    }
    
    echo "\n🎉 ¡Configuración completada exitosamente!\n";
    echo "\n📝 Próximos pasos:\n";
    echo "1. Verificar que las clases EmailHelper y Notification estén cargadas\n";
    echo "2. Probar la creación de un usuario para verificar el envío de emails\n";
    echo "3. Revisar el archivo email_log.txt para ver los logs de emails\n";
    echo "4. Verificar las notificaciones en el panel de control\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
