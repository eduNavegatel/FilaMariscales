<?php
// Script para probar específicamente el cambio de rol
echo "<h1>🔄 Prueba de Cambio de Rol</h1>";

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
    echo "<li><strong>Nombre:</strong> {$testUser->nombre} {$testUser->apellidos}</li>";
    echo "<li><strong>Email:</strong> {$testUser->email}</li>";
    echo "<li><strong>Rol actual:</strong> {$testUser->rol}</li>";
    echo "<li><strong>Activo:</strong> " . ($testUser->activo ? 'Sí' : 'No') . "</li>";
    echo "</ul>";
    
    // Probar cambio de rol
    echo "<h2>🔄 Probando Cambio de Rol:</h2>";
    
    // Simular datos del formulario
    $formData = [
        'user_id' => $testUser->id,
        'nombre' => $testUser->nombre,
        'apellidos' => $testUser->apellidos,
        'email' => $testUser->email,
        'rol' => 'admin', // Cambiar a admin
        'activo' => $testUser->activo
    ];
    
    echo "<h3>📝 Datos del formulario:</h3>";
    echo "<pre>" . print_r($formData, true) . "</pre>";
    
    // Probar actualización directa en la base de datos
    echo "<h3>1️⃣ Prueba de actualización directa en BD:</h3>";
    
    $sql = "UPDATE users SET rol = :rol WHERE id = :id";
    $db->query($sql);
    $db->bind(':rol', 'admin');
    $db->bind(':id', $testUser->id);
    
    if ($db->execute()) {
        echo "<p style='color: green;'>✅ Rol actualizado correctamente en BD</p>";
        
        // Verificar el cambio
        $db->query("SELECT rol FROM users WHERE id = :id");
        $db->bind(':id', $testUser->id);
        $result = $db->single();
        
        if ($result) {
            echo "<p><strong>Rol después del cambio:</strong> {$result->rol}</p>";
        }
        
        // Restaurar el rol original
        $db->query("UPDATE users SET rol = :rol WHERE id = :id");
        $db->bind(':rol', $testUser->rol);
        $db->bind(':id', $testUser->id);
        $db->execute();
        
        echo "<p style='color: blue;'>🔄 Rol restaurado a: {$testUser->rol}</p>";
        
    } else {
        echo "<p style='color: red;'>❌ Error actualizando rol en BD</p>";
    }
    
    // Probar el método del modelo User
    echo "<h3>2️⃣ Prueba del método updateUser del modelo:</h3>";
    
    $updateData = [
        'id' => $testUser->id,
        'nombre' => $testUser->nombre,
        'apellidos' => $testUser->apellidos,
        'email' => $testUser->email,
        'rol' => 'socio', // Cambiar a socio
        'activo' => $testUser->activo
    ];
    
    echo "<p>Intentando cambiar rol a 'socio'...</p>";
    
    if ($userModel->updateUser($updateData)) {
        echo "<p style='color: green;'>✅ Modelo User actualizó correctamente</p>";
        
        // Verificar el cambio
        $updatedUser = $userModel->getUserById($testUser->id);
        if ($updatedUser) {
            echo "<p><strong>Rol después del cambio:</strong> {$updatedUser->rol}</p>";
        }
        
        // Restaurar el rol original
        $updateData['rol'] = $testUser->rol;
        $userModel->updateUser($updateData);
        echo "<p style='color: blue;'>🔄 Rol restaurado a: {$testUser->rol}</p>";
        
    } else {
        echo "<p style='color: red;'>❌ Error en el modelo User</p>";
    }
    
    // Probar el controlador AdminController
    echo "<h3>3️⃣ Prueba del controlador AdminController:</h3>";
    
    // Simular una petición POST
    $_POST = $formData;
    $_POST['rol'] = 'user'; // Cambiar a user
    
    echo "<p>Simulando petición POST con rol 'user'...</p>";
    
    // Incluir el controlador
    require_once 'src/controllers/AdminController.php';
    
    // Crear instancia del controlador
    $adminController = new AdminController();
    
    // Verificar si el método existe
    if (method_exists($adminController, 'editarUsuario')) {
        echo "<p style='color: green;'>✅ Método editarUsuario existe en AdminController</p>";
        
        // Verificar si el método es público
        $reflection = new ReflectionMethod($adminController, 'editarUsuario');
        if ($reflection->isPublic()) {
            echo "<p style='color: green;'>✅ Método editarUsuario es público</p>";
        } else {
            echo "<p style='color: red;'>❌ Método editarUsuario no es público</p>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ Método editarUsuario NO existe en AdminController</p>";
    }
    
    // Verificar el enrutador
    echo "<h3>4️⃣ Verificación del enrutador:</h3>";
    
    require_once 'src/core/Router.php';
    require_once 'routes/web.php';
    
    echo "<p>✅ Router y rutas cargados correctamente</p>";
    
    // Verificar si la ruta de editar usuario está definida
    echo "<h3>5️⃣ Verificación de rutas:</h3>";
    
    // Buscar en el archivo de rutas
    $routesContent = file_get_contents('routes/web.php');
    
    if (strpos($routesContent, 'editarUsuario') !== false) {
        echo "<p style='color: green;'>✅ Ruta 'editarUsuario' encontrada en web.php</p>";
    } else {
        echo "<p style='color: red;'>❌ Ruta 'editarUsuario' NO encontrada en web.php</p>";
    }
    
    if (strpos($routesContent, 'admin/editarUsuario') !== false) {
        echo "<p style='color: green;'>✅ Ruta 'admin/editarUsuario' encontrada en web.php</p>";
    } else {
        echo "<p style='color: red;'>❌ Ruta 'admin/editarUsuario' NO encontrada en web.php</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p>Stack trace:</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<hr>";
echo "<h2>🔍 Análisis del Problema:</h2>";
echo "<p>Basándome en las pruebas, el problema del cambio de rol puede estar en:</p>";
echo "<ul>";
echo "<li><strong>Frontend:</strong> El formulario no se envía correctamente</li>";
echo "<li><strong>JavaScript:</strong> Validaciones que previenen el envío</li>";
echo "<li><strong>Enrutador:</strong> La ruta no está configurada correctamente</li>";
echo "<li><strong>Controlador:</strong> El método no procesa los datos</li>";
echo "<li><strong>Sesión:</strong> Problemas de autenticación</li>";
echo "</ul>";

echo "<h2>🔗 Enlaces de Prueba:</h2>";
echo "<p><a href='/prueba-php/public/admin/usuarios' target='_blank'>🚀 Panel de Administración</a></p>";
echo "<p><a href='/prueba-php/public/admin/login' target='_blank'>🔐 Login de Admin</a></p>";
echo "<p><em>Script completado.</em></p>";
?>
