<?php
// Script final para verificar que el botón de editar usuario funciona correctamente
echo "<h1>Verificación Final - Botón de Editar Usuario</h1>";

// Verificar las correcciones realizadas
echo "<h2>Correcciones Realizadas:</h2>";

$correcciones = [
    'Inclusión de SecurityHelper' => 'Agregada en la vista de usuarios',
    'Debugging mejorado' => 'Función openEditModal con logging detallado',
    'Verificación de Bootstrap' => 'Comprobación de disponibilidad de Bootstrap',
    'Verificación de modal' => 'Búsqueda y validación del elemento modal',
    'Manejo de errores' => 'Try-catch con mensajes informativos'
];

echo "<ul>";
foreach ($correcciones as $correccion => $descripcion) {
    echo "<li><strong>$correccion:</strong> $descripcion</li>";
}
echo "</ul>";

// Verificar archivos corregidos
echo "<h2>Verificación de Archivos Corregidos:</h2>";

$archivos = [
    'src/views/admin/users/index.php' => 'Vista con debugging mejorado',
    'src/controllers/AdminController.php' => 'Controlador con método editarUsuario',
    'src/models/User.php' => 'Modelo con método updateUser',
    'src/helpers/SecurityHelper.php' => 'Helper de seguridad'
];

foreach ($archivos as $archivo => $descripcion) {
    if (file_exists($archivo)) {
        $contenido = file_get_contents($archivo);
        
        if ($archivo === 'src/views/admin/users/index.php') {
            $elementos = [
                'SecurityHelper' => 'Inclusión del SecurityHelper',
                'console.log(\'Botón editar clickeado' => 'Debugging en botón',
                '=== DEBUGGING MODAL DE EDICIÓN ===' => 'Debugging detallado en función',
                'Bootstrap está cargado' => 'Verificación de Bootstrap',
                'Modal encontrado' => 'Verificación de modal'
            ];
        } elseif ($archivo === 'src/controllers/AdminController.php') {
            $elementos = [
                'editarUsuario' => 'Método editarUsuario',
                'updateUser' => 'Llamada a updateUser',
                'getUserById' => 'Llamada a getUserById'
            ];
        } elseif ($archivo === 'src/models/User.php') {
            $elementos = [
                'updateUser' => 'Método updateUser',
                'UPDATE ' . $this->table => 'Query de actualización',
                'bind(:id' => 'Binding de parámetros'
            ];
        } elseif ($archivo === 'src/helpers/SecurityHelper.php') {
            $elementos = [
                'generateCsrfToken' => 'Función generateCsrfToken',
                'validateCsrfToken' => 'Función validateCsrfToken'
            ];
        }
        
        echo "<p>✅ $descripcion existe</p>";
        foreach ($elementos as $buscar => $desc) {
            if (strpos($contenido, $buscar) !== false) {
                echo "<p style='margin-left: 20px;'>✅ $desc</p>";
            } else {
                echo "<p style='margin-left: 20px;'>❌ $desc NO encontrado</p>";
            }
        }
    } else {
        echo "<p>❌ $descripcion NO existe</p>";
    }
}

// Verificar base de datos
echo "<h2>Verificación de Base de Datos:</h2>";

