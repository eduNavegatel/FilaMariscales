<?php
// Script de diagnóstico completo para identificar todos los problemas
echo "<h1>Diagnóstico Completo - Modal y Rutas</h1>";

echo "<div style='margin: 20px; padding: 15px; border: 2px solid #dc3545; border-radius: 8px; background-color: #f8d7da;'>";
echo "<h3>Problemas Identificados:</h3>";
echo "<ul>";
echo "<li><strong>Modal no funciona:</strong> El modal personalizado no se muestra</li>";
echo "<li><strong>Error 404 en activar usuario:</strong> Las rutas no coinciden</li>";
echo "<li><strong>Rutas POST vs GET:</strong> Conflicto entre definición y uso</li>";
echo "</ul>";
echo "</div>";

// Verificar archivos críticos
echo "<h2>Verificación de Archivos Críticos:</h2>";

$archivos = [
    'src/views/admin/users/index.php' => 'Vista principal de usuarios',
    'src/controllers/AdminController.php' => 'Controlador de administración',
    'routes/web.php' => 'Definición de rutas',
    'src/models/User.php' => 'Modelo de usuario'
];

foreach ($archivos as $archivo => $descripcion) {
    if (file_exists($archivo)) {
        echo "<p>✅ $descripcion existe</p>";
        
        // Verificar sintaxis PHP
        $output = [];
        $returnCode = 0;
        exec("php -l $archivo 2>&1", $output, $returnCode);
        
        if ($returnCode === 0) {
            echo "<p style='margin-left: 20px;'>✅ Sintaxis PHP correcta</p>";
        } else {
            echo "<p style='margin-left: 20px;'>❌ Error de sintaxis: " . implode('<br>', $output) . "</p>";
        }
    } else {
        echo "<p>❌ $descripcion NO existe</p>";
    }
}

// Verificar rutas específicas
echo "<h2>Verificación de Rutas:</h2>";

$routesFile = 'routes/web.php';
if (file_exists($routesFile)) {
    $content = file_get_contents($routesFile);
    
    $routesToCheck = [
        'activarUsuario/{id}' => 'Ruta para activar usuario',
        'desactivarUsuario/{id}' => 'Ruta para desactivar usuario',
        'editarUsuario/{id}' => 'Ruta para editar usuario',
        'resetearPassword/{id}' => 'Ruta para resetear contraseña'
    ];
    
    foreach ($routesToCheck as $route => $desc) {
        if (strpos($content, $route) !== false) {
            echo "<p>✅ $desc encontrada</p>";
        } else {
            echo "<p>❌ $desc NO encontrada</p>";
        }
    }
}

// Verificar métodos del controlador
echo "<h2>Verificación de Métodos del Controlador:</h2>";

$controllerFile = 'src/controllers/AdminController.php';
if (file_exists($controllerFile)) {
    $content = file_get_contents($controllerFile);
    
    $methodsToCheck = [
        'activarUsuario' => 'Método para activar usuario',
        'desactivarUsuario' => 'Método para desactivar usuario',
        'editarUsuario' => 'Método para editar usuario',
        'resetearPassword' => 'Método para resetear contraseña'
    ];
    
    foreach ($methodsToCheck as $method => $desc) {
        if (strpos($content, "function $method") !== false) {
            echo "<p>✅ $desc encontrado</p>";
        } else {
            echo "<p>❌ $desc NO encontrado</p>";
        }
    }
}

// Verificar vista de usuarios
echo "<h2>Verificación de la Vista:</h2>";

