<?php
// Versión simplificada de la vista de usuarios para probar
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>";
echo "<html lang='es'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Gestión de Usuarios - Test</title>";
echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
echo "<link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css' rel='stylesheet'>";
echo "</head>";
echo "<body>";

echo "<div class='container-fluid mt-4'>";
echo "<h1>Gestión de Usuarios - Test</h1>";

// Probar inclusión de archivos
echo "<h2>Prueba de Archivos:</h2>";

try {
    require_once 'src/config/config.php';
    echo "<p>✅ config.php incluido</p>";
} catch (Exception $e) {
    echo "<p>❌ Error config.php: " . $e->getMessage() . "</p>";
}

try {
    require_once 'src/models/Database.php';
    echo "<p>✅ Database.php incluido</p>";
} catch (Exception $e) {
    echo "<p>❌ Error Database.php: " . $e->getMessage() . "</p>";
}

try {
    require_once 'src/helpers/SecurityHelper.php';
    echo "<p>✅ SecurityHelper.php incluido</p>";
} catch (Exception $e) {
    echo "<p>❌ Error SecurityHelper.php: " . $e->getMessage() . "</p>";
}

// Probar conexión a base de datos
echo "<h2>Prueba de Base de Datos:</h2>";

try {
    $db = new Database();
    echo "<p>✅ Conexión a BD exitosa</p>";
    
    // Obtener usuarios
    $db->query("SELECT id, nombre, apellidos, email, rol, activo, fecha_registro FROM users ORDER BY fecha_registro DESC LIMIT 10");
    $users = $db->resultSet();
    
    if ($users) {
        echo "<p>✅ Usuarios encontrados: " . count($users) . "</p>";
        
        echo "<div class='card'>";
        echo "<div class='card-header'>";
        echo "<h5>Lista de Usuarios</h5>";
        echo "</div>";
        echo "<div class='card-body'>";
        echo "<div class='table-responsive'>";
        echo "<table class='table table-hover'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Nombre</th>";
        echo "<th>Email</th>";
        echo "<th>Rol</th>";
        echo "<th>Estado</th>";
        echo "<th>Acciones</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>{$user->id}</td>";
            echo "<td><strong>" . htmlspecialchars($user->nombre . ' ' . $user->apellidos) . "</strong></td>";
            echo "<td>" . htmlspecialchars($user->email) . "</td>";
            echo "<td>";
            echo "<span class='badge bg-" . ($user->rol === 'admin' ? 'danger' : ($user->rol === 'socio' ? 'primary' : 'secondary')) . "'>";
            echo ucfirst($user->rol);
            echo "</span>";
            echo "</td>";
            echo "<td>";
            if ($user->activo) {
                echo "<span class='badge bg-success'>Activo</span>";
            } else {
                echo "<span class='badge bg-danger'>Inactivo</span>";
            }
            echo "</td>";
            echo "<td>";
            echo "<div class='btn-group' role='group'>";
            echo "<button type='button' class='btn btn-sm btn-outline-primary' onclick='testEditUser({$user->id})' title='Editar'>";
            echo "<i class='fas fa-edit'></i>";
            echo "</button>";
            echo "<button type='button' class='btn btn-sm btn-outline-info' onclick='testPasswordUser({$user->id})' title='Cambiar contraseña'>";
            echo "<i class='fas fa-key'></i>";
            echo "</button>";
            echo "</div>";
            echo "</td>";
            echo "</tr>";
            
            // Modal de edición para este usuario
            echo "<div class='modal fade' id='editModal{$user->id}' tabindex='-1' aria-hidden='true'>";
            echo "<div class='modal-dialog'>";
            echo "<div class='modal-content'>";
            echo "<div class='modal-header'>";
            echo "<h5 class='modal-title'>Editar Usuario</h5>";
            echo "<button type='button' class='btn-close' data-bs-dismiss='modal'></button>";
            echo "</div>";
            echo "<form action='/prueba-php/public/admin/editarUsuario/{$user->id}' method='POST'>";
            echo "<input type='hidden' name='csrf_token' value='" . (function_exists('generateCsrfToken') ? generateCsrfToken() : 'test-token') . "'>";
            echo "<div class='modal-body'>";
            echo "<div class='mb-3'>";
            echo "<label for='nombre{$user->id}' class='form-label'>Nombre</label>";
            echo "<input type='text' class='form-control' id='nombre{$user->id}' name='nombre' value='" . htmlspecialchars($user->nombre) . "' required>";
            echo "</div>";
            echo "<div class='mb-3'>";
            echo "<label for='apellidos{$user->id}' class='form-label'>Apellidos</label>";
            echo "<input type='text' class='form-control' id='apellidos{$user->id}' name='apellidos' value='" . htmlspecialchars($user->apellidos) . "'>";
            echo "</div>";
            echo "<div class='mb-3'>";
            echo "<label for='email{$user->id}' class='form-label'>Email</label>";
            echo "<input type='email' class='form-control' id='email{$user->id}' name='email' value='" . htmlspecialchars($user->email) . "' required>";
            echo "</div>";
            echo "<div class='mb-3'>";
            echo "<label for='rol{$user->id}' class='form-label'>Rol</label>";
            echo "<select class='form-select' id='rol{$user->id}' name='rol'>";
            echo "<option value='user'" . ($user->rol === 'user' ? ' selected' : '') . ">Usuario</option>";
            echo "<option value='socio'" . ($user->rol === 'socio' ? ' selected' : '') . ">Socio</option>";
            echo "<option value='admin'" . ($user->rol === 'admin' ? ' selected' : '') . ">Administrador</option>";
            echo "</select>";
            echo "</div>";
            echo "<div class='form-check'>";
            echo "<input class='form-check-input' type='checkbox' id='activo{$user->id}' name='activo' value='1'" . ($user->activo ? ' checked' : '') . ">";
            echo "<label class='form-check-label' for='activo{$user->id}'>Usuario activo</label>";
            echo "</div>";
            echo "</div>";
            echo "<div class='modal-footer'>";
            echo "<button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancelar</button>";
            echo "<button type='submit' class='btn btn-primary'>Guardar Cambios</button>";
            echo "</div>";
            echo "</form>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
        
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        
    } else {
        echo "<p>⚠️ No hay usuarios en la base de datos</p>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error de base de datos: " . $e->getMessage() . "</p>";
}

echo "</div>";

// Scripts
echo "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>";
echo "<script>";
echo "function testEditUser(userId) {";
echo "    console.log('Editando usuario:', userId);";
echo "    if (typeof bootstrap !== 'undefined') {";
echo "        const modal = new bootstrap.Modal(document.getElementById('editModal' + userId));";
echo "        modal.show();";
echo "    } else {";
echo "        alert('Bootstrap no está disponible');";
echo "    }";
echo "}";
echo "";
echo "function testPasswordUser(userId) {";
echo "    console.log('Cambiando contraseña de usuario:', userId);";
echo "    alert('Función de cambiar contraseña para usuario ' + userId);";
echo "}";
echo "";
echo "document.addEventListener('DOMContentLoaded', function() {";
echo "    console.log('Página cargada correctamente');";
echo "    console.log('Bootstrap disponible:', typeof bootstrap !== 'undefined');";
echo "});";
echo "</script>";

echo "</body>";
echo "</html>";
?>
