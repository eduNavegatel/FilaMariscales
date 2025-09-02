<?php
// Script de prueba para verificar la actualización del rol
echo "<h1>🧪 Prueba de Actualización de Rol</h1>";

try {
    require_once 'src/config/config.php';
    require_once 'src/models/Database.php';
    require_once 'src/models/User.php';
    
    $db = new Database();
    $userModel = new User();
    
    echo "<p>✅ Conexión a la base de datos exitosa</p>";
    
    // Obtener un usuario para probar
    $users = $userModel->getAllUsers(1, 5);
    
    if (empty($users)) {
        echo "<p style='color: red;'>❌ No hay usuarios para probar</p>";
        exit;
    }
    
    $testUser = $users[0];
    echo "<h2>👤 Usuario de Prueba:</h2>";
    echo "<ul>";
    echo "<li><strong>ID:</strong> {$testUser->id}</li>";
    echo "<li><strong>Nombre:</strong> {$testUser->nombre}</li>";
    echo "<li><strong>Email:</strong> {$testUser->email}</li>";
    echo "<li><strong>Rol actual:</strong> {$testUser->rol}</li>";
    echo "<li><strong>Activo:</strong> " . ($testUser->activo ? 'Sí' : 'No') . "</li>";
    echo "</ul>";
    
    // Probar cambio de rol
    $newRole = ($testUser->rol === 'user') ? 'socio' : 'user';
    echo "<h3>🔄 Probando cambio de rol de '{$testUser->rol}' a '{$newRole}'...</h3>";
    
    $updateData = [
        'id' => $testUser->id,
        'nombre' => $testUser->nombre,
        'apellidos' => $testUser->apellidos,
        'email' => $testUser->email,
        'rol' => $newRole,
        'activo' => $testUser->activo
    ];
    
    echo "<p>Datos a actualizar:</p>";
    echo "<pre>" . print_r($updateData, true) . "</pre>";
    
    // Intentar actualizar
    $result = $userModel->updateUser($updateData);
    
    if ($result) {
        echo "<p style='color: green;'>✅ Usuario actualizado correctamente</p>";
        
        // Verificar que el cambio se aplicó
        $updatedUser = $userModel->getUserById($testUser->id);
        if ($updatedUser) {
            echo "<h3>📋 Usuario después de la actualización:</h3>";
            echo "<ul>";
            echo "<li><strong>ID:</strong> {$updatedUser->id}</li>";
            echo "<li><strong>Nombre:</strong> {$updatedUser->nombre}</li>";
            echo "<li><strong>Email:</strong> {$updatedUser->email}</li>";
            echo "<li><strong>Rol nuevo:</strong> {$updatedUser->rol}</li>";
            echo "<li><strong>Activo:</strong> " . ($updatedUser->activo ? 'Sí' : 'No') . "</li>";
            echo "</ul>";
            
            if ($updatedUser->rol === $newRole) {
                echo "<p style='color: green;'>🎉 ¡El rol se actualizó correctamente!</p>";
            } else {
                echo "<p style='color: red;'>❌ El rol no se actualizó correctamente</p>";
            }
        } else {
            echo "<p style='color: red;'>❌ No se pudo obtener el usuario actualizado</p>";
        }
        
        // Revertir el cambio para no afectar los datos
        echo "<h3>🔄 Revertiendo el cambio...</h3>";
        $revertData = [
            'id' => $testUser->id,
            'nombre' => $testUser->nombre,
            'apellidos' => $testUser->apellidos,
            'email' => $testUser->email,
            'rol' => $testUser->rol,
            'activo' => $testUser->activo
        ];
        
        $revertResult = $userModel->updateUser($revertData);
        if ($revertResult) {
            echo "<p style='color: green;'>✅ Cambio revertido correctamente</p>";
        } else {
            echo "<p style='color: orange;'>⚠️ No se pudo revertir el cambio</p>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ Error al actualizar el usuario</p>";
        
        // Verificar si hay errores en la base de datos
        echo "<h3>🔍 Verificando posibles errores:</h3>";
        
        // Verificar la estructura de la tabla
        $db->query("DESCRIBE users");
        $columns = $db->resultSet();
        
        foreach ($columns as $column) {
            if ($column->Field === 'rol') {
                echo "<p><strong>Columna 'rol':</strong> {$column->Type}</p>";
                break;
            }
        }
        
        // Verificar si el usuario existe
        $checkUser = $userModel->getUserById($testUser->id);
        if ($checkUser) {
            echo "<p>✅ El usuario existe en la base de datos</p>";
        } else {
            echo "<p>❌ El usuario no existe en la base de datos</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h2>🔗 Enlaces:</h2>";
echo "<p><a href='/prueba-php/public/admin/usuarios' target='_blank'>🚀 Ir a Gestión de Usuarios</a></p>";
echo "<p><a href='/prueba-php/public/admin/dashboard' target='_blank'>🏠 Ir al Dashboard</a></p>";
echo "<p><em>Script completado.</em></p>";
?>
