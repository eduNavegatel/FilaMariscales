<?php
// Script para verificar la estructura de la base de datos
echo "<h1>🗄️ Verificación de Estructura de Base de Datos</h1>";

// Incluir configuración
require_once 'src/config/config.php';

echo "<h2>📋 Configuración de Base de Datos</h2>";
echo "<p><strong>DB_HOST:</strong> " . DB_HOST . "</p>";
echo "<p><strong>DB_USER:</strong> " . DB_USER . "</p>";
echo "<p><strong>DB_NAME:</strong> " . DB_NAME . "</p>";

// Conectar a la base de datos
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p>✅ Conexión a la base de datos exitosa</p>";
    
    // Verificar estructura de la tabla users
    echo "<h2>👥 Estructura de la Tabla 'users'</h2>";
    
    $stmt = $pdo->query("DESCRIBE users");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table class='table table-striped'>";
    echo "<thead><tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Clave</th><th>Por Defecto</th><th>Extra</th></tr></thead>";
    echo "<tbody>";
    
    $requiredFields = ['id', 'nombre', 'email', 'password', 'rol', 'activo'];
    $foundFields = [];
    
    foreach ($columns as $column) {
        $fieldName = $column['Field'];
        $foundFields[] = $fieldName;
        
        $rowClass = in_array($fieldName, $requiredFields) ? 'table-success' : '';
        
        echo "<tr class='$rowClass'>";
        echo "<td><strong>$fieldName</strong></td>";
        echo "<td>{$column['Type']}</td>";
        echo "<td>{$column['Null']}</td>";
        echo "<td>{$column['Key']}</td>";
        echo "<td>{$column['Default']}</td>";
        echo "<td>{$column['Extra']}</td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
    
    // Verificar campos requeridos
    echo "<h3>🔍 Verificación de Campos Requeridos</h3>";
    foreach ($requiredFields as $field) {
        if (in_array($field, $foundFields)) {
            echo "<p>✅ Campo <code>$field</code> existe</p>";
        } else {
            echo "<p>❌ Campo <code>$field</code> NO existe</p>";
        }
    }
    
    // Verificar datos de ejemplo
    echo "<h2>📊 Datos de Ejemplo en la Tabla 'users'</h2>";
    
    $stmt = $pdo->query("SELECT id, nombre, email, rol, activo, fecha_registro FROM users LIMIT 5");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($users) > 0) {
        echo "<table class='table table-striped'>";
        echo "<thead><tr><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Activo</th><th>Fecha Registro</th></tr></thead>";
        echo "<tbody>";
        
        foreach ($users as $user) {
            $activoStatus = $user['activo'] ? '✅ Activo' : '❌ Inactivo';
            $rolBadge = match($user['rol']) {
                'admin' => '<span class="badge bg-danger">Admin</span>',
                'socio' => '<span class="badge bg-primary">Socio</span>',
                'user' => '<span class="badge bg-secondary">Usuario</span>',
                default => '<span class="badge bg-warning">' . htmlspecialchars($user['rol']) . '</span>'
            };
            
            echo "<tr>";
            echo "<td>{$user['id']}</td>";
            echo "<td>" . htmlspecialchars($user['nombre']) . "</td>";
            echo "<td>" . htmlspecialchars($user['email']) . "</td>";
            echo "<td>$rolBadge</td>";
            echo "<td>$activoStatus</td>";
            echo "<td>{$user['fecha_registro']}</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>⚠️ No hay usuarios en la base de datos</p>";
    }
    
    // Verificar permisos de escritura
    echo "<h2>✍️ Verificación de Permisos de Escritura</h2>";
    
    try {
        // Intentar hacer una actualización de prueba
        $stmt = $pdo->prepare("UPDATE users SET ultimo_acceso = NOW() WHERE id = ? LIMIT 1");
        $stmt->execute([1]);
        echo "<p>✅ Permisos de escritura correctos</p>";
    } catch (Exception $e) {
        echo "<p>❌ Error de permisos de escritura: " . $e->getMessage() . "</p>";
    }
    
    // Verificar índices
    echo "<h2>🔍 Verificación de Índices</h2>";
    
    $stmt = $pdo->query("SHOW INDEX FROM users");
    $indexes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table class='table table-sm'>";
    echo "<thead><tr><th>Índice</th><th>Campo</th><th>Tipo</th></tr></thead>";
    echo "<tbody>";
    
    foreach ($indexes as $index) {
        echo "<tr>";
        echo "<td>{$index['Key_name']}</td>";
        echo "<td>{$index['Column_name']}</td>";
        echo "<td>{$index['Index_type']}</td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
    
} catch (PDOException $e) {
    echo "<p>❌ Error de conexión a la base de datos: " . $e->getMessage() . "</p>";
}

// Mostrar instrucciones de corrección
echo "<h2>🔧 Instrucciones de Corrección</h2>";
echo "<div style='background: #fff3cd; padding: 20px; border-radius: 8px; border-left: 4px solid #ffc107;'>";

echo "<h4>🚨 Si faltan campos en la tabla 'users':</h4>";
echo "<div style='background: #e7f3ff; padding: 15px; border-radius: 8px; font-family: monospace;'>";
echo "-- Agregar campo 'rol' si no existe<br>";
echo "ALTER TABLE users ADD COLUMN rol ENUM('user', 'socio', 'admin') DEFAULT 'user' AFTER password;<br><br>";
echo "-- Agregar campo 'activo' si no existe<br>";
echo "ALTER TABLE users ADD COLUMN activo TINYINT(1) DEFAULT 1 AFTER rol;<br><br>";
echo "-- Agregar campo 'fecha_registro' si no existe<br>";
echo "ALTER TABLE users ADD COLUMN fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER activo;<br><br>";
echo "-- Agregar campo 'ultimo_acceso' si no existe<br>";
echo "ALTER TABLE users ADD COLUMN ultimo_acceso TIMESTAMP NULL DEFAULT NULL AFTER fecha_registro;";
echo "</div>";

echo "<h4>🔍 Verificar que los campos tengan los tipos correctos:</h4>";
echo "<ul>";
echo "<li><strong>rol:</strong> ENUM('user', 'socio', 'admin') o VARCHAR(10)</li>";
echo "<li><strong>activo:</strong> TINYINT(1) o BOOLEAN</li>";
echo "<li><strong>fecha_registro:</strong> TIMESTAMP</li>";
echo "<li><strong>ultimo_acceso:</strong> TIMESTAMP NULL</li>";
echo "</ul>";

echo "</div>";

// Enlaces de prueba
echo "<h2>🔗 Enlaces de Prueba</h2>";
echo "<p><a href='" . URL_ROOT . "/admin/usuarios' class='btn btn-primary' target='_blank'>🚀 Ir a Gestión de Usuarios</a></p>";
echo "<p><a href='test-user-edit-fix.php' class='btn btn-secondary' target='_blank'>🔧 Verificar Edición de Usuarios</a></p>";

echo "<hr>";
echo "<p><em>Verificación de base de datos completada. Asegúrate de que todos los campos requeridos existan.</em></p>";
?>
