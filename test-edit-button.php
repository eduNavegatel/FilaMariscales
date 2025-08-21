<?php
// Script para diagnosticar el problema del botón de editar usuario
echo "<h1>Diagnóstico del Botón de Editar Usuario</h1>";

// Verificar que el usuario existe en la base de datos
echo "<h2>Verificación de Usuario en Base de Datos:</h2>";

try {
    require_once 'src/config/config.php';
    require_once 'src/models/Database.php';
    
    $db = new Database();
    
    // Buscar el usuario "Admin Principal"
    $db->query("SELECT * FROM users WHERE nombre LIKE '%Admin%' OR email = 'admin@mariscales.com'");
    $user = $db->single();
    
    if ($user) {
        echo "<p>✅ Usuario encontrado:</p>";
        echo "<ul>";
        echo "<li><strong>ID:</strong> " . $user->id . "</li>";
        echo "<li><strong>Nombre:</strong> " . htmlspecialchars($user->nombre) . "</li>";
        echo "<li><strong>Apellidos:</strong> " . htmlspecialchars($user->apellidos) . "</li>";
        echo "<li><strong>Email:</strong> " . htmlspecialchars($user->email) . "</li>";
        echo "<li><strong>Rol:</strong> " . htmlspecialchars($user->rol) . "</li>";
        echo "<li><strong>Activo:</strong> " . ($user->activo ? 'Sí' : 'No') . "</li>";
        echo "<li><strong>Fecha Registro:</strong> " . $user->fecha_registro . "</li>";
        echo "</ul>";
    } else {
        echo "<p>❌ Usuario no encontrado en la base de datos</p>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error de conexión: " . $e->getMessage() . "</p>";
}

// Simular el botón y modal
echo "<h2>Prueba del Botón de Editar:</h2>";

echo "<div style='margin: 20px; padding: 15px; border: 2px solid #007bff; border-radius: 8px; background-color: #f8f9fa;'>";
echo "<h3>Botón de Editar Simulado</h3>";

echo "<button type='button' class='btn btn-sm btn-outline-primary' onclick='testEditButton()' style='margin: 5px;'>";
echo "<i class='fas fa-edit'></i> Editar Usuario (Test)";
echo "</button>";

echo "<button type='button' class='btn btn-sm btn-outline-success' onclick='testBootstrapModal()' style='margin: 5px;'>";
echo "<i class='fas fa-check'></i> Probar Bootstrap Modal";
echo "</button>";

echo "<button type='button' class='btn btn-sm btn-outline-info' onclick='checkBootstrap()' style='margin: 5px;'>";
echo "<i class='fas fa-info'></i> Verificar Bootstrap";
echo "</button>";
echo "</div>";

// Modal de prueba
echo "<div class='modal fade' id='testEditModal' tabindex='-1' aria-hidden='true'>";
echo "<div class='modal-dialog'>";
echo "<div class='modal-content'>";
echo "<div class='modal-header'>";
echo "<h5 class='modal-title'>Editar Usuario (Test)</h5>";
echo "<button type='button' class='btn-close' data-bs-dismiss='modal'></button>";
echo "</div>";
echo "<div class='modal-body'>";
echo "<p>Este es un modal de prueba para verificar que Bootstrap funciona correctamente.</p>";
echo "<p>Si puedes ver este modal, Bootstrap está funcionando.</p>";
echo "</div>";
echo "<div class='modal-footer'>";
echo "<button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cerrar</button>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "</div>";

// Verificar archivos JavaScript
echo "<h2>Verificación de Archivos JavaScript:</h2>";

$archivos = [
    'src/views/admin/users/index.php' => 'Vista de usuarios'
];

foreach ($archivos as $archivo => $descripcion) {
    if (file_exists($archivo)) {
        $contenido = file_get_contents($archivo);
        
        $elementos = [
            'openEditModal' => 'Función openEditModal',
            'bootstrap.Modal' => 'Bootstrap Modal',
            'fas fa-edit' => 'Icono de editar',
            'editModal' => 'Modal de edición'
        ];
        
        echo "<p>✅ $descripcion existe</p>";
        foreach ($elementos as $buscar => $desc) {
            if (strpos($contenido, $buscar) !== false) {
                echo "<p style='margin-left: 20px;'>✅ $desc encontrado</p>";
            } else {
                echo "<p style='margin-left: 20px;'>❌ $desc NO encontrado</p>";
            }
        }
    } else {
        echo "<p>❌ $descripcion NO existe</p>";
    }
}

// Scripts de prueba
echo "<h2>Scripts de Prueba:</h2>";

echo "<script>";
echo "console.log('Script de prueba cargado');";
echo "";
echo "function testEditButton() {";
echo "    console.log('Botón de editar clickeado');";
echo "    alert('Botón de editar funcionando');";
echo "}";
echo "";
echo "function testBootstrapModal() {";
echo "    console.log('Probando modal de Bootstrap');";
echo "    if (typeof bootstrap !== 'undefined') {";
echo "        const modal = new bootstrap.Modal(document.getElementById('testEditModal'));";
echo "        modal.show();";
echo "        console.log('Modal mostrado correctamente');";
echo "    } else {";
echo "        alert('Bootstrap no está cargado');";
echo "        console.error('Bootstrap no está disponible');";
echo "    }";
echo "}";
echo "";
echo "function checkBootstrap() {";
echo "    console.log('Verificando Bootstrap...');";
echo "    if (typeof bootstrap !== 'undefined') {";
echo "        console.log('✅ Bootstrap está cargado');";
echo "        console.log('Versión de Bootstrap:', bootstrap.VERSION || 'No disponible');";
echo "        alert('Bootstrap está cargado correctamente');";
echo "    } else {";
echo "        console.error('❌ Bootstrap NO está cargado');";
echo "        alert('Bootstrap NO está cargado');";
echo "    }";
echo "}";
echo "";
echo "// Verificar al cargar la página";
echo "document.addEventListener('DOMContentLoaded', function() {";
echo "    console.log('Página cargada');";
echo "    console.log('Bootstrap disponible:', typeof bootstrap !== 'undefined');";
echo "    console.log('jQuery disponible:', typeof $ !== 'undefined');";
echo "});";
echo "</script>";

// Instrucciones para diagnosticar
echo "<h2>Instrucciones para Diagnosticar:</h2>";
echo "<ol>";
echo "<li><strong>Abre la consola del navegador</strong> (F12)</li>";
echo "<li><strong>Haz clic en 'Verificar Bootstrap'</strong> para ver si Bootstrap está cargado</li>";
echo "<li><strong>Haz clic en 'Probar Bootstrap Modal'</strong> para ver si los modales funcionan</li>";
echo "<li><strong>Ve a la página de usuarios</strong> y haz clic en el botón de editar</li>";
echo "<li><strong>Observa los mensajes en la consola</strong> para ver errores</li>";
echo "</ol>";

// Posibles problemas
echo "<h2>Posibles Problemas:</h2>";
echo "<ul>";
echo "<li><strong>Bootstrap no cargado:</strong> Verificar que el CDN de Bootstrap esté funcionando</li>";
echo "<li><strong>Conflicto de JavaScript:</strong> Otros scripts pueden estar interfiriendo</li>";
echo "<li><strong>Error en la función:</strong> La función openEditModal puede tener errores</li>";
echo "<li><strong>Modal no encontrado:</strong> El ID del modal puede estar mal</li>";
echo "<li><strong>Problema de rutas:</strong> Las rutas pueden estar incorrectas</li>";
echo "</ul>";

// Soluciones
echo "<h2>Soluciones:</h2>";
echo "<ol>";
echo "<li><strong>Verificar Bootstrap:</strong> Asegurar que Bootstrap 5 esté cargado</li>";
echo "<li><strong>Revisar consola:</strong> Buscar errores JavaScript</li>";
echo "<li><strong>Probar modal simple:</strong> Crear un modal básico para pruebas</li>";
echo "<li><strong>Verificar IDs:</strong> Asegurar que los IDs de los modales sean únicos</li>";
echo "<li><strong>Limpiar caché:</strong> Limpiar la caché del navegador</li>";
echo "</ol>";

// Enlaces de prueba
echo "<h2>Enlaces de Prueba:</h2>";
echo "<p><a href='/prueba-php/public/admin/usuarios' target='_blank'>🔗 Administración - Usuarios</a></p>";
echo "<p><a href='/prueba-php/test-users-section.php' target='_blank'>🔗 Diagnóstico de Usuarios</a></p>";

echo "<h2>Estado del Diagnóstico:</h2>";
echo "<p>Este script te ayudará a identificar exactamente dónde está el problema con el botón de editar.</p>";
echo "<p>Una vez que identifiques el problema, podremos solucionarlo específicamente.</p>";
?>
