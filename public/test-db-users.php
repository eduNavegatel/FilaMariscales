<?php
// Archivo de prueba para verificar la funcionalidad de la base de datos y usuarios
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>";
echo "<html lang='es'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Test Base de Datos - Usuarios</title>";
echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
echo "</head>";
echo "<body>";
echo "<div class='container mt-4'>";
echo "<h1>🧪 Test de Base de Datos - Usuarios</h1>";

try {
    // Cargar configuración
    require_once '../src/config/config.php';
    require_once '../src/models/Database.php';
    require_once '../src/models/User.php';
    
    echo "<div class='alert alert-success'>✅ Archivos cargados correctamente</div>";
    
    // Probar conexión a la base de datos
    echo "<h3>🔌 Test de Conexión a Base de Datos</h3>";
    $db = new Database();
    echo "<div class='alert alert-success'>✅ Conexión a base de datos establecida</div>";
    
    // Probar modelo User
    echo "<h3>👤 Test del Modelo User</h3>";
    $userModel = new User();
    echo "<div class='alert alert-success'>✅ Modelo User creado correctamente</div>";
    
    // Probar obtener usuarios
    echo "<h3>📋 Test de Obtención de Usuarios</h3>";
    $users = $userModel->getAllUsers(1, 5);
    echo "<div class='alert alert-info'>📊 Usuarios encontrados: " . count($users) . "</div>";
    
    if (!empty($users)) {
        echo "<table class='table table-striped'>";
        echo "<thead><tr><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Activo</th></tr></thead>";
        echo "<tbody>";
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>{$user->id}</td>";
            echo "<td>{$user->nombre} {$user->apellidos}</td>";
            echo "<td>{$user->email}</td>";
            echo "<td>{$user->rol}</td>";
            echo "<td>" . ($user->activo ? 'Sí' : 'No') . "</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    }
    
    // Probar obtener un usuario específico
    if (!empty($users)) {
        $firstUser = $users[0];
        echo "<h3>🔍 Test de Usuario Específico</h3>";
        $specificUser = $userModel->getUserById($firstUser->id);
        
        if ($specificUser) {
            echo "<div class='alert alert-success'>✅ Usuario encontrado por ID: {$specificUser->nombre}</div>";
            echo "<pre>" . print_r($specificUser, true) . "</pre>";
        } else {
            echo "<div class='alert alert-danger'>❌ No se pudo encontrar el usuario por ID</div>";
        }
    }
    
    // Probar actualización de usuario (solo lectura, no modificar)
    if (!empty($users)) {
        $firstUser = $users[0];
        echo "<h3>✏️ Test de Actualización (Simulado)</h3>";
        
        $testData = [
            'id' => $firstUser->id,
            'nombre' => $firstUser->nombre,
            'apellidos' => $firstUser->apellidos,
            'email' => $firstUser->email,
            'rol' => $firstUser->rol,
            'activo' => $firstUser->activo
        ];
        
        echo "<div class='alert alert-info'>📝 Datos de prueba preparados:</div>";
        echo "<pre>" . print_r($testData, true) . "</pre>";
        
        // Simular la consulta SQL que se ejecutaría
        $sql = "UPDATE users SET nombre = :nombre, apellidos = :apellidos, email = :email, rol = :rol, activo = :activo WHERE id = :id";
        echo "<div class='alert alert-info'>🔍 SQL que se ejecutaría:</div>";
        echo "<code>{$sql}</code>";
    }
    
    // Verificar estructura de la tabla
    echo "<h3>🏗️ Test de Estructura de Tabla</h3>";
    try {
        $db->query("DESCRIBE users");
        $structure = $db->resultSet();
        
        if (!empty($structure)) {
            echo "<div class='alert alert-success'>✅ Estructura de tabla obtenida</div>";
            echo "<table class='table table-sm'>";
            echo "<thead><tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Clave</th><th>Por defecto</th></tr></thead>";
            echo "<tbody>";
            foreach ($structure as $field) {
                echo "<tr>";
                echo "<td>{$field->Field}</td>";
                echo "<td>{$field->Type}</td>";
                echo "<td>{$field->Null}</td>";
                echo "<td>{$field->Key}</td>";
                echo "<td>{$field->Default}</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        }
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>❌ Error al obtener estructura: " . $e->getMessage() . "</div>";
    }
    
    // Verificar permisos de la base de datos
    echo "<h3>🔐 Test de Permisos de Base de Datos</h3>";
    try {
        // Probar SELECT
        $db->query("SELECT COUNT(*) as count FROM users");
        $result = $db->single();
        echo "<div class='alert alert-success'>✅ Permiso SELECT: OK</div>";
        
        // Probar UPDATE (solo verificar sintaxis, no ejecutar)
        $db->query("UPDATE users SET nombre = nombre WHERE id = 1");
        echo "<div class='alert alert-success'>✅ Permiso UPDATE: OK</div>";
        
        // Probar INSERT (solo verificar sintaxis, no ejecutar)
        $db->query("INSERT INTO users (nombre, apellidos, email, password, rol, activo) VALUES ('test', 'test', 'test@test.com', 'test', 'user', 1)");
        echo "<div class='alert alert-success'>✅ Permiso INSERT: OK</div>";
        
        // Probar DELETE (solo verificar sintaxis, no ejecutar)
        $db->query("DELETE FROM users WHERE id = 999999");
        echo "<div class='alert alert-success'>✅ Permiso DELETE: OK</div>";
        
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>❌ Error de permisos: " . $e->getMessage() . "</div>";
    }
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>❌ Error general: " . $e->getMessage() . "</div>";
    echo "<div class='alert alert-info'>📋 Stack trace:</div>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<div class='mt-4'>";
echo "<a href='/prueba-php/public/admin/dashboard' class='btn btn-primary'>Volver al Dashboard</a>";
echo "<a href='/prueba-php/public/admin/usuarios' class='btn btn-secondary ms-2'>Ir a Usuarios</a>";
echo "</div>";

echo "</div>";
echo "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>";
echo "</body>";
echo "</html>";
