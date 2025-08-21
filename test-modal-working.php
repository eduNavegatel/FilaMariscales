<?php
// Script de prueba específico para verificar que el modal funciona
echo "<h1>Prueba Específica del Modal de Edición</h1>";

echo "<div style='margin: 20px; padding: 15px; border: 2px solid #007bff; border-radius: 8px; background-color: #f8f9fa;'>";
echo "<h3>Cambios Implementados:</h3>";
echo "<ul>";
echo "<li><strong>Volvimos a onclick:</strong> En lugar de event listeners, usamos onclick directo</li>";
echo "<li><strong>Funciones globales:</strong> Las funciones están disponibles globalmente</li>";
echo "<li><strong>Variables globales:</strong> Los modales se almacenan en variables globales</li>";
echo "<li><strong>Inicialización explícita:</strong> Los modales se inicializan explícitamente</li>";
echo "<li><strong>Debugging mejorado:</strong> Más mensajes de consola para debugging</li>";
echo "</ul>";
echo "</div>";

// Verificar archivos
echo "<h2>Verificación de Archivos:</h2>";

$archivo = 'src/views/admin/users/index.php';
if (file_exists($archivo)) {
    $contenido = file_get_contents($archivo);
    
    $elementos = [
        'onclick="openEditModal(' => 'Función onclick para abrir modal',
        'function openEditModal(' => 'Función JavaScript openEditModal',
        'let editModal;' => 'Variable global para modal',
        'new bootstrap.Modal(' => 'Inicialización de Bootstrap Modal',
        'editModal.show();' => 'Mostrar modal'
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
echo "// Variables globales para los modales\n";
echo "let editModal;\n";
echo "let resetModal;\n";
echo "let currentUserId;\n\n";
echo "// Función para abrir modal de edición\n";
echo "function openEditModal(userId, nombre, apellidos, email, rol, activo) {\n";
echo "    console.log('Abriendo modal de edición para usuario:', userId);\n";
echo "    \n";
echo "    // Guardar el ID del usuario actual\n";
echo "    currentUserId = userId;\n";
echo "    \n";
echo "    // Llenar el formulario\n";
echo "    document.getElementById('editNombre').value = nombre;\n";
echo "    document.getElementById('editApellidos').value = apellidos;\n";
echo "    document.getElementById('editEmail').value = email;\n";
echo "    document.getElementById('editRol').value = rol;\n";
echo "    document.getElementById('editActivo').checked = activo;\n";
echo "    \n";
echo "    // Actualizar la acción del formulario\n";
echo "    document.getElementById('editUserForm').action = '/admin/editarUsuario/' + userId;\n";
echo "    \n";
echo "    // Mostrar el modal\n";
echo "    if (editModal) {\n";
echo "        editModal.show();\n";
echo "    } else {\n";
echo "        console.error('Modal de edición no inicializado');\n";
echo "    }\n";
echo "}\n\n";
echo "// Inicializar cuando el DOM esté listo\n";
echo "document.addEventListener('DOMContentLoaded', function() {\n";
echo "    // Inicializar modales\n";
echo "    try {\n";
echo "        editModal = new bootstrap.Modal(document.getElementById('editUserModal'));\n";
echo "        resetModal = new bootstrap.Modal(document.getElementById('resetPasswordModal'));\n";
echo "        console.log('Modales inicializados correctamente');\n";
echo "    } catch (error) {\n";
echo "        console.error('Error al inicializar modales:', error);\n";
echo "    }\n";
echo "});";
echo "</pre>";
echo "</div>";

// Enlaces de prueba
echo "<h2>Enlaces de Prueba:</h2>";
echo "<p><a href='/prueba-php/public/admin/usuarios' target='_blank'>🔗 Administración - Usuarios (Nueva Versión)</a></p>";

// Instrucciones para probar
echo "<h2>Instrucciones para Probar:</h2>";
echo "<ol>";
echo "<li><strong>Ve a la página de usuarios</strong>: <a href='/prueba-php/public/admin/usuarios' target='_blank'>Administración - Usuarios</a></li>";
echo "<li><strong>Abre la consola del navegador</strong> (F12 → Console)</li>";
echo "<li><strong>Haz clic en el botón de editar</strong> (ícono de lápiz) de cualquier usuario</li>";
echo "<li><strong>Verifica en la consola</strong> que aparecen los mensajes de debugging</li>";
echo "<li><strong>Confirma que se abre el modal</strong> con los datos del usuario</li>";
echo "<li><strong>Modifica algún campo</strong> en el formulario</li>";
echo "<li><strong>Haz clic en 'Guardar Cambios'</strong></li>";
echo "</ol>";

// Estado esperado
echo "<h2>Estado Esperado:</h2>";
echo "<ul>";
echo "<li>✅ Al hacer clic en editar, aparecen mensajes en la consola</li>";
echo "<li>✅ Se abre el modal de edición</li>";
echo "<li>✅ El formulario está pre-llenado con los datos del usuario</li>";
echo "<li>✅ Se puede editar cualquier campo</li>";
echo "<li>✅ El formulario se envía correctamente</li>";
echo "<li>✅ No hay errores en la consola</li>";
echo "</ul>";

// Script de prueba
echo "<script>";
echo "console.log('Script de prueba del modal cargado');";
echo "";
echo "// Verificar al cargar la página";
echo "document.addEventListener('DOMContentLoaded', function() {";
echo "    console.log('Página de prueba cargada');";
echo "    console.log('Bootstrap disponible:', typeof bootstrap !== 'undefined');";
echo "});";
echo "";
echo "// Función para simular clic en botón editar";
echo "function testEditButton() {";
echo "    console.log('Probando botón de editar...');";
echo "    alert('Para probar el modal, ve a la página de usuarios y haz clic en el botón de editar');";
echo "}";
echo "</script>";

// Botón de prueba
echo "<div style='margin: 20px; padding: 15px; border: 2px solid #28a745; border-radius: 8px; background-color: #f8fff9;'>";
echo "<h3>Prueba del Modal</h3>";
echo "<button type='button' class='btn btn-success' onclick='testEditButton()'>";
echo "<i class='fas fa-test'></i> Instrucciones para Probar";
echo "</button>";
echo "<p style='margin-top: 10px; font-size: 14px; color: #666;'>";
echo "Haz clic en este botón para obtener instrucciones sobre cómo probar el modal.";
echo "</p>";
echo "</div>";

// Resumen
echo "<h2>Resumen de la Solución:</h2>";
echo "<p>✅ <strong>Se ha simplificado el código JavaScript</strong></p>";
echo "<p>✅ <strong>Se han usado funciones globales con onclick</strong></p>";
echo "<p>✅ <strong>Se han inicializado los modales explícitamente</strong></p>";
echo "<p>✅ <strong>Se ha mejorado el debugging</strong></p>";
echo "<p>✅ <strong>El modal debería funcionar correctamente ahora</strong></p>";

echo "<h2>Cambios Principales:</h2>";
echo "<ul>";
echo "<li><strong>Volvimos a onclick:</strong> Más directo y confiable</li>";
echo "<li><strong>Funciones globales:</strong> Disponibles inmediatamente</li>";
echo "<li><strong>Variables globales:</strong> Modales accesibles desde cualquier lugar</li>";
echo "<li><strong>Inicialización explícita:</strong> Los modales se crean cuando el DOM está listo</li>";
echo "<li><strong>Debugging mejorado:</strong> Más mensajes para identificar problemas</li>";
echo "</ul>";

echo "<p><strong>El modal de edición debería funcionar correctamente ahora con esta implementación simplificada.</strong></p>";
?>
