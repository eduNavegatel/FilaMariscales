<?php
// Script de prueba para verificar que el modal personalizado funciona
echo "<h1>Prueba del Modal Personalizado - Nueva Implementación</h1>";

echo "<div style='margin: 20px; padding: 15px; border: 2px solid #28a745; border-radius: 8px; background-color: #f8fff9;'>";
echo "<h3>Nueva Solución Implementada:</h3>";
echo "<ul>";
echo "<li><strong>Modal Personalizado:</strong> No depende de Bootstrap Modal</li>";
echo "<li><strong>CSS Personalizado:</strong> Estilos propios para el modal</li>";
echo "<li><strong>JavaScript Puro:</strong> Control directo de mostrar/ocultar</li>";
echo "<li><strong>Sin Dependencias:</strong> No requiere inicialización de Bootstrap</li>";
echo "<li><strong>Debugging Mejorado:</strong> Más mensajes de consola</li>";
echo "</ul>";
echo "</div>";

// Verificar archivos
echo "<h2>Verificación de Archivos:</h2>";

$archivo = 'src/views/admin/users/index.php';
if (file_exists($archivo)) {
    $contenido = file_get_contents($archivo);
    
    $elementos = [
        'custom-modal' => 'Clase CSS para modal personalizado',
        'openEditModal(' => 'Función JavaScript para abrir modal',
        'closeEditModal()' => 'Función JavaScript para cerrar modal',
        'style.display = \'block\'' => 'Mostrar modal con JavaScript puro',
        'style.display = \'none\'' => 'Ocultar modal con JavaScript puro'
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

// Código CSS de ejemplo
echo "<h2>CSS del Modal Personalizado:</h2>";

echo "<div style='background-color: #f5f5f5; padding: 15px; border-radius: 5px; font-family: monospace; font-size: 12px;'>";
echo "<pre>";
echo "/* Modal personalizado */\n";
echo ".custom-modal {\n";
echo "    display: none;\n";
echo "    position: fixed;\n";
echo "    z-index: 9999;\n";
echo "    left: 0;\n";
echo "    top: 0;\n";
echo "    width: 100%;\n";
echo "    height: 100%;\n";
echo "    background-color: rgba(0,0,0,0.5);\n";
echo "}\n\n";
echo ".custom-modal-content {\n";
echo "    background-color: #fefefe;\n";
echo "    margin: 5% auto;\n";
echo "    padding: 0;\n";
echo "    border: 1px solid #888;\n";
echo "    border-radius: 8px;\n";
echo "    width: 90%;\n";
echo "    max-width: 500px;\n";
echo "    box-shadow: 0 4px 6px rgba(0,0,0,0.1);\n";
echo "}";
echo "</pre>";
echo "</div>";

// Código JavaScript de ejemplo
echo "<h2>JavaScript del Modal Personalizado:</h2>";

echo "<div style='background-color: #f5f5f5; padding: 15px; border-radius: 5px; font-family: monospace; font-size: 12px;'>";
echo "<pre>";
echo "// Función para abrir modal de edición\n";
echo "function openEditModal(userId, nombre, apellidos, email, rol, activo) {\n";
echo "    console.log('=== ABRIENDO MODAL DE EDICIÓN ===');\n";
echo "    console.log('Usuario ID:', userId);\n";
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

// Enlaces de prueba
echo "<h2>Enlaces de Prueba:</h2>";
echo "<p><a href='/prueba-php/public/admin/usuarios' target='_blank'>🔗 Administración - Usuarios (Modal Personalizado)</a></p>";

// Instrucciones para probar
echo "<h2>Instrucciones para Probar:</h2>";
echo "<ol>";
echo "<li><strong>Ve a la página de usuarios</strong>: <a href='/prueba-php/public/admin/usuarios' target='_blank'>Administración - Usuarios</a></li>";
echo "<li><strong>Abre la consola del navegador</strong> (F12 → Console)</li>";
echo "<li><strong>Haz clic en el botón de editar</strong> (ícono de lápiz) de cualquier usuario</li>";
echo "<li><strong>Verifica en la consola</strong> que aparecen los mensajes de debugging</li>";
echo "<li><strong>Confirma que se abre el modal</strong> con los datos del usuario</li>";
echo "<li><strong>Prueba cerrar el modal</strong> con el botón X o Cancelar</li>";
echo "<li><strong>Prueba hacer clic fuera del modal</strong> para cerrarlo</li>";
echo "</ol>";

// Estado esperado
echo "<h2>Estado Esperado:</h2>";
echo "<ul>";
echo "<li>✅ Al hacer clic en editar, aparecen mensajes en la consola</li>";
echo "<li>✅ Se abre el modal personalizado (no Bootstrap)</li>";
echo "<li>✅ El formulario está pre-llenado con los datos del usuario</li>";
echo "<li>✅ Se puede cerrar con el botón X</li>";
echo "<li>✅ Se puede cerrar con el botón Cancelar</li>";
echo "<li>✅ Se puede cerrar haciendo clic fuera del modal</li>";
echo "<li>✅ No hay errores en la consola</li>";
echo "</ul>";

// Ventajas de la nueva implementación
echo "<h2>Ventajas de la Nueva Implementación:</h2>";
echo "<ul>";
echo "<li><strong>Sin dependencias:</strong> No requiere Bootstrap Modal</li>";
echo "<li><strong>Control total:</strong> JavaScript puro para mostrar/ocultar</li>";
echo "<li><strong>CSS personalizado:</strong> Estilos propios y controlables</li>";
echo "<li><strong>Debugging fácil:</strong> Más mensajes de consola</li>";
echo "<li><strong>Funcionamiento garantizado:</strong> No hay conflictos de inicialización</li>";
echo "</ul>";

// Script de prueba
echo "<script>";
echo "console.log('Script de prueba del modal personalizado cargado');";
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
echo "    alert('Para probar el modal personalizado, ve a la página de usuarios y haz clic en el botón de editar');";
echo "}";
echo "</script>";

// Botón de prueba
echo "<div style='margin: 20px; padding: 15px; border: 2px solid #007bff; border-radius: 8px; background-color: #f8f9fa;'>";
echo "<h3>Prueba del Modal Personalizado</h3>";
echo "<button type='button' class='btn btn-primary' onclick='testEditButton()'>";
echo "<i class='fas fa-test'></i> Instrucciones para Probar";
echo "</button>";
echo "<p style='margin-top: 10px; font-size: 14px; color: #666;'>";
echo "Haz clic en este botón para obtener instrucciones sobre cómo probar el modal personalizado.";
echo "</p>";
echo "</div>";

// Resumen
echo "<h2>Resumen de la Nueva Solución:</h2>";
echo "<p>✅ <strong>Se ha implementado un modal completamente personalizado</strong></p>";
echo "<p>✅ <strong>No depende de Bootstrap Modal</strong></p>";
echo "<p>✅ <strong>Usa JavaScript puro para control</strong></p>";
echo "<p>✅ <strong>CSS personalizado para estilos</strong></p>";
echo "<p>✅ <strong>Debugging mejorado</strong></p>";
echo "<p>✅ <strong>El modal debería funcionar correctamente ahora</strong></p>";

echo "<h2>Cambios Principales:</h2>";
echo "<ul>";
echo "<li><strong>Modal personalizado:</strong> CSS y HTML propios</li>";
echo "<li><strong>JavaScript puro:</strong> Control directo de display</li>";
echo "<li><strong>Sin Bootstrap Modal:</strong> Eliminada la dependencia</li>";
echo "<li><strong>Funciones simples:</strong> openEditModal() y closeEditModal()</li>";
echo "<li><strong>Event listeners:</strong> Para cerrar al hacer clic fuera</li>";
echo "</ul>";

echo "<p><strong>El modal de edición debería funcionar correctamente ahora con esta implementación completamente personalizada.</strong></p>";
?>
