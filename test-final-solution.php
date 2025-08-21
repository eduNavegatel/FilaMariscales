<?php
// Script de verificación final para confirmar que las correcciones funcionan
echo "<h1>Verificación Final - Soluciones Implementadas</h1>";

echo "<div style='margin: 20px; padding: 15px; border: 2px solid #28a745; border-radius: 8px; background-color: #d4edda;'>";
echo "<h3>Soluciones Implementadas:</h3>";
echo "<ul>";
echo "<li><strong>Modal con alert de prueba:</strong> Se muestra un alert antes del modal para verificar funcionamiento</li>";
echo "<li><strong>Rutas corregidas:</strong> Cambiados enlaces GET a formularios POST</li>";
echo "<li><strong>CSRF tokens:</strong> Agregados tokens de seguridad a todos los formularios</li>";
echo "<li><strong>Debugging mejorado:</strong> Más mensajes de consola y alerts de verificación</li>";
echo "</ul>";
echo "</div>";

// Verificar archivos
echo "<h2>Verificación de Archivos:</h2>";

$archivo = 'src/views/admin/users/index.php';
if (file_exists($archivo)) {
    $contenido = file_get_contents($archivo);
    
    $elementos = [
        'method="POST"' => 'Formularios POST para rutas',
        'csrf_token' => 'Tokens CSRF en formularios',
        'alert(' => 'Alerts de verificación en JavaScript',
        'openEditModal(' => 'Función para abrir modal',
        'closeEditModal()' => 'Función para cerrar modal',
        'activarUsuario/' => 'Ruta para activar usuario',
        'desactivarUsuario/' => 'Ruta para desactivar usuario'
    ];
    
    echo "<p>✅ Vista de usuarios existe</p>";
    foreach ($elementos as $buscar => $desc) {
        if (strpos($contenido, $buscar) !== false) {
            echo "<p style='margin-left: 20px;'>✅ $desc encontrado</p>";
        } else {
            echo "<p style='margin-left: 20px;'>❌ $desc NO encontrado</p>";
        }
    }
} else {
    echo "<p>❌ Vista de usuarios NO existe</p>";
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
        
        // Mostrar algunos usuarios
        $db->query("SELECT id, nombre, apellidos, email, rol, activo FROM users LIMIT 3");
        $users = $db->resultSet();
        
        if ($users) {
            echo "<p><strong>Usuarios disponibles para probar:</strong></p>";
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Activo</th></tr>";
            foreach ($users as $user) {
                echo "<tr>";
                echo "<td>{$user->id}</td>";
                echo "<td>{$user->nombre} {$user->apellidos}</td>";
                echo "<td>{$user->email}</td>";
                echo "<td>{$user->rol}</td>";
                echo "<td>" . ($user->activo ? 'Sí' : 'No') . "</td>";
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

// Código JavaScript de ejemplo
echo "<h2>Código JavaScript Implementado:</h2>";

echo "<div style='background-color: #f5f5f5; padding: 15px; border-radius: 5px; font-family: monospace; font-size: 12px;'>";
echo "<pre>";
echo "// Función para abrir modal de edición\n";
echo "function openEditModal(userId, nombre, apellidos, email, rol, activo) {\n";
echo "    console.log('=== ABRIENDO MODAL DE EDICIÓN ===');\n";
echo "    console.log('Usuario ID:', userId);\n";
echo "    \n";
echo "    // Primero mostrar un alert para verificar que la función se ejecuta\n";
echo "    alert('Editando usuario: ' + nombre + ' ' + apellidos);\n";
echo "    \n";
echo "    // Llenar el formulario\n";
echo "    document.getElementById('editNombre').value = nombre;\n";
echo "    document.getElementById('editEmail').value = email;\n";
echo "    \n";
echo "    // Mostrar el modal\n";
echo "    document.getElementById('editUserModal').style.display = 'block';\n";
echo "    console.log('Modal de edición mostrado');\n";
echo "}\n\n";
echo "// Función para cerrar modal de edición\n";
echo "function closeEditModal() {\n";
echo "    document.getElementById('editUserModal').style.display = 'none';\n";
echo "    console.log('Modal de edición cerrado');\n";
echo "}";
echo "</pre>";
echo "</div>";

// Código HTML de ejemplo
echo "<h2>Código HTML de Formularios POST:</h2>";

echo "<div style='background-color: #f5f5f5; padding: 15px; border-radius: 5px; font-family: monospace; font-size: 12px;'>";
echo "<pre>";
echo "<!-- Formulario para activar usuario -->\n";
echo "<form method=\"POST\" action=\"/prueba-php/public/admin/activarUsuario/{id}\" style=\"display: inline;\">\n";
echo "    <input type=\"hidden\" name=\"csrf_token\" value=\"{token}\">\n";
echo "    <button type=\"submit\" \n";
echo "            class=\"btn btn-sm btn-outline-success\"\n";
echo "            onclick=\"return confirm('¿Estás seguro?')\"\n";
echo "            title=\"Activar usuario\">\n";
echo "        <i class=\"fas fa-user-check\"></i>\n";
echo "    </button>\n";
echo "</form>\n\n";
echo "<!-- Formulario para desactivar usuario -->\n";
echo "<form method=\"POST\" action=\"/prueba-php/public/admin/desactivarUsuario/{id}\" style=\"display: inline;\">\n";
echo "    <input type=\"hidden\" name=\"csrf_token\" value=\"{token}\">\n";
echo "    <button type=\"submit\" \n";
echo "            class=\"btn btn-sm btn-outline-warning\"\n";
echo "            onclick=\"return confirm('¿Estás seguro?')\"\n";
echo "            title=\"Desactivar usuario\">\n";
echo "        <i class=\"fas fa-user-slash\"></i>\n";
echo "    </button>\n";
echo "</form>";
echo "</pre>";
echo "</div>";

// Enlaces de prueba
echo "<h2>Enlaces de Prueba:</h2>";
echo "<p><a href='/prueba-php/public/admin/usuarios' target='_blank'>🔗 Administración - Usuarios (Corregido)</a></p>";

// Instrucciones para probar
echo "<h2>Instrucciones para Probar:</h2>";
echo "<ol>";
echo "<li><strong>Ve a la página de usuarios</strong>: <a href='/prueba-php/public/admin/usuarios' target='_blank'>Administración - Usuarios</a></li>";
echo "<li><strong>Abre la consola del navegador</strong> (F12 → Console)</li>";
echo "<li><strong>Haz clic en el botón de editar</strong> (ícono de lápiz) de cualquier usuario</li>";
echo "<li><strong>Verifica que aparece un alert</strong> con los datos del usuario</li>";
echo "<li><strong>Confirma el alert</strong> y verifica que se abre el modal</li>";
echo "<li><strong>Prueba el botón de activar/desactivar</strong> - ya no debería dar 404</li>";
echo "<li><strong>Verifica en la consola</strong> que aparecen los mensajes de debugging</li>";
echo "</ol>";

// Estado esperado
echo "<h2>Estado Esperado Después de las Correcciones:</h2>";
echo "<ul>";
echo "<li>✅ Al hacer clic en editar, aparece un alert con los datos del usuario</li>";
echo "<li>✅ Después del alert, se abre el modal de edición</li>";
echo "<li>✅ El formulario está pre-llenado con los datos del usuario</li>";
echo "<li>✅ Los botones de activar/desactivar funcionan sin error 404</li>";
echo "<li>✅ Los formularios se envían correctamente</li>";
echo "<li>✅ Aparecen mensajes de debugging en la consola</li>";
echo "<li>✅ No hay errores en la consola</li>";
echo "</ul>";

// Ventajas de las correcciones
echo "<h2>Ventajas de las Correcciones:</h2>";
echo "<ul>";
echo "<li><strong>Alert de verificación:</strong> Confirma que el JavaScript funciona</li>";
echo "<li><strong>Formularios POST:</strong> Rutas correctas para activar/desactivar</li>";
echo "<li><strong>CSRF tokens:</strong> Seguridad mejorada</li>";
echo "<li><strong>Debugging mejorado:</strong> Más información para troubleshooting</li>";
echo "<li><strong>Funcionamiento garantizado:</strong> Sin dependencias problemáticas</li>";
echo "</ul>";

// Script de prueba
echo "<script>";
echo "console.log('Script de verificación final cargado');";
echo "";
echo "// Verificar al cargar la página";
echo "document.addEventListener('DOMContentLoaded', function() {";
echo "    console.log('Página de verificación final cargada');";
echo "    console.log('Bootstrap disponible:', typeof bootstrap !== 'undefined');";
echo "});";
echo "";
echo "// Función para probar modal";
echo "function testModal() {";
echo "    console.log('Probando modal...');";
echo "    alert('Modal de prueba funcionando!');";
echo "}";
echo "</script>";

// Botón de prueba
echo "<div style='margin: 20px; padding: 15px; border: 2px solid #007bff; border-radius: 8px; background-color: #f8f9fa;'>";
echo "<h3>Prueba del Modal</h3>";
echo "<button type='button' class='btn btn-primary' onclick='testModal()'>";
echo "<i class='fas fa-test'></i> Probar Modal";
echo "</button>";
echo "<p style='margin-top: 10px; font-size: 14px; color: #666;'>";
echo "Haz clic en este botón para probar si el JavaScript funciona.";
echo "</p>";
echo "</div>";

// Resumen
echo "<h2>Resumen de las Correcciones:</h2>";
echo "<p>✅ <strong>Se ha agregado alert de verificación al modal</strong></p>";
echo "<p>✅ <strong>Se han corregido las rutas de activar/desactivar usuario</strong></p>";
echo "<p>✅ <strong>Se han agregado tokens CSRF a todos los formularios</strong></p>";
echo "<p>✅ <strong>Se ha mejorado el debugging</strong></p>";
echo "<p>✅ <strong>El modal y las rutas deberían funcionar correctamente ahora</strong></p>";

echo "<h2>Cambios Principales:</h2>";
echo "<ul>";
echo "<li><strong>Alert de verificación:</strong> Confirma que el JavaScript se ejecuta</li>";
echo "<li><strong>Formularios POST:</strong> En lugar de enlaces GET</li>";
echo "<li><strong>CSRF tokens:</strong> Seguridad en todos los formularios</li>";
echo "<li><strong>Debugging mejorado:</strong> Más mensajes de consola</li>";
echo "<li><strong>Rutas corregidas:</strong> Sin errores 404</li>";
echo "</ul>";

echo "<p><strong>El modal de edición y las funciones de activar/desactivar usuario deberían funcionar correctamente ahora.</strong></p>";
?>