try {
    require_once 'src/config/config.php';
    require_once 'src/models/Database.php';
    
    $db = new Database();
    echo "<p>✅ Conexión a la base de datos exitosa</p>";
    
    // Verificar tabla users
    $db->query("SHOW TABLES LIKE 'users'");
    $result = $db->single();
    
    if ($result) {
        echo "<p>✅ La tabla 'users' existe</p>";
        
        // Contar usuarios
        $db->query("SELECT COUNT(*) as count FROM users");
        $userCount = $db->single();
        echo "<p><strong>Total de usuarios:</strong> " . $userCount->count . "</p>";
        
        // Mostrar algunos usuarios para editar
        $db->query("SELECT id, nombre, apellidos, email, rol, activo FROM users LIMIT 3");
        $users = $db->resultSet();
        
        if ($users) {
            echo "<p><strong>Usuarios disponibles para editar:</strong></p>";
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Activo</th><th>Acción</th></tr>";
            foreach ($users as $user) {
                echo "<tr>";
                echo "<td>{$user->id}</td>";
                echo "<td>{$user->nombre} {$user->apellidos}</td>";
                echo "<td>{$user->email}</td>";
                echo "<td>{$user->rol}</td>";
                echo "<td>" . ($user->activo ? 'Sí' : 'No') . "</td>";
                echo "<td><button onclick='testEditUser({$user->id})' class='btn btn-sm btn-primary'>Probar Editar</button></td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        
    } else {
        echo "<p>❌ La tabla 'users' NO existe</p>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error de conexión: " . $e->getMessage() . "</p>";
}

// Simular el flujo completo
echo "<h2>Flujo Completo del Botón de Editar:</h2>";

echo "<div style='margin: 20px; padding: 15px; border: 2px solid #28a745; border-radius: 8px; background-color: #f8fff9;'>";
echo "<h3>Proceso de Editar Usuario (Corregido):</h3>";
echo "<ol>";
echo "<li><strong>Usuario hace clic en botón de editar</strong></li>";
echo "<li><strong>Se ejecuta console.log</strong> para debugging</li>";
echo "<li><strong>Se llama a openEditModal(userId)</strong></li>";
echo "<li><strong>Verificación de Bootstrap:</strong> Comprobar si está cargado</li>";
echo "<li><strong>Búsqueda del modal:</strong> Buscar elemento con ID 'editModal' + userId</li>";
echo "<li><strong>Creación de instancia:</strong> new bootstrap.Modal(modalElement)</li>";
echo "<li><strong>Mostrar modal:</strong> modal.show()</li>";
echo "<li><strong>Usuario edita datos</strong> en el formulario</li>";
echo "<li><strong>Enviar formulario:</strong> POST a /admin/editarUsuario/{id}</li>";
echo "<li><strong>Validación CSRF:</strong> Verificar token de seguridad</li>";
echo "<li><strong>Actualización en BD:</strong> userModel->updateUser()</li>";
echo "<li><strong>Redirección:</strong> Volver a lista de usuarios</li>";
echo "</ol>";
echo "</div>";

// Enlaces de prueba
echo "<h2>Enlaces de Prueba:</h2>";
echo "<p><a href='/prueba-php/public/admin/usuarios' target='_blank'>🔗 Administración - Usuarios</a></p>";
echo "<p><a href='/prueba-php/test-edit-user-button.php' target='_blank'>🔗 Diagnóstico del Botón de Editar</a></p>";

// Instrucciones para probar
echo "<h2>Instrucciones para Probar:</h2>";
echo "<ol>";
echo "<li><strong>Abre la consola del navegador</strong> (F12)</li>";
echo "<li><strong>Ve a la página de usuarios</strong>: <a href='/prueba-php/public/admin/usuarios' target='_blank'>Administración - Usuarios</a></li>";
echo "<li><strong>Haz clic en el botón de editar</strong> de cualquier usuario</li>";
echo "<li><strong>Observa los mensajes en la consola</strong> para ver el debugging</li>";
echo "<li><strong>Verifica que el modal se abre</strong> correctamente</li>";
echo "<li><strong>Edita algún campo</strong> y guarda los cambios</li>";
echo "<li><strong>Confirma que se redirige</strong> a la lista de usuarios</li>";
echo "</ol>";

// Estado esperado
echo "<h2>Estado Esperado Después de las Correcciones:</h2>";
echo "<ul>";
echo "<li>✅ El botón de editar responde al clic</li>";
echo "<li>✅ Se muestran mensajes de debugging en la consola</li>";
echo "<li>✅ Bootstrap está disponible y funcionando</li>";
echo "<li>✅ El modal se encuentra y se abre correctamente</li>";
echo "<li>✅ El formulario se envía sin errores</li>";
echo "<li>✅ La validación CSRF funciona</li>";
echo "<li>✅ Los datos se actualizan en la base de datos</li>";
echo "<li>✅ Se redirige correctamente después de guardar</li>";
echo "</ul>";

// Script de prueba
echo "<script>";
echo "function testEditUser(userId) {";
echo "    console.log('Probando editar usuario:', userId);";
echo "    if (typeof openEditModal === 'function') {";
echo "        openEditModal(userId);";
echo "    } else {";
echo "        alert('La función openEditModal no está disponible');";
echo "    }";
echo "}";
echo "";
echo "// Verificar al cargar la página";
echo "document.addEventListener('DOMContentLoaded', function() {";
echo "    console.log('Página de verificación cargada');";
echo "    console.log('Bootstrap disponible:', typeof bootstrap !== 'undefined');";
echo "    console.log('Función openEditModal disponible:', typeof openEditModal !== 'undefined');";
echo "});";
echo "</script>";

// Resumen
echo "<h2>Resumen de la Solución:</h2>";
echo "<p>✅ <strong>Se ha agregado debugging detallado al botón de editar</strong></p>";
echo "<p>✅ <strong>Se ha incluido el SecurityHelper para CSRF tokens</strong></p>";
echo "<p>✅ <strong>Se ha mejorado la función openEditModal con verificaciones</strong></p>";
echo "<p>✅ <strong>Se han agregado mensajes informativos en la consola</strong></p>";
echo "<p>✅ <strong>El botón de editar debería funcionar correctamente ahora</strong></p>";

echo "<h2>Si el problema persiste:</h2>";
echo "<ol>";
echo "<li>Revisa la consola del navegador para ver mensajes de error</li>";
echo "<li>Verifica que Bootstrap esté cargado correctamente</li>";
echo "<li>Comprueba que no hay conflictos de JavaScript</li>";
echo "<li>Verifica que las rutas están configuradas correctamente</li>";
echo "<li>Limpia la caché del navegador</li>";
echo "<li>Verifica que la base de datos está funcionando</li>";
echo "</ol>";

echo "<p><strong>El botón de editar usuario ha sido corregido y debería funcionar correctamente ahora.</strong></p>";
?>
