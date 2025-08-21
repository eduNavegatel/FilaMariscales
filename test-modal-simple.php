<?php
// Versión de prueba completamente independiente para verificar que el modal funciona
echo "<!DOCTYPE html>";
echo "<html lang='es'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Prueba Modal - Gestión de Usuarios</title>";
echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
echo "<link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css' rel='stylesheet'>";
echo "<style>";
echo "body { background-color: #f8f9fa; }";
echo ".card { box-shadow: 0 2px 4px rgba(0,0,0,0.1); }";
echo "</style>";
echo "</head>";
echo "<body>";

echo "<div class='container-fluid mt-4'>";
echo "<h1>Prueba del Modal de Edición</h1>";
echo "<p>Esta es una versión de prueba para verificar que el modal funciona correctamente.</p>";

// Simular datos de usuarios
echo "<div class='card'>";
echo "<div class='card-header'>";
echo "<h5>Lista de Usuarios (Simulada)</h5>";
echo "</div>";
echo "<div class='card-body'>";
echo "<div class='table-responsive'>";
echo "<table class='table table-hover'>";
echo "<thead>";
echo "<tr>";
echo "<th>ID</th>";
echo "<th>Nombre</th>";
echo "<th>Email</th>";
echo "<th>Rol</th>";
echo "<th>Acciones</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";

// Usuario 1
echo "<tr>";
echo "<td>1</td>";
echo "<td><strong>Juan Pérez</strong></td>";
echo "<td>juan@example.com</td>";
echo "<td><span class='badge bg-primary'>Socio</span></td>";
echo "<td>";
echo "<button type='button' class='btn btn-sm btn-outline-primary' onclick='openEditModal(1, \"Juan\", \"Pérez\", \"juan@example.com\", \"socio\", true)' title='Editar'>";
echo "<i class='fas fa-edit'></i>";
echo "</button>";
echo "</td>";
echo "</tr>";

// Usuario 2
echo "<tr>";
echo "<td>2</td>";
echo "<td><strong>María García</strong></td>";
echo "<td>maria@example.com</td>";
echo "<td><span class='badge bg-secondary'>Usuario</span></td>";
echo "<td>";
echo "<button type='button' class='btn btn-sm btn-outline-primary' onclick='openEditModal(2, \"María\", \"García\", \"maria@example.com\", \"user\", false)' title='Editar'>";
echo "<i class='fas fa-edit'></i>";
echo "</button>";
echo "</td>";
echo "</tr>";

// Usuario 3
echo "<tr>";
echo "<td>3</td>";
echo "<td><strong>Admin Sistema</strong></td>";
echo "<td>admin@example.com</td>";
echo "<td><span class='badge bg-danger'>Admin</span></td>";
echo "<td>";
echo "<button type='button' class='btn btn-sm btn-outline-primary' onclick='openEditModal(3, \"Admin\", \"Sistema\", \"admin@example.com\", \"admin\", true)' title='Editar'>";
echo "<i class='fas fa-edit'></i>";
echo "</button>";
echo "</td>";
echo "</tr>";

echo "</tbody>";
echo "</table>";
echo "</div>";
echo "</div>";
echo "</div>";

// Modal de edición
echo "<div class='modal fade' id='editUserModal' tabindex='-1' aria-labelledby='editUserModalLabel' aria-hidden='true'>";
echo "<div class='modal-dialog'>";
echo "<div class='modal-content'>";
echo "<div class='modal-header'>";
echo "<h5 class='modal-title' id='editUserModalLabel'>Editar Usuario</h5>";
echo "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
echo "</div>";
echo "<form id='editUserForm' method='POST'>";
echo "<div class='modal-body'>";
echo "<div class='mb-3'>";
echo "<label for='editNombre' class='form-label'>Nombre</label>";
echo "<input type='text' class='form-control' id='editNombre' name='nombre' required>";
echo "</div>";
echo "<div class='mb-3'>";
echo "<label for='editApellidos' class='form-label'>Apellidos</label>";
echo "<input type='text' class='form-control' id='editApellidos' name='apellidos'>";
echo "</div>";
echo "<div class='mb-3'>";
echo "<label for='editEmail' class='form-label'>Email</label>";
echo "<input type='email' class='form-control' id='editEmail' name='email' required>";
echo "</div>";
echo "<div class='mb-3'>";
echo "<label for='editRol' class='form-label'>Rol</label>";
echo "<select class='form-select' id='editRol' name='rol'>";
echo "<option value='user'>Usuario</option>";
echo "<option value='socio'>Socio</option>";
echo "<option value='admin'>Administrador</option>";
echo "</select>";
echo "</div>";
echo "<div class='form-check'>";
echo "<input class='form-check-input' type='checkbox' id='editActivo' name='activo' value='1'>";
echo "<label class='form-check-label' for='editActivo'>Usuario activo</label>";
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

echo "</div>";

// Bootstrap JS
echo "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>";

// JavaScript
echo "<script>";
echo "// Variable global para el modal";
echo "let editModal;";
echo "";
echo "// Función para abrir modal de edición";
echo "function openEditModal(userId, nombre, apellidos, email, rol, activo) {";
echo "    console.log('=== PRUEBA MODAL ===');";
echo "    console.log('Abriendo modal de edición para usuario:', userId);";
echo "    console.log('Datos:', { nombre, apellidos, email, rol, activo });";
echo "    ";
echo "    // Llenar el formulario";
echo "    document.getElementById('editNombre').value = nombre;";
echo "    document.getElementById('editApellidos').value = apellidos;";
echo "    document.getElementById('editEmail').value = email;";
echo "    document.getElementById('editRol').value = rol;";
echo "    document.getElementById('editActivo').checked = activo;";
echo "    ";
echo "    // Mostrar el modal";
echo "    if (editModal) {";
echo "        console.log('Modal encontrado, mostrando...');";
echo "        editModal.show();";
echo "        console.log('Modal mostrado correctamente');";
echo "    } else {";
echo "        console.error('Modal no inicializado');";
echo "        alert('Error: Modal no inicializado');";
echo "    }";
echo "}";
echo "";
echo "// Inicializar cuando el DOM esté listo";
echo "document.addEventListener('DOMContentLoaded', function() {";
echo "    console.log('Página cargada correctamente');";
echo "    console.log('Bootstrap disponible:', typeof bootstrap !== 'undefined');";
echo "    ";
echo "    // Inicializar modal";
echo "    try {";
echo "        editModal = new bootstrap.Modal(document.getElementById('editUserModal'));";
echo "        console.log('Modal inicializado correctamente');";
echo "    } catch (error) {";
echo "        console.error('Error al inicializar modal:', error);";
echo "        alert('Error al inicializar modal: ' + error.message);";
echo "    }";
echo "    ";
echo "    // Event listener para el formulario";
echo "    document.getElementById('editUserForm').addEventListener('submit', function(e) {";
echo "        e.preventDefault();";
echo "        console.log('Formulario enviado');";
echo "        alert('Formulario enviado correctamente (simulado)');";
echo "        editModal.hide();";
echo "    });";
echo "});";
echo "</script>";

echo "</body>";
echo "</html>";
?>
