<?php
// Script para probar específicamente el envío del modal de edición
echo "<h1>🔧 Prueba del Envío del Modal de Edición</h1>";

try {
    require_once 'src/config/config.php';
    require_once 'src/models/Database.php';
    require_once 'src/models/User.php';
    
    $db = new Database();
    $userModel = new User();
    
    echo "<p>✅ Conexión a la base de datos exitosa</p>";
    
    // Crear un usuario de prueba
    echo "<h2>📝 Creando Usuario de Prueba:</h2>";
    
    $testData = [
        'nombre' => 'Modal Submit Test',
        'apellidos' => 'Usuario',
        'email' => 'modal.submit.' . time() . '@example.com',
        'password' => '123456',
        'rol' => 'user',
        'activo' => 1
    ];
    
    $result = $userModel->register($testData);
    
    if ($result) {
        echo "<p style='color: green;'>✅ Usuario de prueba creado</p>";
        
        // Obtener el usuario creado
        $createdUser = $userModel->findUserByEmail($testData['email']);
        
        if ($createdUser) {
            echo "<p><strong>Usuario creado:</strong></p>";
            echo "<ul>";
            echo "<li><strong>ID:</strong> {$createdUser->id}</li>";
            echo "<li><strong>Nombre:</strong> {$createdUser->nombre}</li>";
            echo "<li><strong>Email:</strong> {$createdUser->email}</li>";
            echo "<li><strong>Rol actual:</strong> {$createdUser->rol}</li>";
            echo "</ul>";
            
            // Simular exactamente los datos que enviaría el modal
            echo "<h2>📋 Simulando Envío del Modal:</h2>";
            
            // Simular la URL que usaría el modal
            $modalUrl = URL_ROOT . '/admin/editarUsuario/' . $createdUser->id;
            echo "<p><strong>URL del modal:</strong> {$modalUrl}</p>";
            
            // Simular datos POST exactos del modal
            $_POST = [
                'nombre' => $createdUser->nombre,
                'apellidos' => $createdUser->apellidos,
                'email' => $createdUser->email,
                'rol' => 'socio', // Cambiar a socio
                'activo' => '1',
                'csrf_token' => 'test-token'
            ];
            
            echo "<p>Datos POST del modal:</p>";
            echo "<pre>" . print_r($_POST, true) . "</pre>";
            
            // Simular el procesamiento del controlador
            echo "<h2>⚙️ Procesamiento del Controlador:</h2>";
            
            // Simular el método editarUsuario del controlador
            $userData = [
                'id' => $createdUser->id,
                'nombre' => trim(htmlspecialchars($_POST['nombre'] ?? '')),
                'apellidos' => trim(htmlspecialchars($_POST['apellidos'] ?? '')),
                'email' => filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL),
                'rol' => trim(htmlspecialchars($_POST['rol'] ?? 'user')),
                'activo' => isset($_POST['activo']) && $_POST['activo'] == '1' ? 1 : 0,
                'errors' => []
            ];
            
            echo "<p>Datos procesados por el controlador:</p>";
            echo "<pre>" . print_r($userData, true) . "</pre>";
            
            // Validar datos
            echo "<h2>✅ Validación:</h2>";
            
            if (empty($userData['nombre'])) $userData['errors']['nombre'] = 'Nombre requerido';
            if (empty($userData['email'])) $userData['errors']['email'] = 'Email requerido';
            if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
                $userData['errors']['email'] = 'Email inválido';
            }
            
            // Validate role
            $validRoles = ['user', 'socio', 'admin'];
            if (!in_array($userData['rol'], $validRoles)) {
                $userData['errors']['rol'] = 'Rol inválido. Debe ser: ' . implode(', ', $validRoles);
            }
            
            if (empty($userData['errors'])) {
                echo "<p style='color: green;'>✅ No hay errores de validación</p>";
                
                // Actualizar usuario
                echo "<h2>🔄 Actualizando Usuario:</h2>";
                
                $updateResult = $userModel->updateUser($userData);
                
                if ($updateResult) {
                    echo "<p style='color: green;'>✅ Actualización exitosa</p>";
                    
                    // Verificar resultado
                    $updatedUser = $userModel->findUserById($createdUser->id);
                    
                    if ($updatedUser) {
                        echo "<p><strong>Usuario después de la actualización:</strong></p>";
                        echo "<ul>";
                        echo "<li><strong>ID:</strong> {$updatedUser->id}</li>";
                        echo "<li><strong>Nombre:</strong> {$updatedUser->nombre}</li>";
                        echo "<li><strong>Email:</strong> {$updatedUser->email}</li>";
                        echo "<li><strong>Rol actual:</strong> {$updatedUser->rol}</li>";
                        echo "<li><strong>Activo:</strong> " . ($updatedUser->activo ? 'Sí' : 'No') . "</li>";
                        echo "</ul>";
                        
                        if ($updatedUser->rol === 'socio') {
                            echo "<p style='color: green;'>✅ El rol se actualizó correctamente a 'socio'</p>";
                            echo "<h2 style='color: green;'>🎉 ¡Modal Funciona Correctamente!</h2>";
                        } else {
                            echo "<p style='color: red;'>❌ El rol NO se actualizó correctamente. Rol actual: '{$updatedUser->rol}'</p>";
                        }
                    }
                } else {
                    echo "<p style='color: red;'>❌ Error en la actualización</p>";
                }
            } else {
                echo "<p style='color: red;'>❌ Errores de validación:</p>";
                echo "<pre>" . print_r($userData['errors'], true) . "</pre>";
            }
            
            // Limpiar usuario de prueba
            $db->query('DELETE FROM users WHERE id = :id');
            $db->bind(':id', $createdUser->id);
            $db->execute();
            echo "<p>Usuario de prueba eliminado</p>";
            
        } else {
            echo "<p style='color: red;'>❌ No se pudo obtener el usuario creado</p>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ Error al crear usuario de prueba</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'><strong>❌ Error:</strong> " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h2>🔗 Enlaces:</h2>";
echo "<a href='/prueba-php/public/admin/usuarios' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px; display: inline-block;'>🚀 Ir a Gestión de Usuarios</a>";
echo "<a href='/prueba-php/public/admin/dashboard' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px; display: inline-block;'>🏠 Ir al Dashboard</a>";

echo "<p><em>Prueba del envío del modal completada.</em></p>";
?>
