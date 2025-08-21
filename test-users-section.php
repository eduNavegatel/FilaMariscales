<?php
// Script para diagnosticar el problema con la sección de usuarios registrados
echo "<h1>Diagnóstico de la Sección de Usuarios Registrados</h1>";

// Verificar configuración de base de datos
echo "<h2>Verificación de Base de Datos:</h2>";

try {
    require_once 'src/config/config.php';
    require_once 'src/models/Database.php';
    
    $db = new Database();
    echo "<p>✅ Conexión a la base de datos exitosa</p>";
    
    // Verificar si existe la tabla users
    $db->query("SHOW TABLES LIKE 'users'");
    $result = $db->single();
    
    if ($result) {
        echo "<p>✅ La tabla 'users' existe</p>";
        
        // Verificar estructura de la tabla
        $db->query("DESCRIBE users");
        $columns = $db->resultSet();
        
        echo "<h3>Estructura de la tabla users:</h3>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Llave</th><th>Por defecto</th></tr>";
        
        foreach ($columns as $column) {
            echo "<tr>";
            echo "<td>" . $column->Field . "</td>";
            echo "<td>" . $column->Type . "</td>";
            echo "<td>" . $column->Null . "</td>";
            echo "<td>" . $column->Key . "</td>";
            echo "<td>" . $column->Default . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Contar usuarios
        $db->query("SELECT COUNT(*) as count FROM users");
        $userCount = $db->single();
        echo "<p><strong>Total de usuarios:</strong> " . $userCount->count . "</p>";
        
        // Mostrar usuarios existentes
        $db->query("SELECT * FROM users ORDER BY fecha_registro DESC LIMIT 10");
        $users = $db->resultSet();
        
        if (!empty($users)) {
            echo "<h3>Usuarios existentes:</h3>";
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>ID</th><th>Nombre</th><th>Apellidos</th><th>Email</th><th>Rol</th><th>Fecha Registro</th><th>Activo</th></tr>";
            
            foreach ($users as $user) {
                echo "<tr>";
                echo "<td>" . $user->id . "</td>";
                echo "<td>" . htmlspecialchars($user->nombre) . "</td>";
                echo "<td>" . htmlspecialchars($user->apellidos) . "</td>";
                echo "<td>" . htmlspecialchars($user->email) . "</td>";
                echo "<td>" . htmlspecialchars($user->rol) . "</td>";
                echo "<td>" . $user->fecha_registro . "</td>";
                echo "<td>" . ($user->activo ? 'Sí' : 'No') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>❌ No hay usuarios en la base de datos</p>";
        }
        
    } else {
        echo "<p>❌ La tabla 'users' NO existe</p>";
        echo "<p>Creando la tabla...</p>";
        
        $sql = "CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(100) NOT NULL,
            apellidos VARCHAR(100),
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            rol ENUM('user', 'admin', 'socio') DEFAULT 'user',
            activo TINYINT(1) DEFAULT 1,
            fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            ultimo_acceso TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            token_reset VARCHAR(255) NULL,
            token_expira TIMESTAMP NULL
        )";
        
        if ($db->query($sql) && $db->execute()) {
            echo "<p>✅ Tabla 'users' creada exitosamente</p>";
            
            // Crear usuario administrador por defecto
            $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
            $adminSql = "INSERT INTO users (nombre, apellidos, email, password, rol, activo) 
                        VALUES ('Administrador', 'Sistema', 'admin@filamariscales.es', :password, 'admin', 1)";
            
            $db->query($adminSql);
            $db->bind(':password', $adminPassword);
            
            if ($db->execute()) {
                echo "<p>✅ Usuario administrador creado</p>";
                echo "<p><strong>Email:</strong> admin@filamariscales.es</p>";
                echo "<p><strong>Contraseña:</strong> admin123</p>";
            } else {
                echo "<p>❌ Error al crear usuario administrador</p>";
            }
        } else {
            echo "<p>❌ Error al crear la tabla</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error de conexión a la base de datos: " . $e->getMessage() . "</p>";
}

// Verificar el modelo User
echo "<h2>Verificación del Modelo User:</h2>";

if (file_exists('src/models/User.php')) {
    echo "<p>✅ El modelo User existe</p>";
    
    $modelContent = file_get_contents('src/models/User.php');
    $methods = [
        'getAllUsers' => 'Obtener todos los usuarios',
        'countAllUsers' => 'Contar usuarios',
        'getUserCount' => 'Contar usuarios para dashboard',
        'getRecentUsers' => 'Obtener usuarios recientes',
        'updateUser' => 'Actualizar usuario',
        'deleteUser' => 'Eliminar usuario'
    ];
    
    foreach ($methods as $method => $description) {
        if (strpos($modelContent, $method) !== false) {
            echo "<p>✅ $description existe</p>";
        } else {
            echo "<p>❌ $description NO existe</p>";
        }
    }
} else {
    echo "<p>❌ El modelo User NO existe</p>";
}

// Verificar el controlador AdminController
echo "<h2>Verificación del Controlador AdminController:</h2>";

if (file_exists('src/controllers/AdminController.php')) {
    echo "<p>✅ El controlador AdminController existe</p>";
    
    $controllerContent = file_get_contents('src/controllers/AdminController.php');
    $methods = [
        'usuarios' => 'Método usuarios',
        'editarUsuario' => 'Método editarUsuario',
        'crearUsuario' => 'Método crearUsuario',
        'eliminarUsuario' => 'Método eliminarUsuario'
    ];
    
    foreach ($methods as $method => $description) {
        if (strpos($controllerContent, $method) !== false) {
            echo "<p>✅ $description existe</p>";
        } else {
            echo "<p>❌ $description NO existe</p>";
        }
    }
} else {
    echo "<p>❌ El controlador AdminController NO existe</p>";
}

// Verificar las vistas
echo "<h2>Verificación de Vistas:</h2>";

$vistas = [
    'src/views/admin/users/index.php' => 'Vista de listado de usuarios',
    'src/views/admin/usuarios/editar.php' => 'Vista de edición de usuarios'
];

foreach ($vistas as $ruta => $descripcion) {
    if (file_exists($ruta)) {
        echo "<p>✅ $descripcion existe</p>";
    } else {
        echo "<p>❌ $descripcion NO existe</p>";
    }
}

// Simular el controlador
echo "<h2>Prueba del Controlador:</h2>";

try {
    require_once 'src/controllers/AdminController.php';
    
    // Crear una instancia del controlador (sin autenticación para pruebas)
    $adminController = new AdminController();
    
    // Probar métodos del modelo
    if ($adminController->userModel) {
        echo "<p>✅ Modelo User inicializado correctamente</p>";
        
        // Probar obtener usuarios
        $users = $adminController->userModel->getAllUsers(1, 10);
        echo "<p><strong>Usuarios obtenidos:</strong> " . count($users) . "</p>";
        
        // Probar contar usuarios
        $userCount = $adminController->userModel->countAllUsers();
        echo "<p><strong>Total de usuarios:</strong> $userCount</p>";
        
    } else {
        echo "<p>❌ Modelo User NO inicializado</p>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error al probar el controlador: " . $e->getMessage() . "</p>";
}

// Enlaces de prueba
echo "<h2>Enlaces de Prueba:</h2>";
echo "<p><a href='/prueba-php/public/admin/usuarios' target='_blank'>🔗 Administración - Usuarios</a></p>";
echo "<p><a href='/prueba-php/public/admin/dashboard' target='_blank'>🔗 Panel de Administración</a></p>";
echo "<p><a href='/prueba-php/public/admin/crearUsuario' target='_blank'>🔗 Crear Usuario</a></p>";

// Recomendaciones
echo "<h2>Recomendaciones:</h2>";
echo "<ol>";
echo "<li>Verifica que la tabla 'users' existe en la base de datos</li>";
echo "<li>Asegúrate de que hay usuarios registrados</li>";
echo "<li>Verifica que el modelo User tiene todos los métodos necesarios</li>";
echo "<li>Comprueba que el controlador AdminController funciona correctamente</li>";
echo "<li>Verifica que las vistas están en las rutas correctas</li>";
echo "<li>Si no hay usuarios, crea algunos para probar</li>";
echo "</ol>";

echo "<h2>Estado del Sistema:</h2>";
echo "<p>Este script ha verificado:</p>";
echo "<ul>";
echo "<li>✅ Conexión a la base de datos</li>";
echo "<li>✅ Existencia de la tabla users</li>";
echo "<li>✅ Estructura de la tabla</li>";
echo "<li>✅ Usuarios existentes</li>";
echo "<li>✅ Modelo User</li>";
echo "<li>✅ Controlador AdminController</li>";
echo "<li>✅ Vistas necesarias</li>";
echo "</ul>";
?>
