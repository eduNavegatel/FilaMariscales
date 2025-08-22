<?php
// Script para verificar botones de gestión de usuarios
echo "<h1>🔧 Verificación de Botones de Gestión de Usuarios</h1>";

// Incluir configuración
require_once 'src/config/config.php';

echo "<h2>📋 Verificación de Configuración</h2>";
echo "<p><strong>URL_ROOT:</strong> " . URL_ROOT . "</p>";

// Verificar archivos de vistas
echo "<h2>📁 Verificación de Archivos de Vistas</h2>";

$viewFiles = [
    'users/index.php' => 'src/views/admin/users/index.php',
    'users/create.php' => 'src/views/admin/users/create.php'
];

foreach ($viewFiles as $name => $path) {
    if (file_exists($path)) {
        echo "<p>✅ Archivo <code>$name</code> existe</p>";
        
        // Verificar botones con texto
        $content = file_get_contents($path);
        
        // Buscar botones específicos
        $buttons = [
            'Editar' => strpos($content, 'Editar') !== false,
            'Activar' => strpos($content, 'Activar') !== false,
            'Desactivar' => strpos($content, 'Desactivar') !== false,
            'Resetear' => strpos($content, 'Resetear') !== false,
            'Eliminar' => strpos($content, 'Eliminar') !== false,
            'Cancelar' => strpos($content, 'Cancelar') !== false,
            'Guardar' => strpos($content, 'Guardar') !== false,
            'Crear Usuario' => strpos($content, 'Crear Usuario') !== false,
            'Volver' => strpos($content, 'Volver') !== false
        ];
        
        echo "<div style='margin-left: 20px;'>";
        foreach ($buttons as $buttonText => $exists) {
            if ($exists) {
                echo "<p>✅ Botón <strong>$buttonText</strong> encontrado</p>";
            } else {
                echo "<p>❌ Botón <strong>$buttonText</strong> no encontrado</p>";
            }
        }
        echo "</div>";
        
    } else {
        echo "<p>❌ Archivo <code>$name</code> no encontrado</p>";
    }
}

// Verificar estilos CSS
echo "<h2>🎨 Verificación de Estilos CSS</h2>";

$indexContent = file_get_contents('src/views/admin/users/index.php');
$cssChecks = [
    'font-size' => strpos($indexContent, 'font-size') !== false,
    'padding' => strpos($indexContent, 'padding') !== false,
    'white-space' => strpos($indexContent, 'white-space') !== false,
    'flex' => strpos($indexContent, 'flex') !== false
];

foreach ($cssChecks as $property => $exists) {
    if ($exists) {
        echo "<p>✅ Propiedad CSS <code>$property</code> encontrada</p>";
    } else {
        echo "<p>❌ Propiedad CSS <code>$property</code> no encontrada</p>";
    }
}

// Mostrar resumen de botones implementados
echo "<h2>📝 Resumen de Botones Implementados</h2>";
echo "<div style='background: #f8f9fa; padding: 20px; border-radius: 8px;'>";

echo "<h4>🎯 Botones de Acciones de Usuario:</h4>";
echo "<ul>";
echo "<li><strong>Editar</strong> - <i class='fas fa-edit'></i> Editar información del usuario</li>";
echo "<li><strong>Activar</strong> - <i class='fas fa-user-check'></i> Activar usuario inactivo</li>";
echo "<li><strong>Desactivar</strong> - <i class='fas fa-user-slash'></i> Desactivar usuario activo</li>";
echo "<li><strong>Resetear</strong> - <i class='fas fa-key'></i> Resetear contraseña del usuario</li>";
echo "<li><strong>Eliminar</strong> - <i class='fas fa-trash'></i> Eliminar usuario del sistema</li>";
echo "</ul>";

echo "<h4>🎯 Botones de Formularios:</h4>";
echo "<ul>";
echo "<li><strong>Guardar Cambios</strong> - <i class='fas fa-save'></i> Guardar cambios en edición</li>";
echo "<li><strong>Crear Usuario</strong> - <i class='fas fa-save'></i> Crear nuevo usuario</li>";
echo "<li><strong>Cancelar</strong> - <i class='fas fa-times'></i> Cancelar operación</li>";
echo "<li><strong>Volver</strong> - <i class='fas fa-arrow-left'></i> Volver a la lista</li>";
echo "</ul>";

echo "<h4>🎯 Botones de Navegación:</h4>";
echo "<ul>";
echo "<li><strong>Nuevo Usuario</strong> - <i class='fas fa-plus'></i> Crear nuevo usuario</li>";
echo "<li><strong>Resetear Contraseña</strong> - <i class='fas fa-key'></i> Resetear contraseña</li>";
echo "</ul>";

echo "</div>";

// Mostrar instrucciones de prueba
echo "<h2>🧪 Instrucciones de Prueba</h2>";
echo "<ol>";
echo "<li>Accede a <a href='" . URL_ROOT . "/admin/usuarios' target='_blank'>Gestión de Usuarios</a></li>";
echo "<li>Verifica que todos los botones tienen texto descriptivo</li>";
echo "<li>Prueba cada acción (Editar, Activar/Desactivar, Resetear, Eliminar)</li>";
echo "<li>Verifica que los botones de los modales también tienen texto</li>";
echo "<li>Prueba la creación de un nuevo usuario</li>";
echo "</ol>";

// Enlaces de prueba
echo "<h2>🔗 Enlaces de Prueba</h2>";
echo "<p><a href='" . URL_ROOT . "/admin/usuarios' class='btn btn-primary' target='_blank'>🚀 Ir a Gestión de Usuarios</a></p>";
echo "<p><a href='" . URL_ROOT . "/admin/crearUsuario' class='btn btn-success' target='_blank'>➕ Crear Nuevo Usuario</a></p>";
echo "<p><a href='" . URL_ROOT . "/admin' class='btn btn-secondary' target='_blank'>🏠 Ir al Dashboard</a></p>";

echo "<hr>";
echo "<p><em>Script de prueba completado. Verifica que todos los botones de gestión de usuarios tienen texto descriptivo.</em></p>";
?>
