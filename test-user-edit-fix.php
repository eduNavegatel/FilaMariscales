<?php
// Script para verificar la funcionalidad de edición de usuarios
echo "<h1>🔧 Verificación de Edición de Usuarios</h1>";

// Incluir configuración
require_once 'src/config/config.php';

echo "<h2>📋 Verificación de Configuración</h2>";
echo "<p><strong>URL_ROOT:</strong> " . URL_ROOT . "</p>";

// Verificar controlador
echo "<h2>🎮 Verificación del Controlador</h2>";

$controllerFile = 'src/controllers/AdminController.php';
if (file_exists($controllerFile)) {
    echo "<p>✅ Archivo <code>AdminController.php</code> existe</p>";
    
    $controllerContent = file_get_contents($controllerFile);
    
    // Verificar método editarUsuario
    if (strpos($controllerContent, 'editarUsuario') !== false) {
        echo "<p>✅ Método <code>editarUsuario</code> existe</p>";
        
        // Verificar que no use FILTER_SANITIZE_STRING
        if (strpos($controllerContent, 'FILTER_SANITIZE_STRING') === false) {
            echo "<p>✅ No usa <code>FILTER_SANITIZE_STRING</code> (deprecado)</p>";
        } else {
            echo "<p>❌ Aún usa <code>FILTER_SANITIZE_STRING</code> (deprecado)</p>";
        }
        
        // Verificar manejo del campo activo
        if (strpos($controllerContent, 'activo') !== false) {
            echo "<p>✅ Maneja el campo <code>activo</code></p>";
        } else {
            echo "<p>❌ No maneja el campo <code>activo</code></p>";
        }
        
    } else {
        echo "<p>❌ Método <code>editarUsuario</code> NO existe</p>";
    }
} else {
    echo "<p>❌ Archivo <code>AdminController.php</code> no encontrado</p>";
}

// Verificar modelo User
echo "<h2>📊 Verificación del Modelo User</h2>";

$modelFile = 'src/models/User.php';
if (file_exists($modelFile)) {
    echo "<p>✅ Archivo <code>User.php</code> existe</p>";
    
    $modelContent = file_get_contents($modelFile);
    
    // Verificar método updateUser
    if (strpos($modelContent, 'updateUser') !== false) {
        echo "<p>✅ Método <code>updateUser</code> existe</p>";
        
        // Verificar que actualice rol y activo
        if (strpos($modelContent, 'rol = :rol') !== false) {
            echo "<p>✅ Actualiza el campo <code>rol</code></p>";
        } else {
            echo "<p>❌ No actualiza el campo <code>rol</code></p>";
        }
        
        if (strpos($modelContent, 'activo = :activo') !== false) {
            echo "<p>✅ Actualiza el campo <code>activo</code></p>";
        } else {
            echo "<p>❌ No actualiza el campo <code>activo</code></p>";
        }
        
    } else {
        echo "<p>❌ Método <code>updateUser</code> NO existe</p>";
    }
} else {
    echo "<p>❌ Archivo <code>User.php</code> no encontrado</p>";
}

// Verificar vista
echo "<h2>👁️ Verificación de la Vista</h2>";

$viewFile = 'src/views/admin/users/index.php';
if (file_exists($viewFile)) {
    echo "<p>✅ Archivo <code>users/index.php</code> existe</p>";
    
    $viewContent = file_get_contents($viewFile);
    
    // Verificar formulario de edición
    if (strpos($viewContent, 'editUserForm') !== false) {
        echo "<p>✅ Formulario <code>editUserForm</code> existe</p>";
        
        // Verificar campos del formulario
        $formFields = [
            'nombre' => 'Campo nombre',
            'apellidos' => 'Campo apellidos', 
            'email' => 'Campo email',
            'rol' => 'Campo rol (select)',
            'activo' => 'Campo activo (checkbox)'
        ];
        
        foreach ($formFields as $field => $description) {
            if (strpos($viewContent, 'name="' . $field . '"') !== false) {
                echo "<p>✅ $description existe</p>";
            } else {
                echo "<p>❌ $description NO existe</p>";
            }
        }
        
        // Verificar JavaScript
        if (strpos($viewContent, 'openEditModal') !== false) {
            echo "<p>✅ Función <code>openEditModal</code> existe</p>";
        } else {
            echo "<p>❌ Función <code>openEditModal</code> NO existe</p>";
        }
        
    } else {
        echo "<p>❌ Formulario <code>editUserForm</code> NO existe</p>";
    }
} else {
    echo "<p>❌ Archivo <code>users/index.php</code> no encontrado</p>";
}

