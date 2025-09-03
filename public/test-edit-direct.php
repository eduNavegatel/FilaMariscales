<?php
// Archivo de prueba directa para simular la edición de usuarios
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>";
echo "<html lang='es'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Test Edición Directa - Filá Mariscales</title>";
echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
echo "</head>";
echo "<body>";
echo "<div class='container mt-4'>";
echo "<h1>🧪 Test Edición Directa de Usuarios</h1>";

try {
    // Cargar configuración
    require_once '../src/config/config.php';
    require_once '../src/models/Database.php';
    require_once '../src/models/User.php';
    
    echo "<div class='alert alert-success'>✅ Archivos cargados correctamente</div>";
    
    // Crear instancia del modelo User
    $userModel = new User();
    echo "<div class='alert alert-success'>✅ Modelo User creado correctamente</div>";
    
    // Obtener usuario específico para editar (ID 36)
    $userId = 36;
    $user = $userModel->getUserById($userId);
    
    if ($user) {
        echo "<div class='alert alert-info'>✅ Usuario encontrado: {$user->nombre} {$user->apellidos}</div>";
        
        // Mostrar datos actuales del usuario
        echo "<h3>📊 Datos Actuales del Usuario</h3>";
        echo "<div class='card'>";
        echo "<div class='card-body'>";
        echo "<table class='table table-striped'>";
        echo "<tr><th>Campo</th><th>Valor</th></tr>";
        echo "<tr><td>ID</td><td>{$user->id}</td></tr>";
        echo "<tr><td>Nombre</td><td>{$user->nombre}</td></tr>";
        echo "<tr><td>Apellidos</td><td>{$user->apellidos}</td></tr>";
        echo "<tr><td>Email</td><td>{$user->email}</td></tr>";
        echo "<tr><td>Rol</td><td>{$user->rol}</td></tr>";
        echo "<tr><td>Activo</td><td>" . ($user->activo ? 'Sí' : 'No') . "</td></tr>";
        echo "</table>";
        echo "</div>";
        echo "</div>";
        
        // Formulario de prueba con datos reales
        echo "<h3>✏️ Formulario de Prueba de Edición</h3>";
        echo "<div class='card'>";
        echo "<div class='card-body'>";
        echo "<form id='directEditForm' method='POST'>";
        echo "<input type='hidden' name='csrf_token' value='" . bin2hex(random_bytes(32)) . "'>";
        echo "<input type='hidden' name='user_id' value='{$userId}'>";
        
        echo "<div class='row'>";
        echo "<div class='col-md-6'>";
        echo "<div class='mb-3'>";
        echo "<label for='nombre' class='form-label'>Nombre</label>";
        echo "<input type='text' class='form-control' id='nombre' name='nombre' value='{$user->nombre}' required>";
        echo "</div>";
        echo "</div>";
        echo "<div class='col-md-6'>";
        echo "<div class='mb-3'>";
        echo "<label for='apellidos' class='form-label'>Apellidos</label>";
        echo "<input type='text' class='form-control' id='apellidos' name='apellidos' value='{$user->apellidos}' required>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        
        echo "<div class='row'>";
        echo "<div class='col-md-6'>";
        echo "<div class='mb-3'>";
        echo "<label for='email' class='form-label'>Email</label>";
        echo "<input type='email' class='form-control' id='email' name='email' value='{$user->email}' required>";
        echo "</div>";
        echo "</div>";
        echo "<div class='col-md-6'>";
        echo "<div class='mb-3'>";
        echo "<label for='rol' class='form-label'>Rol</label>";
        echo "<select class='form-select' id='rol' name='rol'>";
        echo "<option value='user'" . ($user->rol === 'user' ? ' selected' : '') . ">Usuario</option>";
        echo "<option value='socio'" . ($user->rol === 'socio' ? ' selected' : '') . ">Socio</option>";
        echo "<option value='admin'" . ($user->rol === 'admin' ? ' selected' : '') . ">Administrador</option>";
        echo "</select>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        
        echo "<div class='mb-3'>";
        echo "<div class='form-check'>";
        echo "<input class='form-check-input' type='checkbox' id='activo' name='activo' value='1'" . ($user->activo ? ' checked' : '') . ">";
        echo "<label class='form-check-label' for='activo'>Usuario activo</label>";
        echo "</div>";
        echo "</div>";
        
        echo "<div class='d-flex gap-2'>";
        echo "<button type='submit' class='btn btn-primary'>Guardar Cambios</button>";
        echo "<button type='button' class='btn btn-secondary' onclick='testUpdateUser()'>Probar Actualización</button>";
        echo "<button type='button' class='btn btn-info' onclick='showFormData()'>Mostrar Datos del Formulario</button>";
        echo "<button type='button' class='btn btn-warning' onclick='performDirectUpdate()'>Actualización Directa en BD</button>";
        echo "</div>";
        echo "</form>";
        echo "</div>";
        echo "</div>";
        
        // Procesar el formulario si se envía
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h3>🔄 Procesando Formulario...</h3>";
            echo "<div class='card'>";
            echo "<div class='card-body'>";
            
            try {
                // Simular exactamente lo que hace el AdminController
                error_log("=== INICIO DEL TEST DE EDICIÓN ===");
                error_log("Request method: " . $_SERVER['REQUEST_METHOD']);
                error_log("POST data: " . print_r($_POST, true));
                
                // Process form data safely
                $userData = [
                    'id' => $userId ?: ($_POST['user_id'] ?? null),
                    'nombre' => trim(htmlspecialchars($_POST['nombre'] ?? '')),
                    'apellidos' => trim(htmlspecialchars($_POST['apellidos'] ?? '')),
                    'email' => filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL),
                    'rol' => trim(htmlspecialchars($_POST['rol'] ?? 'user')),
                    'activo' => isset($_POST['activo']) && $_POST['activo'] == '1' ? 1 : 0,
                    'errors' => []
                ];
                
                error_log("User data to update: " . print_r($userData, true));
                
                // Validate data
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
                    error_log("Attempting to update user with ID: " . $userId);
                    
                    // Actualizar usuario directamente
                    $result = $userModel->updateUser($userData);
                    error_log("Update result: " . ($result ? 'success' : 'failed'));
                    
                    if ($result) {
                        echo "<div class='alert alert-success'>✅ Usuario actualizado correctamente en la base de datos</div>";
                        
                        // Mostrar datos actualizados
                        $updatedUser = $userModel->getUserById($userId);
                        echo "<h4>📊 Datos Actualizados:</h4>";
                        echo "<table class='table table-striped'>";
                        echo "<tr><th>Campo</th><th>Valor Anterior</th><th>Valor Nuevo</th></tr>";
                        echo "<tr><td>Nombre</td><td>{$user->nombre}</td><td>{$updatedUser->nombre}</td></tr>";
                        echo "<tr><td>Apellidos</td><td>{$user->apellidos}</td><td>{$updatedUser->apellidos}</td></tr>";
                        echo "<tr><td>Email</td><td>{$user->email}</td><td>{$updatedUser->email}</td></tr>";
                        echo "<tr><td>Rol</td><td>{$user->rol}</td><td>{$updatedUser->rol}</td></tr>";
                        echo "<tr><td>Activo</td><td>" . ($user->activo ? 'Sí' : 'No') . "</td><td>" . ($updatedUser->activo ? 'Sí' : 'No') . "</td></tr>";
                        echo "</table>";
                        
                        echo "<div class='alert alert-info'>🔄 Ahora puedes probar la redirección:</div>";
                        echo "<a href='/prueba-php/public/admin/usuarios?updated=1&t=" . time() . "&refresh=1' class='btn btn-success'>Ir a Lista de Usuarios</a>";
                        
                    } else {
                        echo "<div class='alert alert-danger'>❌ Error al actualizar el usuario en la base de datos</div>";
                        echo "<p>Revisa los logs de error para más detalles.</p>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>❌ Errores de validación:</div>";
                    echo "<ul>";
                    foreach ($userData['errors'] as $error) {
                        echo "<li>{$error}</li>";
                    }
                    echo "</ul>";
                }
                
                error_log("=== FIN DEL TEST DE EDICIÓN ===");
                
            } catch (Exception $e) {
                echo "<div class='alert alert-danger'>❌ Excepción durante la actualización: " . $e->getMessage() . "</div>";
                echo "<div class='alert alert-info'>📋 Stack trace:</div>";
                echo "<pre>" . $e->getTraceAsString() . "</pre>";
                error_log("Exception in test: " . $e->getMessage());
            }
            
            echo "</div>";
            echo "</div>";
        }
        
    } else {
        echo "<div class='alert alert-danger'>❌ No se pudo encontrar el usuario con ID {$userId}</div>";
    }
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>❌ Error: " . $e->getMessage() . "</div>";
    echo "<div class='alert alert-info'>📋 Stack trace:</div>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<div class='mt-4'>";
