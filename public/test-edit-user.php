<?php
// Archivo de prueba para la funcionalidad de edición de usuarios
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>";
echo "<html lang='es'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Test Edición de Usuarios - Filá Mariscales</title>";
echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
echo "</head>";
echo "<body>";
echo "<div class='container mt-4'>";
echo "<h1>🧪 Test de Edición de Usuarios</h1>";

try {
    // Cargar configuración
    require_once '../src/config/config.php';
    require_once '../src/models/Database.php';
    require_once '../src/models/User.php';
    
    echo "<div class='alert alert-success'>✅ Archivos cargados correctamente</div>";
    
    // Crear instancia del modelo User
    $userModel = new User();
    echo "<div class='alert alert-success'>✅ Modelo User creado correctamente</div>";
    
    // Obtener usuarios para mostrar
    $users = $userModel->getAllUsers(1, 5);
    echo "<div class='alert alert-info'>📊 Usuarios encontrados: " . count($users) . "</div>";
    
    if (!empty($users)) {
        echo "<h3>👥 Usuarios Disponibles para Editar</h3>";
        echo "<table class='table table-striped'>";
        echo "<thead><tr><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Activo</th><th>Acción</th></tr></thead>";
        echo "<tbody>";
        
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>{$user->id}</td>";
            echo "<td>{$user->nombre} {$user->apellidos}</td>";
            echo "<td>{$user->email}</td>";
            echo "<td><span class='badge bg-" . ($user->rol === 'admin' ? 'danger' : ($user->rol === 'socio' ? 'primary' : 'secondary')) . "'>{$user->rol}</span></td>";
            echo "<td>" . ($user->activo ? '<span class="badge bg-success">Sí</span>' : '<span class="badge bg-danger">No</span>') . "</td>";
            echo "<td>";
            echo "<button class='btn btn-sm btn-primary' onclick='testEditUser({$user->id})'>Editar</button>";
            echo "</td>";
            echo "</tr>";
        }
        
        echo "</tbody></table>";
        
        // Formulario de prueba de edición
        $firstUser = $users[0];
        echo "<h3>✏️ Formulario de Prueba de Edición</h3>";
        echo "<div class='card'>";
        echo "<div class='card-body'>";
        echo "<form id='testEditForm' method='POST' action='/prueba-php/public/admin/editarUsuario/{$firstUser->id}'>";
        echo "<input type='hidden' name='csrf_token' value='" . bin2hex(random_bytes(32)) . "'>";
        echo "<input type='hidden' name='user_id' value='{$firstUser->id}'>";
        
        echo "<div class='row'>";
        echo "<div class='col-md-6'>";
        echo "<div class='mb-3'>";
        echo "<label for='nombre' class='form-label'>Nombre</label>";
        echo "<input type='text' class='form-control' id='nombre' name='nombre' value='{$firstUser->nombre}' required>";
        echo "</div>";
        echo "</div>";
        echo "<div class='col-md-6'>";
        echo "<div class='mb-3'>";
        echo "<label for='apellidos' class='form-label'>Apellidos</label>";
        echo "<input type='text' class='form-control' id='apellidos' name='apellidos' value='{$firstUser->apellidos}' required>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        
        echo "<div class='row'>";
        echo "<div class='col-md-6'>";
        echo "<div class='mb-3'>";
        echo "<label for='email' class='form-label'>Email</label>";
        echo "<input type='email' class='form-control' id='email' name='email' value='{$firstUser->email}' required>";
        echo "</div>";
        echo "</div>";
        echo "<div class='col-md-6'>";
        echo "<div class='mb-3'>";
        echo "<label for='rol' class='form-label'>Rol</label>";
        echo "<select class='form-select' id='rol' name='rol'>";
        echo "<option value='user'" . ($firstUser->rol === 'user' ? ' selected' : '') . ">Usuario</option>";
        echo "<option value='socio'" . ($firstUser->rol === 'socio' ? ' selected' : '') . ">Socio</option>";
        echo "<option value='admin'" . ($firstUser->rol === 'admin' ? ' selected' : '') . ">Administrador</option>";
        echo "</select>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        
        echo "<div class='mb-3'>";
        echo "<div class='form-check'>";
        echo "<input class='form-check-input' type='checkbox' id='activo' name='activo' value='1'" . ($firstUser->activo ? ' checked' : '') . ">";
        echo "<label class='form-check-label' for='activo'>Usuario activo</label>";
        echo "</div>";
        echo "</div>";
        
        echo "<div class='d-flex gap-2'>";
        echo "<button type='submit' class='btn btn-primary'>Guardar Cambios</button>";
        echo "<button type='button' class='btn btn-secondary' onclick='testUpdateUser()'>Probar Actualización</button>";
        echo "<button type='button' class='btn btn-info' onclick='showUserData()'>Mostrar Datos</button>";
        echo "</div>";
        echo "</form>";
        echo "</div>";
        echo "</div>";
        
        // Información de debug
        echo "<h3>🔍 Información de Debug</h3>";
        echo "<div class='card'>";
        echo "<div class='card-body'>";
        echo "<h5>Datos del Usuario Seleccionado:</h5>";
        echo "<pre>" . print_r($firstUser, true) . "</pre>";
        
        echo "<h5>URL del Formulario:</h5>";
        echo "<code>/prueba-php/public/admin/editarUsuario/{$firstUser->id}</code>";
        
        echo "<h5>Método HTTP:</h5>";
        echo "<code>POST</code>";
        
        echo "<h5>Campos del Formulario:</h5>";
        echo "<ul>";
        echo "<li><strong>csrf_token</strong>: Token de seguridad</li>";
        echo "<li><strong>user_id</strong>: ID del usuario ({$firstUser->id})</li>";
        echo "<li><strong>nombre</strong>: Nombre del usuario</li>";
        echo "<li><strong>apellidos</strong>: Apellidos del usuario</li>";
        echo "<li><strong>email</strong>: Email del usuario</li>";
        echo "<li><strong>rol</strong>: Rol del usuario</li>";
        echo "<li><strong>activo</strong>: Estado activo/inactivo</li>";
        echo "</ul>";
        echo "</div>";
        echo "</div>";
        
    } else {
        echo "<div class='alert alert-warning'>⚠️ No hay usuarios disponibles para editar</div>";
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
echo "function testEditUser(userId) {";
echo "    console.log('Editando usuario:', userId);";
echo "    // Actualizar el formulario con los datos del usuario seleccionado";
echo "    // Esto simula la funcionalidad del modal de edición";
echo "}";
echo "";
echo "function testUpdateUser() {";
echo "    const formData = new FormData(document.getElementById('testEditForm'));";
echo "    console.log('Datos del formulario:');";
echo "    for (let [key, value] of formData.entries()) {";
echo "        console.log(key + ': ' + value);";
echo "    }";
echo "    alert('Revisa la consola del navegador para ver los datos del formulario');";
echo "}";
echo "";
echo "function showUserData() {";
echo "    const form = document.getElementById('testEditForm');";
echo "    const formData = new FormData(form);";
echo "    let data = {};";
echo "    for (let [key, value] of formData.entries()) {";
echo "        data[key] = value;";
echo "    }";
echo "    console.log('Datos del formulario:', data);";
echo "    alert('Datos del formulario mostrados en la consola');";
echo "}";
echo "</script>";

echo "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>";
echo "</body>";
echo "</html>";
