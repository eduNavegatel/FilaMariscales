<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Filá Mariscales</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body { background-color: #f8f9fa; }
        .card { box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .btn-group .btn { margin-right: 2px; }
        
        /* Estilos para botones con texto */
        .btn-group .btn {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            white-space: nowrap;
        }
        
        /* Asegurar que los botones de grupo tengan el mismo tamaño */
        .btn-group .btn {
            flex: 1;
            min-width: 0;
        }
        
        /* Espaciado entre icono y texto */
        .btn i {
            margin-right: 0.25rem;
        }
        
        /* Modal personalizado */
        .custom-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        
        .custom-modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 0;
            border: 1px solid #888;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .custom-modal-header {
            padding: 15px 20px;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .custom-modal-body {
            padding: 20px;
        }
        
        .custom-modal-footer {
            padding: 15px 20px;
            border-top: 1px solid #dee2e6;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        
        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            border: none;
            background: none;
        }
        
        .close:hover {
            color: #000;
        }
    </style>
</head>
<body>
    <?php
    // Función simple para generar CSRF token
    if (!function_exists('generateCsrfToken')) {
        function generateCsrfToken() {
            return bin2hex(random_bytes(32));
        }
    }
    ?>
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/prueba-php/public/admin/dashboard">
                <i class="fas fa-shield-alt me-2"></i>Panel de Administración
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="/prueba-php/public/admin/dashboard">Dashboard</a>
                <a class="nav-link active" href="/prueba-php/public/admin/usuarios">Usuarios</a>
                <a class="nav-link" href="/prueba-php/public/admin/galeria">Galería</a>
                <a class="nav-link" href="/prueba-php/public/admin/logout">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Gestión de Usuarios</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="/prueba-php/public/admin/crearUsuario" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nuevo Usuario
                </a>
            </div>
        </div>

        <!-- Users Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Lista de Usuarios</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th>Fecha Registro</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($data['users'])): ?>
                                <?php foreach ($data['users'] as $user): ?>
                                    <tr>
                                        <td><?= $user->id ?></td>
                                        <td>
                                            <strong><?= htmlspecialchars($user->nombre . ' ' . $user->apellidos) ?></strong>
                                        </td>
                                        <td><?= htmlspecialchars($user->email) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $user->rol === 'admin' ? 'danger' : ($user->rol === 'socio' ? 'primary' : 'secondary') ?>">
                                                <?= ucfirst($user->rol) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($user->activo): ?>
                                                <span class="badge bg-success">Activo</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= date('d/m/Y H:i', strtotime($user->created_at)) ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <!-- Botón Editar -->
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-primary" 
                                                        onclick="openEditModal(<?= $user->id ?>, '<?= htmlspecialchars($user->nombre) ?>', '<?= htmlspecialchars($user->apellidos) ?>', '<?= htmlspecialchars($user->email) ?>', '<?= $user->rol ?>', <?= $user->activo ? 'true' : 'false' ?>)"
                                                        title="Editar usuario">
                                                    <i class="fas fa-edit me-1"></i>Editar
                                                </button>
                                                
                                                <!-- Botón Activar/Desactivar -->
                                                <?php if ($user->activo): ?>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-warning"
                                                            onclick="toggleUserStatus(<?= $user->id ?>, 'desactivar')"
                                                            title="Desactivar usuario">
                                                        <i class="fas fa-user-slash me-1"></i>Desactivar
                                                    </button>
                                                <?php else: ?>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-success"
                                                            onclick="toggleUserStatus(<?= $user->id ?>, 'activar')"
                                                            title="Activar usuario">
                                                        <i class="fas fa-user-check me-1"></i>Activar
                                                    </button>
                                                <?php endif; ?>
                                                
                                                <!-- Botón Resetear Contraseña -->
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-info"
                                                        onclick="openResetModal(<?= $user->id ?>)"
                                                        title="Resetear contraseña">
                                                    <i class="fas fa-key me-1"></i>Resetear
                                                </button>
                                                
                                                <!-- Botón Eliminar -->
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="deleteUser(<?= $user->id ?>)"
                                                        title="Eliminar usuario">
                                                    <i class="fas fa-trash me-1"></i>Eliminar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">No hay usuarios registrados</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editUserModal" class="custom-modal">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <h5 class="modal-title">Editar Usuario</h5>
                <button type="button" class="close" onclick="closeEditModal()">&times;</button>
            </div>
            <form id="editUserForm" method="POST">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                <div class="custom-modal-body">
                    <div class="mb-3">
                        <label for="editNombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="editNombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="editApellidos" class="form-label">Apellidos</label>
                        <input type="text" class="form-control" id="editApellidos" name="apellidos">
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="editRol" class="form-label">Rol</label>
                        <select class="form-select" id="editRol" name="rol">
                            <option value="user">Usuario</option>
                            <option value="socio">Socio</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="editActivo" name="activo" value="1">
                        <label class="form-check-label" for="editActivo">
                            Usuario activo
                        </label>
                    </div>
                </div>
                <div class="custom-modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reset Password Modal -->
    <div id="resetPasswordModal" class="custom-modal">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <h5 class="modal-title">Resetear Contraseña</h5>
                <button type="button" class="close" onclick="closeResetModal()">&times;</button>
            </div>
            <form id="resetPasswordForm" method="POST">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                <div class="custom-modal-body">
                    <p>¿Estás seguro de que quieres resetear la contraseña de este usuario?</p>
                    <p>Se generará una nueva contraseña aleatoria.</p>
                </div>
                <div class="custom-modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeResetModal()">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Resetear Contraseña</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    // Variables globales
    let currentUserId;

    // Función para abrir modal de edición
    function openEditModal(userId, nombre, apellidos, email, rol, activo) {
        console.log('Abriendo modal de edición para usuario:', userId);
        console.log('Datos del usuario:', { userId, nombre, apellidos, email, rol, activo });
        
        // Guardar el ID del usuario actual
        currentUserId = userId;
        
        // Llenar el formulario
        document.getElementById('editNombre').value = nombre;
        document.getElementById('editApellidos').value = apellidos;
        document.getElementById('editEmail').value = email;
        document.getElementById('editRol').value = rol;
        document.getElementById('editActivo').checked = activo;
        
        // Actualizar la acción del formulario
        const formAction = '<?= URL_ROOT ?>/admin/editarUsuario/' + userId;
        document.getElementById('editUserForm').action = formAction;
        console.log('Form action set to:', formAction);
        
        // Mostrar el modal
        document.getElementById('editUserModal').style.display = 'block';
    }

    // Función para cerrar modal de edición
    function closeEditModal() {
        document.getElementById('editUserModal').style.display = 'none';
    }

    // Función para abrir modal de resetear contraseña
    function openResetModal(userId) {
        console.log('Abriendo modal de reset para usuario:', userId);
        
        // Actualizar la acción del formulario
        document.getElementById('resetPasswordForm').action = '<?= URL_ROOT ?>/admin/resetearPassword/' + userId;
        
        // Mostrar el modal
        document.getElementById('resetPasswordModal').style.display = 'block';
    }

    // Función para cerrar modal de resetear contraseña
    function closeResetModal() {
        document.getElementById('resetPasswordModal').style.display = 'none';
    }

    // Función para activar/desactivar usuario
    function toggleUserStatus(userId, action) {
        const message = action === 'activar' ? 
            '¿Estás seguro de activar este usuario?' : 
            '¿Estás seguro de desactivar este usuario?';
            
        if (confirm(message)) {
            const url = action === 'activar' ? 
                '<?= URL_ROOT ?>/admin/activarUsuario/' + userId :
                '<?= URL_ROOT ?>/admin/desactivarUsuario/' + userId;
                
            // Crear formulario temporal y enviarlo
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = 'csrf_token';
            csrfInput.value = '<?= generateCsrfToken() ?>';
            
            form.appendChild(csrfInput);
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Función para eliminar usuario
    function deleteUser(userId) {
        if (confirm('¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.')) {
            // Crear formulario temporal y enviarlo
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= URL_ROOT ?>/admin/eliminarUsuario/' + userId;
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = 'csrf_token';
            csrfInput.value = '<?= generateCsrfToken() ?>';
            
            form.appendChild(csrfInput);
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Event listener para el formulario de edición
    document.getElementById('editUserForm').addEventListener('submit', function(e) {
        console.log('Formulario de edición enviado');
        console.log('Form action:', this.action);
        console.log('Form data:', new FormData(this));
        
        // Log form values
        const formData = new FormData(this);
        for (let [key, value] of formData.entries()) {
            console.log(key + ': ' + value);
        }
    });

    // Cerrar modales al hacer clic fuera de ellos
    window.onclick = function(event) {
        const editModal = document.getElementById('editUserModal');
        const resetModal = document.getElementById('resetPasswordModal');
        
        if (event.target === editModal) {
            closeEditModal();
        }
        if (event.target === resetModal) {
            closeResetModal();
        }
    }

    // Inicializar cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Página de usuarios cargada correctamente');
        
        // Event listeners para formularios
        document.getElementById('editUserForm').addEventListener('submit', function(e) {
            console.log('Enviando formulario de edición para usuario:', currentUserId);
        });
        
        document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
            console.log('Reseteando contraseña...');
        });
    });
    </script>
</body>
</html>