echo "<a href='/prueba-php/public/admin/dashboard' class='btn btn-primary'>Volver al Dashboard</a>";
echo "<a href='/prueba-php/public/admin/usuarios' class='btn btn-secondary ms-2'>Ir a Usuarios</a>";
echo "<a href='/prueba-php/public/test-db-users.php' class='btn btn-info ms-2'>Test Base de Datos</a>";
echo "</div>";

echo "</div>";

echo "<script>";
echo "function testUpdateUser() {";
echo "    const formData = new FormData(document.getElementById('directEditForm'));";
echo "    console.log('Datos del formulario:');";
echo "    for (let [key, value] of formData.entries()) {";
echo "        console.log(key + ': ' + value);";
echo "    }";
echo "    alert('Revisa la consola del navegador para ver los datos del formulario');";
echo "}";
echo "";
echo "function showFormData() {";
echo "    const form = document.getElementById('directEditForm');";
echo "    const formData = new FormData(form);";
echo "    let data = {};";
echo "    for (let [key, value] of formData.entries()) {";
echo "        data[key] = value;";
echo "    }";
echo "    console.log('Datos del formulario:', data);";
echo "    alert('Datos del formulario mostrados en la consola');";
echo "}";
echo "";
echo "function performDirectUpdate() {";
echo "    if (confirm('¿Estás seguro de que quieres actualizar el usuario directamente en la base de datos?')) {";
echo "        // Enviar el formulario";
echo "        document.getElementById('directEditForm').submit();";
echo "    }";
echo "}";
echo "</script>";

echo "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>";
echo "</body>";
echo "</html>";