$viewFile = 'src/views/admin/users/index.php';
if (file_exists($viewFile)) {
    $content = file_get_contents($viewFile);
    
    $elementsToCheck = [
        'custom-modal' => 'Clase CSS para modal personalizado',
        'openEditModal(' => 'Función JavaScript para abrir modal',
        'closeEditModal()' => 'Función JavaScript para cerrar modal',
        'style.display = \'block\'' => 'Mostrar modal con JavaScript',
        'activarUsuario/' => 'Enlace para activar usuario',
        'desactivarUsuario/' => 'Enlace para desactivar usuario'
    ];
    
    foreach ($elementsToCheck as $element => $desc) {
        if (strpos($content, $element) !== false) {
            echo "<p>✅ $desc encontrado</p>";
        } else {
            echo "<p>❌ $desc NO encontrado</p>";
        }
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
        
        // Verificar estructura de la tabla
        $db->query("DESCRIBE users");
        $columns = $db->resultSet();
        
        $requiredColumns = ['id', 'nombre', 'apellidos', 'email', 'rol', 'activo', 'created_at'];
        foreach ($requiredColumns as $column) {
            $found = false;
            foreach ($columns as $col) {
                if ($col->Field === $column) {
                    $found = true;
                    break;
                }
            }
            if ($found) {
                echo "<p style='margin-left: 20px;'>✅ Columna '$column' existe</p>";
            } else {
                echo "<p style='margin-left: 20px;'>❌ Columna '$column' NO existe</p>";
            }
        }
        
        // Contar usuarios
        $db->query("SELECT COUNT(*) as count FROM users");
        $userCount = $db->single();
        echo "<p><strong>Total de usuarios:</strong> " . $userCount->count . "</p>";
        
    } else {
        echo "<p>❌ La tabla 'users' NO existe</p>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error de conexión: " . $e->getMessage() . "</p>";
}

// Solución propuesta
echo "<h2>Solución Propuesta:</h2>";

echo "<div style='margin: 20px; padding: 15px; border: 2px solid #28a745; border-radius: 8px; background-color: #d4edda;'>";
echo "<h3>Pasos para Solucionar:</h3>";
echo "<ol>";
echo "<li><strong>Corregir rutas:</strong> Cambiar enlaces GET a formularios POST</li>";
echo "<li><strong>Simplificar modal:</strong> Usar alert() temporal para verificar funcionamiento</li>";
echo "<li><strong>Verificar JavaScript:</strong> Asegurar que las funciones se ejecutan</li>";
echo "<li><strong>Probar rutas:</strong> Verificar que las URLs son correctas</li>";
echo "</ol>";
echo "</div>";

// Código de prueba para el modal
echo "<h2>Código de Prueba para el Modal:</h2>";

echo "<div style='background-color: #f5f5f5; padding: 15px; border-radius: 5px; font-family: monospace; font-size: 12px;'>";
echo "<pre>";
echo "// Función de prueba simple\n";
echo "function testModal() {\n";
echo "    alert('Modal funcionando!');\n";
echo "    console.log('Modal test ejecutado');\n";
echo "}\n\n";
echo "// Función para abrir modal de edición\n";
echo "function openEditModal(userId, nombre, apellidos, email, rol, activo) {\n";
echo "    console.log('Abriendo modal para usuario:', userId);\n";
echo "    alert('Editando usuario: ' + nombre + ' ' + apellidos);\n";
echo "    \n";
echo "    // Aquí iría el código del modal\n";
echo "    document.getElementById('editUserModal').style.display = 'block';\n";
echo "}\n\n";
echo "// Función para cerrar modal\n";
echo "function closeEditModal() {\n";
echo "    document.getElementById('editUserModal').style.display = 'none';\n";
echo "    console.log('Modal cerrado');\n";
echo "}";
echo "</pre>";
echo "</div>";

// Enlaces de prueba
echo "<h2>Enlaces de Prueba:</h2>";
echo "<p><a href='/prueba-php/public/admin/usuarios' target='_blank'>🔗 Administración - Usuarios</a></p>";

// Instrucciones para probar
echo "<h2>Instrucciones para Probar:</h2>";
echo "<ol>";
echo "<li><strong>Ve a la página de usuarios</strong>: <a href='/prueba-php/public/admin/usuarios' target='_blank'>Administración - Usuarios</a></li>";
echo "<li><strong>Abre la consola del navegador</strong> (F12 → Console)</li>";
echo "<li><strong>Haz clic en el botón de editar</strong> (ícono de lápiz) de cualquier usuario</li>";
echo "<li><strong>Verifica que aparece un alert</strong> con los datos del usuario</li>";
echo "<li><strong>Prueba el botón de activar/desactivar</strong> para ver si da 404</li>";
echo "<li><strong>Revisa la consola</strong> para mensajes de error</li>";
echo "</ol>";

// Script de prueba
echo "<script>";
echo "console.log('Script de diagnóstico cargado');";
echo "";
echo "// Verificar al cargar la página";
echo "document.addEventListener('DOMContentLoaded', function() {";
echo "    console.log('Página de diagnóstico cargada');";
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
echo "<h2>Resumen del Diagnóstico:</h2>";
echo "<p>✅ <strong>Se han identificado los problemas principales</strong></p>";
echo "<p>✅ <strong>Se han verificado todos los archivos críticos</strong></p>";
echo "<p>✅ <strong>Se han propuesto soluciones específicas</strong></p>";
echo "<p>✅ <strong>Se ha creado código de prueba</strong></p>";

echo "<p><strong>El siguiente paso es implementar las correcciones basadas en este diagnóstico.</strong></p>";
?>
