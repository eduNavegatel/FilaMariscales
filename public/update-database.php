<?php
// Script simple para actualizar la base de datos
echo "<h1>🔧 Actualización de Base de Datos</h1>";

try {
    // Configuración de base de datos
    $host = 'localhost';
    $dbname = 'mariscales_db';
    $username = 'root';
    $password = '';
    
    // Conectar a la base de datos
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p>✅ Conexión a la base de datos exitosa</p>";
    
    // 1. Verificar si la columna activo existe
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'activo'");
    $activoExists = $stmt->rowCount() > 0;
    
    if (!$activoExists) {
        // Agregar columna activo
        $pdo->exec("ALTER TABLE users ADD COLUMN activo TINYINT(1) DEFAULT 1 AFTER rol");
        echo "<p>✅ Columna 'activo' agregada correctamente</p>";
    } else {
        echo "<p>ℹ️ Columna 'activo' ya existe</p>";
    }
    
    // 2. Verificar y actualizar el ENUM del campo rol
    $stmt = $pdo->query("SHOW COLUMNS FROM users WHERE Field = 'rol'");
    $rolColumn = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (strpos($rolColumn['Type'], 'socio') === false) {
        // Actualizar ENUM para incluir 'socio'
        $pdo->exec("ALTER TABLE users MODIFY COLUMN rol ENUM('admin', 'user', 'socio') DEFAULT 'user'");
        echo "<p>✅ Campo 'rol' actualizado para incluir 'socio'</p>";
    } else {
        echo "<p>ℹ️ Campo 'rol' ya incluye 'socio'</p>";
    }
    
    // 3. Actualizar usuarios existentes
    $stmt = $pdo->prepare("UPDATE users SET activo = 1 WHERE activo IS NULL");
    $stmt->execute();
    $affected = $stmt->rowCount();
    echo "<p>✅ Actualizados $affected usuarios con activo = 1</p>";
    
    // 4. Mostrar estructura final
    echo "<h3>📋 Estructura final de la tabla users:</h3>";
    $stmt = $pdo->query("DESCRIBE users");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr style='background: #f0f0f0;'><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Llave</th><th>Por defecto</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td><strong>" . $column['Field'] . "</strong></td>";
        echo "<td>" . $column['Type'] . "</td>";
        echo "<td>" . $column['Null'] . "</td>";
        echo "<td>" . $column['Key'] . "</td>";
        echo "<td>" . ($column['Default'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // 5. Mostrar usuarios existentes
    echo "<h3>👥 Usuarios existentes:</h3>";
    $stmt = $pdo->query("SELECT id, nombre, apellidos, email, rol, activo FROM users ORDER BY id");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($users) > 0) {
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr style='background: #f0f0f0;'><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Activo</th></tr>";
        foreach ($users as $user) {
            $rolBadge = match($user['rol']) {
                'admin' => '<span style="background: #dc3545; color: white; padding: 2px 6px; border-radius: 3px;">Admin</span>',
                'socio' => '<span style="background: #007bff; color: white; padding: 2px 6px; border-radius: 3px;">Socio</span>',
                'user' => '<span style="background: #6c757d; color: white; padding: 2px 6px; border-radius: 3px;">Usuario</span>',
                default => '<span style="background: #ffc107; color: black; padding: 2px 6px; border-radius: 3px;">' . htmlspecialchars($user['rol']) . '</span>'
            };
            
            echo "<tr>";
            echo "<td>" . $user['id'] . "</td>";
            echo "<td>" . htmlspecialchars($user['nombre'] . ' ' . $user['apellidos']) . "</td>";
            echo "<td>" . htmlspecialchars($user['email']) . "</td>";
            echo "<td>$rolBadge</td>";
            echo "<td>" . ($user['activo'] ? '✅ Sí' : '❌ No') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No hay usuarios en la base de datos</p>";
    }
    
    echo "<h2 style='color: green;'>✅ Actualización completada exitosamente</h2>";
    echo "<p>La base de datos ahora está lista para el modal de edición de usuarios.</p>";
    
    // Enlaces útiles
    echo "<hr>";
    echo "<h3>🔗 Enlaces útiles:</h3>";
    echo "<p><a href='/prueba-php/public/admin/usuarios' target='_blank'>🚀 Ir a Gestión de Usuarios</a></p>";
    echo "<p><a href='/prueba-php/public/admin/dashboard' target='_blank'>🏠 Ir al Dashboard</a></p>";
    
} catch (PDOException $e) {
    echo "<h2 style='color: red;'>❌ Error de conexión a la base de datos</h2>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<h3>🔧 Solución:</h3>";
    echo "<ul>";
    echo "<li>Verificar que XAMPP esté ejecutándose</li>";
    echo "<li>Verificar que MySQL esté activo</li>";
    echo "<li>Verificar que la base de datos 'mariscales_db' exista</li>";
    echo "<li>Verificar las credenciales de conexión</li>";
    echo "</ul>";
    
    echo "<h3>📋 Verificar configuración:</h3>";
    echo "<p>Host: $host</p>";
    echo "<p>Base de datos: $dbname</p>";
    echo "<p>Usuario: $username</p>";
    echo "<p>Contraseña: " . ($password ? 'Configurada' : 'Vacía') . "</p>";
}
?>



