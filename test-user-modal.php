<?php
// Script para probar el modal de edición de usuarios
echo "<h1>Prueba del Modal de Edición de Usuarios</h1>";

// Verificar que el controlador funciona
echo "<h2>Verificación del Controlador:</h2>";

if (file_exists('src/controllers/AdminController.php')) {
    echo "<p>✅ AdminController existe</p>";
    
    // Verificar el método editarUsuario
    $controllerContent = file_get_contents('src/controllers/AdminController.php');
    if (strpos($controllerContent, 'editarUsuario') !== false) {
        echo "<p>✅ Método editarUsuario existe</p>";
    } else {
        echo "<p>❌ Método editarUsuario NO existe</p>";
    }
} else {
    echo "<p>❌ AdminController NO existe</p>";
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

// Verificar el modelo de usuarios
echo "<h2>Verificación del Modelo:</h2>";

if (file_exists('src/models/User.php')) {
    echo "<p>✅ Modelo User existe</p>";
    
    $modelContent = file_get_contents('src/models/User.php');
    $methods = ['getUserById', 'updateUser', 'register'];
    
    foreach ($methods as $method) {
        if (strpos($modelContent, $method) !== false) {
            echo "<p>✅ Método $method existe</p>";
        } else {
            echo "<p>❌ Método $method NO existe</p>";
        }
    }
} else {
    echo "<p>❌ Modelo User NO existe</p>";
}

// Simular el modal de edición
echo "<h2>Simulación del Modal:</h2>";

echo "<div style='margin: 20px; padding: 15px; border: 2px solid #ccc; border-radius: 8px;'>";
echo "<h3>Modal de Edición Simulado</h3>";

echo "<div class='modal fade' id='testEditModal' tabindex='-1' aria-hidden='true'>";
echo "<div class='modal-dialog'>";
echo "<div class='modal-content'>";
echo "<div class='modal-header'>";
echo "<h5 class='modal-title'>Editar Usuario (Test)</h5>";
echo "<button type='button' class='btn-close' data-bs-dismiss='modal'></button>";
echo "</div>";
echo "<form action='/prueba-php/public/admin/editarUsuario/1' method='POST'>";
echo "<div class='modal-body'>";
echo "<div class='mb-3'>";
echo "<label for='nombre1' class='form-label'>Nombre</label>";
echo "<input type='text' class='form-control' id='nombre1' name='nombre' value='Usuario Test' required>";
echo "</div>";
echo "<div class='mb-3'>";
echo "<label for='apellidos1' class='form-label'>Apellidos</label>";
echo "<input type='text' class='form-control' id='apellidos1' name='apellidos' value='Apellido Test'>";
echo "</div>";
echo "<div class='mb-3'>";
echo "<label for='email1' class='form-label'>Email</label>";
echo "<input type='email' class='form-control' id='email1' name='email' value='test@example.com' required>";
echo "</div>";
echo "<div class='mb-3'>";
echo "<label for='rol1' class='form-label'>Rol</label>";
echo "<select class='form-select' id='rol1' name='rol'>";
echo "<option value='user' selected>Usuario</option>";
echo "<option value='socio'>Socio</option>";
echo "<option value='admin'>Administrador</option>";
echo "</select>";
echo "</div>";
echo "<div class='form-check'>";
echo "<input class='form-check-input' type='checkbox' id='activo1' name='activo' value='1' checked>";
echo "<label class='form-check-label' for='activo1'>Usuario activo</label>";
echo "</div>";
echo "</div>";
echo "<div class='modal-footer'>";
echo "<button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancelar</button>";
echo "<button type='submit' class='btn btn-primary'>Guardar Cambios</button>";
echo "</div>";
echo "</form>";
echo "</div>";
echo "</div>";
echo "</div>";

echo "<button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#testEditModal'>";
echo "Abrir Modal de Prueba";
echo "</button>";
echo "</div>";

// Verificar posibles problemas
echo "<h2>Posibles Problemas:</h2>";
echo "<ol>";
echo "<li><strong>Conflicto de JavaScript:</strong> Verificar si hay scripts que interfieren con Bootstrap</li>";
echo "<li><strong>Validación de formulario:</strong> El formulario puede estar validándose y cerrando el modal</li>";
echo "<li><strong>Problema de rutas:</strong> La acción del formulario puede estar causando redirección</li>";
echo "<li><strong>Problema de CSRF:</strong> El token CSRF puede estar causando problemas</li>";
echo "<li><strong>Problema de Bootstrap:</strong> Versión incorrecta o conflicto de CSS/JS</li>";
echo "</ol>";

// Soluciones propuestas
echo "<h2>Soluciones Propuestas:</h2>";
echo "<ol>";
echo "<li><strong>Agregar preventDefault:</strong> Prevenir el envío automático del formulario</li>";
echo "<li><strong>Verificar validación:</strong> Asegurar que la validación no cierre el modal</li>";
echo "<li><strong>Agregar debugging:</strong> Incluir console.log para ver qué está pasando</li>";
echo "<li><strong>Verificar rutas:</strong> Asegurar que las rutas estén correctas</li>";
echo "<li><strong>Simplificar el modal:</strong> Crear una versión más simple para pruebas</li>";
echo "</ol>";

// Enlaces de prueba
echo "<h2>Enlaces de Prueba:</h2>";
echo "<p><a href='/prueba-php/public/admin/usuarios' target='_blank'>🔗 Administración - Usuarios</a></p>";
echo "<p><a href='/prueba-php/public/admin/crearUsuario' target='_blank'>🔗 Crear Usuario</a></p>";

// Script de debugging
echo "<h2>Script de Debugging:</h2>";
echo "<script>";
echo "console.log('Script de debugging cargado');";
echo "document.addEventListener('DOMContentLoaded', function() {";
echo "    console.log('DOM cargado');";
echo "    const modals = document.querySelectorAll('.modal');";
echo "    console.log('Modales encontrados:', modals.length);";
echo "    modals.forEach(function(modal, index) {";
echo "        modal.addEventListener('show.bs.modal', function() {";
echo "            console.log('Modal', index, 'abriéndose');";
echo "        });";
echo "        modal.addEventListener('hide.bs.modal', function() {";
echo "            console.log('Modal', index, 'cerrándose');";
echo "        });";
echo "    });";
echo "});";
echo "</script>";

echo "<h2>Instrucciones para Debugging:</h2>";
echo "<ol>";
echo "<li>Abre la consola del navegador (F12)</li>";
echo "<li>Ve a la página de usuarios</li>";
echo "<li>Haz clic en el botón de editar</li>";
echo "<li>Observa los mensajes en la consola</li>";
echo "<li>Verifica si hay errores JavaScript</li>";
echo "</ol>";
?>
