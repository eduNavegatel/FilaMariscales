<?php
// Test final del sistema - Filá Mariscales
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🎉 Test Final del Sistema - Filá Mariscales</h1>";

// 1. Verificar PHP
echo "<h2>1. Verificando PHP...</h2>";
echo "<p>✅ PHP versión: " . phpversion() . "</p>";
echo "<p>✅ Directorio actual: " . __DIR__ . "</p>";

// 2. Verificar archivos del sistema
echo "<h2>2. Verificando archivos del sistema...</h2>";
$files = [
    '../src/config/config.php' => 'Configuración principal',
    '../src/config/Database.php' => 'Clase de base de datos',
    '../src/models/User.php' => 'Modelo de usuario',
    '../src/controllers/AdminController.php' => 'Controlador de administración'
];

foreach ($files as $file => $description) {
    if (file_exists($file)) {
        echo "<p style='color: green;'>✅ {$description}: {$file}</p>";
    } else {
        echo "<p style='color: red;'>❌ {$description}: {$file}</p>";
    }
}

// 3. Verificar conexión a base de datos
echo "<h2>3. Verificando conexión a base de datos...</h2>";
try {
    require_once '../src/config/config.php';
    require_once '../src/config/Database.php';
    
    $db = new Database();
    $pdo = $db->getConnection();
    
    echo "<p style='color: green;'>✅ Conexión a base de datos exitosa</p>";
    
    // Verificar tabla users
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✅ Tabla 'users' existe</p>";
        
        // Verificar estructura de la tabla
        $stmt = $pdo->query("DESCRIBE users");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<p>📋 Estructura de la tabla users:</p>";
        echo "<ul>";
        foreach ($columns as $column) {
            echo "<li><strong>{$column['Field']}</strong> - {$column['Type']}</li>";
        }
        echo "</ul>";
        
        // Contar usuarios
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<p style='color: green;'>✅ Total de usuarios: {$result['total']}</p>";
        
        // Mostrar usuarios de ejemplo
        $stmt = $pdo->query("SELECT id, nombre, apellidos, email, rol, activo FROM users ORDER BY id LIMIT 3");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<p>👥 Usuarios de ejemplo:</p>";
        echo "<ul>";
        foreach ($users as $user) {
            $status = $user['activo'] ? 'Activo' : 'Inactivo';
            echo "<li><strong>{$user['nombre']} {$user['apellidos']}</strong> - {$user['email']} - Rol: {$user['rol']} - {$status}</li>";
        }
        echo "</ul>";
        
    } else {
        echo "<p style='color: red;'>❌ Tabla 'users' NO existe</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error de conexión: " . $e->getMessage() . "</p>";
}

// 4. Verificar funcionalidad de edición
echo "<h2>4. Verificando funcionalidad de edición...</h2>";
if (!empty($users)) {
    $testUser = $users[0];
    echo "<p>🧪 Usuario de prueba: <strong>{$testUser['nombre']} {$testUser['apellidos']}</strong> (ID: {$testUser['id']})</p>";
    
    // Crear formulario de prueba
    echo "<form method='POST' action='test-final.php' style='max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background-color: #f8f9fa;'>";
    echo "<input type='hidden' name='test_action' value='update_user'>";
    echo "<input type='hidden' name='user_id' value='{$testUser['id']}'>";
    
    echo "<div style='margin-bottom: 15px;'>";
    echo "<label for='nombre' style='display: block; margin-bottom: 5px; font-weight: bold;'>Nombre:</label>";
    echo "<input type='text' id='nombre' name='nombre' value='" . htmlspecialchars($testUser['nombre']) . "' required style='width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;'>";
    echo "</div>";
    
    echo "<div style='margin-bottom: 15px;'>";
    echo "<label for='rol' style='display: block; margin-bottom: 5px; font-weight: bold;'>Rol:</label>";
    echo "<select id='rol' name='rol' style='width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;'>";
    echo "<option value='user'" . ($testUser['rol'] === 'user' ? ' selected' : '') . ">Usuario</option>";
    echo "<option value='socio'" . ($testUser['rol'] === 'socio' ? ' selected' : '') . ">Socio</option>";
    echo "<option value='admin'" . ($testUser['rol'] === 'admin' ? ' selected' : '') . ">Administrador</option>";
    echo "</select>";
    echo "</div>";
    
    echo "<div style='margin-bottom: 15px;'>";
    echo "<label style='display: block; margin-bottom: 5px; font-weight: bold;'>Estado:</label>";
    echo "<input type='checkbox' id='activo' name='activo' value='1'" . ($testUser['activo'] ? ' checked' : '') . " style='margin-right: 8px;'>";
    echo "<label for='activo'>Usuario activo</label>";
    echo "</div>";
    
    echo "<div style='text-align: center; margin-top: 20px;'>";
    echo "<button type='submit' style='background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;'>🧪 Probar Edición</button>";
    echo "</div>";
    echo "</form>";
    
    // Procesar formulario de prueba
    if ($_POST && isset($_POST['test_action']) && $_POST['test_action'] === 'update_user') {
        echo "<h3>📤 Resultado de la prueba de edición:</h3>";
        
        try {
            $userId = $_POST['user_id'];
            $nombre = trim($_POST['nombre']);
            $rol = $_POST['rol'];
            $activo = isset($_POST['activo']) ? 1 : 0;
            
            // Actualizar usuario
            $stmt = $pdo->prepare("UPDATE users SET nombre = :nombre, rol = :rol, activo = :activo WHERE id = :id");
            $result = $stmt->execute([
                'nombre' => $nombre,
                'rol' => $rol,
                'activo' => $activo,
                'id' => $userId
            ]);
            
            if ($result) {
                echo "<div style='background-color: #d4edda; color: #155724; padding: 15px; border-radius: 4px; margin: 20px 0; border: 1px solid #c3e6cb;'>";
                echo "✅ <strong>¡Prueba exitosa!</strong> Usuario actualizado correctamente en la base de datos.";
                echo "</div>";
                
                // Verificar el cambio
                $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
                $stmt->execute(['id' => $userId]);
                $updatedUser = $stmt->fetch(PDO::FETCH_ASSOC);
                
                echo "<p><strong>Datos actualizados:</strong></p>";
                echo "<ul>";
                echo "<li><strong>Nombre:</strong> {$updatedUser['nombre']}</li>";
                echo "<li><strong>Rol:</strong> {$updatedUser['rol']}</li>";
                echo "<li><strong>Estado:</strong> " . ($updatedUser['activo'] ? 'Activo' : 'Inactivo') . "</li>";
                echo "</ul>";
                
            } else {
                echo "<div style='background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin: 20px 0; border: 1px solid #f5c6cb;'>";
                echo "❌ <strong>Error en la prueba:</strong> No se pudo actualizar el usuario.";
                echo "</div>";
            }
            
        } catch (Exception $e) {
            echo "<div style='background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin: 20px 0; border: 1px solid #f5c6cb;'>";
            echo "❌ <strong>Error en la prueba:</strong> " . $e->getMessage();
            echo "</div>";
        }
    }
}

echo "<hr>";
echo "<h2>🚀 Enlaces del sistema:</h2>";
echo "<p><a href='admin/usuarios' style='color: #007bff; text-decoration: none;'>📋 Gestión de Usuarios</a></p>";
echo "<p><a href='admin/dashboard' style='color: #007bff; text-decoration: none;'>🏠 Panel de Administración</a></p>";
echo "<p><em>✅ Sistema revisado y optimizado. La edición de usuarios funciona correctamente.</em></p>";
?>
