<?php
// Script para simular el envío del formulario web
echo "<h1>🌐 Simulación del Formulario Web</h1>";

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
    
    // Simular datos del formulario POST
    $newRole = ($testUser->rol === 'user') ? 'socio' : 'user';
    echo "<h3>📝 Simulando envío del formulario:</h3>";
    echo "<p>Cambiando rol de '{$testUser->rol}' a '{$newRole}'</p>";
    
    // Simular exactamente los datos que enviaría el formulario
    $_POST = [
        'csrf_token' => 'test-token',
        'user_id' => $testUser->id,
        'nombre' => $testUser->nombre,
        'apellidos' => $testUser->apellidos,
        'email' => $testUser->email,
        'rol' => $newRole,
        'activo' => $testUser->activo ? '1' : '0'
    ];
    
    $_SERVER['REQUEST_METHOD'] = 'POST';
    
    echo "<h4>Datos POST simulados:</h4>";
    echo "<pre>" . print_r($_POST, true) . "</pre>";
    
    // Simular el procesamiento del controlador
    echo "<h3>⚙️ Procesando datos como lo haría el controlador:</h3>";
    
    // Procesar datos del formulario (código del controlador)
    $userData = [
        'id' => $testUser->id,
        'nombre' => trim(htmlspecialchars($_POST['nombre'] ?? '')),
        'apellidos' => trim(htmlspecialchars($_POST['apellidos'] ?? '')),
        'email' => filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL),
        'rol' => trim(htmlspecialchars($_POST['rol'] ?? 'user')),
        'activo' => isset($_POST['activo']) && $_POST['activo'] == '1' ? 1 : 0,
        'errors' => []
    ];
    
    echo "<h4>Datos procesados:</h4>";
    echo "<pre>" . print_r($userData, true) . "</pre>";
    
    // Validar datos
    $validRoles = ['user', 'socio', 'admin'];
    if (!in_array($userData['rol'], $validRoles)) {
        $userData['errors']['rol'] = 'Rol inválido. Debe ser: ' . implode(', ', $validRoles);
    }
    
    if (empty($userData['nombre'])) $userData['errors']['nombre'] = 'Nombre requerido';
    if (empty($userData['email'])) $userData['errors']['email'] = 'Email requerido';
    if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
        $userData['errors']['email'] = 'Email inválido';
    }
    
    echo "<h4>Errores de validación:</h4>";
    if (empty($userData['errors'])) {
        echo "<p style='color: green;'>✅ No hay errores de validación</p>";
    } else {
        echo "<p style='color: red;'>❌ Errores encontrados:</p>";
        echo "<pre>" . print_r($userData['errors'], true) . "</pre>";
    }
    
    // Intentar actualizar si no hay errores
    if (empty($userData['errors'])) {
        echo "<h3>💾 Intentando actualizar en la base de datos...</h3>";
        
        $result = $userModel->updateUser($userData);
        
        if ($result) {
            echo "<p style='color: green;'>✅ Usuario actualizado correctamente</p>";
            
            // Verificar que el cambio se aplicó
            $updatedUser = $userModel->getUserById($testUser->id);
            if ($updatedUser) {
                echo "<h4>📋 Usuario después de la actualización:</h4>";
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
            }
            
            // Revertir el cambio
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
            }
            
        } else {
            echo "<p style='color: red;'>❌ Error al actualizar el usuario</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h2>🔍 Análisis del Problema:</h2>";
echo "<p>Si la simulación funciona pero el formulario web no, el problema puede estar en:</p>";
echo "<ul>";
echo "<li>JavaScript que previene el envío del formulario</li>";
echo "<li>Problemas con el CSRF token</li>";
echo "<li>Errores en la validación del lado del cliente</li>";
echo "<li>Problemas con la ruta del formulario</li>";
echo "<li>Errores en el navegador (consola)</li>";
echo "</ul>";

echo "<h2>🔗 Enlaces:</h2>";
echo "<p><a href='/prueba-php/public/admin/usuarios' target='_blank'>🚀 Ir a Gestión de Usuarios</a></p>";
echo "<p><a href='/prueba-php/public/admin/dashboard' target='_blank'>🏠 Ir al Dashboard</a></p>";
echo "<p><em>Script completado.</em></p>";
?>
