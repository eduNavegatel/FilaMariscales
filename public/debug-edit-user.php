<?php
// Archivo de debug para identificar problemas con la edición de usuarios
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>";
echo "<html lang='es'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Debug Edición de Usuarios - Filá Mariscales</title>";
echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
echo "</head>";
echo "<body>";
echo "<div class='container mt-4'>";
echo "<h1>🐛 Debug Edición de Usuarios</h1>";

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
        echo "<form id='debugEditForm' method='POST' action='/prueba-php/public/admin/editarUsuario/{$userId}'>";
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
        echo "<button type='button' class='btn btn-warning' onclick='testDirectUpdate()'>Test Directo en BD</button>";
        echo "</div>";
        echo "</form>";
        echo "</div>";
        echo "</div>";
        
        // Información de debug
        echo "<h3>🔍 Información de Debug</h3>";
        echo "<div class='card'>";
        echo "<div class='card-body'>";
        echo "<h5>URL del Formulario:</h5>";
        echo "<code>/prueba-php/public/admin/editarUsuario/{$userId}</code>";
        echo "<br><br>";
        
        echo "<h5>Método HTTP:</h5>";
        echo "<code>POST</code>";
        echo "<br><br>";
        
        echo "<h5>Campos del Formulario:</h5>";
        echo "<ul>";
        echo "<li><strong>csrf_token</strong>: Token de seguridad</li>";
        echo "<li><strong>user_id</strong>: ID del usuario ({$userId})</li>";
        echo "<li><strong>nombre</strong>: Nombre del usuario</li>";
        echo "<li><strong>apellidos</strong>: Apellidos del usuario</li>";
        echo "<li><strong>email</strong>: Email del usuario</li>";
        echo "<li><strong>rol</strong>: Rol del usuario</li>";
        echo "<li><strong>activo</strong>: Estado activo/inactivo</li>";
        echo "</ul>";
        echo "</div>";
        echo "</div>";
        
        // Test de actualización directa en base de datos
        echo "<h3>🧪 Test de Actualización Directa en Base de Datos</h3>";
        echo "<div class='card'>";
        echo "<div class='card-body'>";
        echo "<p>Este test actualizará directamente el usuario en la base de datos para verificar que funciona:</p>";
        
        // Simular datos de actualización
        $testUpdateData = [
            'id' => $userId,
            'nombre' => $user->nombre . '_test',
            'apellidos' => $user->apellidos . '_test',
            'email' => $user->email,
            'rol' => $user->rol,
            'activo' => $user->activo
        ];
        
        echo "<h6>Datos de prueba para actualización:</h6>";
        echo "<pre>" . print_r($testUpdateData, true) . "</pre>";
        
        echo "<button type='button' class='btn btn-warning' onclick='performDirectUpdate()'>Ejecutar Actualización Directa</button>";
        echo "</div>";
        echo "</div>";
        
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
echo "    const formData = new FormData(document.getElementById('debugEditForm'));";
echo "    console.log('Datos del formulario:');";
echo "    for (let [key, value] of formData.entries()) {";
echo "        console.log(key + ': ' + value);";
echo "    }";
echo "    alert('Revisa la consola del navegador para ver los datos del formulario');";
echo "}";
echo "";
echo "function showFormData() {";
echo "    const form = document.getElementById('debugEditForm');";
echo "    const formData = new FormData(form);";
echo "    let data = {};";
echo "    for (let [key, value] of formData.entries()) {";
echo "        data[key] = value;";
echo "    }";
echo "    console.log('Datos del formulario:', data);";
echo "    alert('Datos del formulario mostrados en la consola');";
echo "}";
echo "";
echo "function testDirectUpdate() {";
echo "    alert('Esta función probará la actualización directa en la base de datos');";
echo "    // Aquí se podría hacer una llamada AJAX para probar la actualización";
echo "}";
echo "";
echo "function performDirectUpdate() {";
echo "    if (confirm('¿Estás seguro de que quieres actualizar el usuario directamente en la base de datos?')) {";
echo "        // Crear formulario temporal para la actualización directa";
echo "        const form = document.createElement('form');";
echo "        form.method = 'POST';";
echo "        form.action = '/prueba-php/public/admin/editarUsuario/36';";
echo "        form.target = '_blank';";
echo "        ";
echo "        const formData = new FormData(document.getElementById('debugEditForm'));";
echo "        for (let [key, value] of formData.entries()) {";
echo "            const input = document.createElement('input');";
echo "            input.type = 'hidden';";
echo "            input.name = key;";
echo "            input.value = value;";
echo "            form.appendChild(input);";
echo "        }";
echo "        ";
echo "        document.body.appendChild(form);";
echo "        form.submit();";
echo "        document.body.removeChild(form);";
echo "        ";
echo "        alert('Formulario enviado en nueva pestaña. Revisa si hay errores.');";
echo "    }";
echo "}";
echo "</script>";

echo "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>";
echo "</body>";
echo "</html>";