// Verificar rutas
echo "<h2>🛣️ Verificación de Rutas</h2>";

$routesFile = 'routes/web.php';
if (file_exists($routesFile)) {
    echo "<p>✅ Archivo <code>routes/web.php</code> existe</p>";
    
    $routesContent = file_get_contents($routesFile);
    
    if (strpos($routesContent, 'editarUsuario') !== false) {
        echo "<p>✅ Ruta <code>editarUsuario</code> definida</p>";
    } else {
        echo "<p>❌ Ruta <code>editarUsuario</code> NO definida</p>";
    }
} else {
    echo "<p>❌ Archivo <code>routes/web.php</code> no encontrado</p>";
}

// Mostrar instrucciones de prueba
echo "<h2>🧪 Instrucciones de Prueba</h2>";
echo "<div style='background: #f8f9fa; padding: 20px; border-radius: 8px;'>";

echo "<h4>📝 Pasos para probar la edición:</h4>";
echo "<ol>";
echo "<li>Accede a <a href='" . URL_ROOT . "/admin/usuarios' target='_blank'>Gestión de Usuarios</a></li>";
echo "<li>Haz clic en el botón <strong>Editar</strong> de cualquier usuario</li>";
echo "<li>Modifica el <strong>rol</strong> (Usuario/Socio/Administrador)</li>";
echo "<li>Cambia el estado <strong>activo/inactivo</strong></li>";
echo "<li>Haz clic en <strong>Guardar Cambios</strong></li>";
echo "<li>Verifica que los cambios se guardaron correctamente</li>";
echo "</ol>";

echo "<h4>🔍 Verificaciones específicas:</h4>";
echo "<ul>";
echo "<li><strong>Rol:</strong> Debe cambiar entre 'user', 'socio', 'admin'</li>";
echo "<li><strong>Estado:</strong> Debe cambiar entre activo (1) e inactivo (0)</li>";
echo "<li><strong>Mensajes:</strong> Debe mostrar 'Usuario actualizado correctamente'</li>";
echo "<li><strong>Redirección:</strong> Debe volver a la lista de usuarios</li>";
echo "</ul>";

echo "</div>";

// Mostrar posibles problemas y soluciones
echo "<h2>⚠️ Posibles Problemas y Soluciones</h2>";
echo "<div style='background: #fff3cd; padding: 20px; border-radius: 8px; border-left: 4px solid #ffc107;'>";

echo "<h4>🚨 Problemas comunes:</h4>";
echo "<ul>";
echo "<li><strong>Token CSRF:</strong> Si falla la validación, verificar SecurityHelper</li>";
echo "<li><strong>Campo activo:</strong> Asegurar que el checkbox tenga <code>value='1'</code></li>";
echo "<li><strong>Rutas:</strong> Verificar que las rutas usen <code>URL_ROOT</code></li>";
echo "<li><strong>Base de datos:</strong> Verificar que la tabla <code>users</code> tenga campos <code>rol</code> y <code>activo</code></li>";
echo "<li><strong>Permisos:</strong> Verificar que el usuario tenga permisos de escritura en la BD</li>";
echo "</ul>";

echo "<h4>🔧 Soluciones:</h4>";
echo "<ul>";
echo "<li><strong>Debug:</strong> Revisar logs de error en <code>error_log</code></li>";
echo "<li><strong>Validación:</strong> Verificar que los datos lleguen correctamente al controlador</li>";
echo "<li><strong>SQL:</strong> Verificar que la consulta UPDATE se ejecute correctamente</li>";
echo "<li><strong>Redirección:</strong> Asegurar que las rutas de redirección sean correctas</li>";
echo "</ul>";

echo "</div>";

// Enlaces de prueba
echo "<h2>🔗 Enlaces de Prueba</h2>";
echo "<p><a href='" . URL_ROOT . "/admin/usuarios' class='btn btn-primary' target='_blank'>🚀 Ir a Gestión de Usuarios</a></p>";
echo "<p><a href='" . URL_ROOT . "/admin' class='btn btn-secondary' target='_blank'>🏠 Ir al Dashboard</a></p>";

// Verificar base de datos
echo "<h2>🗄️ Verificación de Base de Datos</h2>";
echo "<p>Para verificar la estructura de la tabla <code>users</code>:</p>";
echo "<div style='background: #e7f3ff; padding: 15px; border-radius: 8px; font-family: monospace;'>";
echo "DESCRIBE users;<br>";
echo "SELECT id, nombre, email, rol, activo FROM users LIMIT 5;";
echo "</div>";

echo "<hr>";
echo "<p><em>Script de verificación completado. Revisa los logs de error si la edición no funciona.</em></p>";
?>
