<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Filá Mariscales</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome - Múltiples CDNs como respaldo -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" onerror="this.onerror=null;this.href='https://use.fontawesome.com/releases/v6.0.0/css/all.css';">
    <link href="https://use.fontawesome.com/releases/v6.0.0/css/all.css" rel="stylesheet" onerror="this.onerror=null;this.href='https://maxcdn.bootstrapcdn.com/font-awesome/6.0.0/css/font-awesome.min.css';">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/6.0.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- Admin CSS -->
    <link href="/prueba-php/public/assets/css/admin.css" rel="stylesheet">
    <!-- Font Awesome Fallback CSS -->
    <link href="/prueba-php/public/assets/css/fontawesome-fallback.css" rel="stylesheet">
    
    <style>
        body { 
            background-color: #f8f9fa; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card { 
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); 
            border: none;
            border-radius: 8px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        .bg-danger { background: linear-gradient(135deg, #007bff, #0056b3) !important; }
        .bg-success { background: linear-gradient(135deg, #28a745, #1e7e34) !important; }
        .bg-info { background: linear-gradient(135deg, #17a2b8, #138496) !important; }
        .bg-warning { background: linear-gradient(135deg, #ffc107, #e0a800) !important; }
        .bg-dark { background: linear-gradient(135deg, #343a40, #212529) !important; }
        .bg-danger { background: linear-gradient(135deg, #dc3545, #c82333) !important; }
        .bg-secondary { background: linear-gradient(135deg, #6c757d, #545b62) !important; }
        .navbar-brand { font-weight: bold; }
        .btn-toolbar .btn { border-radius: 6px; }
        .border-bottom { border-color: #dee2e6 !important; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/prueba-php/public/admin/dashboard">
                <i class="fas fa-shield-alt me-2"></i>Panel de Administración
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="/prueba-php/public/admin/dashboard">Dashboard</a>
                <a class="nav-link" href="/prueba-php/public/admin/usuarios">Usuarios</a>
                <a class="nav-link" href="/prueba-php/public/admin/eventos">Eventos</a>
                <a class="nav-link" href="/prueba-php/public/admin/galeria">Galería</a>
                <a class="nav-link" href="/prueba-php/public/admin/logout">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Panel de Control</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="/prueba-php/public/admin/export/dashboard" class="btn btn-sm btn-outline-secondary" title="Exportar CSV">
                Exportar
            </a>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()" title="Imprimir página">
                Imprimir
            </button>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-danger h-100">
            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title mb-1">Usuarios Registrados</h5>
                        <h2 class="mb-0"><?= $userCount ?></h2>
                        <small class="opacity-75">Total de miembros</small>
                    </div>
                    <i class="fas fa-users fa-2x opacity-50"></i>
                </div>
                <a href="<?= URL_ROOT ?>/admin/usuarios" class="btn btn-light btn-sm mt-auto">
                    <i class="fas fa-users me-1"></i>Ver usuarios
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success h-100">
            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title mb-1">Eventos Activos</h5>
                        <h2 class="mb-0"><?= $eventCount ?></h2>
                        <small class="opacity-75">En curso y próximos</small>
                    </div>
                    <i class="fas fa-calendar-alt fa-2x opacity-50"></i>
                </div>
                <a href="<?= URL_ROOT ?>/admin/eventos" class="btn btn-light btn-sm mt-auto">
                    <i class="fas fa-calendar-alt me-1"></i>Ver eventos
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-info h-100">
            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title mb-1">Galería</h5>
                        <h2 class="mb-0"><?= isset($galleryCount) ? $galleryCount : 0 ?></h2>
                        <small class="opacity-75">Archivos multimedia</small>
                    </div>
                    <i class="fas fa-images fa-2x opacity-50"></i>
                </div>
                <a href="<?= URL_ROOT ?>/admin/galeria" class="btn btn-light btn-sm mt-auto">
                    <i class="fas fa-images me-1"></i>Gestionar galería
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-warning h-100">
            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title mb-1">Acciones Rápidas</h5>
                        <h2 class="mb-0">+</h2>
                        <small class="opacity-75">Herramientas</small>
                    </div>
                    <i class="fas fa-plus fa-2x opacity-50"></i>
                </div>
                <div class="mt-auto">
                    <a href="/prueba-php/public/admin/crearUsuario" class="text-white d-block small">Nuevo usuario</a>
                    <a href="/prueba-php/public/admin/nuevoEvento" class="text-white d-block small">Nuevo evento</a>
                    <a href="/prueba-php/public/admin/galeria" class="text-white d-block small">Subir archivos</a>
                    <a href="/prueba-php/public/admin/noticias/nueva" class="text-white d-block small">Nueva noticia</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estadísticas Secundarias -->
<div class="row mb-4">

    <div class="col-md-3 mb-3">
        <div class="card text-white bg-dark h-100">
            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title mb-1">Noticias</h5>
                        <h2 class="mb-0"><?= isset($data['newsCount']) ? $data['newsCount'] : 0 ?></h2>
                        <small class="opacity-75">Publicaciones</small>
                    </div>
                    <i class="fas fa-newspaper fa-2x opacity-50"></i>
                </div>
                <a href="<?= URL_ROOT ?>/admin/noticias" class="btn btn-light btn-sm mt-auto">
                    <i class="fas fa-newspaper me-1"></i>Gestionar noticias
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-danger h-100">
            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title mb-1">Mensajes</h5>
                        <h2 class="mb-0"><?= isset($data['messagesCount']) ? $data['messagesCount'] : 0 ?></h2>
                        <small class="opacity-75">Sin leer</small>
                    </div>
                    <i class="fas fa-envelope fa-2x opacity-50"></i>
                </div>
                <a href="<?= URL_ROOT ?>/admin/mensajes" class="btn btn-light btn-sm mt-auto">
                    <i class="fas fa-envelope me-1"></i>Ver mensajes
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-purple h-100" style="background: linear-gradient(135deg, #6f42c1 0%, #5a2d91 100%);">
            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title mb-1">Cuotas Pendientes</h5>
                        <h2 class="mb-0"><?= isset($data['pendingFees']) ? $data['pendingFees'] : 0 ?></h2>
                        <small class="opacity-75">Por cobrar</small>
                    </div>
                    <i class="fas fa-exclamation-triangle fa-2x opacity-50"></i>
                </div>
                <a href="<?= URL_ROOT ?>/admin/cuotas" class="btn btn-light btn-sm mt-auto">
                    <i class="fas fa-exclamation-triangle me-1"></i>Gestionar cuotas
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 text-white">Últimos Usuarios Registrados</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($data['recentUsers'])): ?>
                                <?php foreach ($data['recentUsers'] as $user): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($user->nombre . ' ' . $user->apellidos) ?></td>
                                        <td><?= htmlspecialchars($user->email) ?></td>
                                        <td><span class="badge bg-<?= $user->rol === 'admin' ? 'primary' : 'secondary' ?>">
                                            <?= ucfirst($user->rol) ?>
                                        </span></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center py-3 text-secondary">No hay usuarios registrados</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 text-white">Próximos Eventos</h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php if (!empty($data['recentEvents'])): ?>
                        <?php foreach ($data['recentEvents'] as $event): ?>
                            <a href="<?= URL_ROOT ?>/admin/events/<?= $event->id ?>/edit" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1 text-dark"><?= htmlspecialchars($event->titulo) ?></h6>
                                    <small class="text-secondary">
                                        <?= date('d M', strtotime($event->fecha)) ?>
                                    </small>
                                </div>
                                <p class="mb-1 text-secondary">
                                    <i class="far fa-clock me-1"></i> 
                                    <?= date('H:i', strtotime($event->hora)) ?> - 
                                    <?= htmlspecialchars($event->lugar) ?>
                                </p>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-3 text-secondary">No hay eventos próximos</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Herramientas Administrativas -->
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0 text-white">
                    <i class="fas fa-tools me-2"></i>Herramientas Administrativas
                </h6>
            </div>
            <div class="card-body d-flex flex-column">
                <div class="row flex-grow-1 admin-tools">
                    <div class="col-6 mb-3">
                        <a href="<?= URL_ROOT ?>/admin/backup" class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                            <i class="fas fa-database fa-2x mb-2"></i>Backup BD
                        </a>
                    </div>
                    <div class="col-6 mb-3">
                        <a href="<?= URL_ROOT ?>/admin/logs" class="btn btn-outline-secondary w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                            <i class="fas fa-file-alt fa-2x mb-2"></i>Ver Logs
                        </a>
                    </div>
                    <div class="col-6 mb-3">
                        <a href="<?= URL_ROOT ?>/admin/settings" class="btn btn-outline-info w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                            <i class="fas fa-cog fa-2x mb-2"></i>Configuración
                        </a>
                    </div>
                    <div class="col-6 mb-3">
                        <a href="<?= URL_ROOT ?>/admin/export" class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                            <i class="fas fa-download fa-2x mb-2"></i>Exportar Datos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0 text-white">
                    <i class="fas fa-tasks me-2"></i>Tareas Pendientes
                </h6>
            </div>
            <div class="card-body d-flex flex-column">
                <div class="task-list flex-grow-1">
                    <div class="task-item d-flex align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="task1">
                            <label class="form-check-label text-secondary" for="task1">
                                Revisar solicitudes de socios
                            </label>
                        </div>
                        <span class="badge bg-warning ms-auto">Urgente</span>
                    </div>
                    
                    <div class="task-item d-flex align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="task2">
                            <label class="form-check-label text-secondary" for="task2">
                                Actualizar calendario de eventos
                            </label>
                        </div>
                        <span class="badge bg-info ms-auto">Media</span>
                    </div>
                    
                    <div class="task-item d-flex align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="task3">
                            <label class="form-check-label text-secondary" for="task3">
                                Revisar mensajes de contacto
                            </label>
                        </div>
                        <span class="badge bg-success ms-auto">Baja</span>
                    </div>
                    
                    <div class="task-item d-flex align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="task4">
                            <label class="form-check-label text-secondary" for="task4">
                                Hacer backup de la base de datos
                            </label>
                        </div>
                        <span class="badge bg-danger ms-auto">Semanal</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 text-white">Actividad Reciente</h6>
            </div>
            <div class="card-body">
                <div class="activity-feed">
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm bg-light rounded-circle text-danger d-flex align-items-center justify-content-center">
                                <i class="fas fa-user-plus"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 text-dark">Nuevo usuario registrado</h6>
                            <p class="text-secondary small mb-0">Juan Pérez se ha registrado en el sistema</p>
                            <small class="text-secondary">Hace 2 horas</small>
                        </div>
                    </div>

                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm bg-light rounded-circle text-warning d-flex align-items-center justify-content-center">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 text-dark">Intento de inicio de sesión fallido</h6>
                            <p class="text-secondary small mb-0">Se detectó un intento de inicio de sesión fallido para admin@example.com</p>
                            <small class="text-secondary">Ayer a las 08:15</small>
                        </div>
                    </div>

                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm bg-light rounded-circle text-success d-flex align-items-center justify-content-center">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 text-dark">Nuevo evento creado</h6>
                            <p class="text-secondary small mb-0">Se ha creado el evento "Reunión de Directiva"</p>
                            <small class="text-secondary">Hace 1 día</small>
                        </div>
                    </div>

                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm bg-light rounded-circle text-info d-flex align-items-center justify-content-center">
                                <i class="fas fa-image"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 text-dark">Imágenes subidas a la galería</h6>
                            <p class="text-secondary small mb-0">Se han subido 5 nuevas imágenes del último evento</p>
                            <small class="text-secondary">Hace 2 días</small>
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm bg-light rounded-circle text-danger d-flex align-items-center justify-content-center">
                                <i class="fas fa-newspaper"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 text-dark">Nueva noticia publicada</h6>
                            <p class="text-secondary small mb-0">Se ha publicado "Preparativos para las fiestas 2024"</p>
                            <small class="text-secondary">Hace 3 días</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notificaciones y Alertas -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 text-white">
                    <i class="fas fa-bell me-2"></i>Notificaciones y Alertas
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>¡Atención!</strong> Hay 3 solicitudes de socios pendientes de revisión.
                    <a href="<?= URL_ROOT ?>/admin/solicitudes" class="alert-link">Ver solicitudes</a>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-calendar me-2"></i>
                    <strong>Próximo evento:</strong> Reunión de Directiva el próximo viernes a las 20:00.
                    <a href="<?= URL_ROOT ?>/admin/eventos" class="alert-link">Ver detalles</a>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>¡Éxito!</strong> El backup de la base de datos se completó correctamente.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts para funcionalidades adicionales -->
<script>
// Funcionalidad para las tareas pendientes
document.querySelectorAll('.task-item input[type="checkbox"]').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const taskItem = this.closest('.task-item');
        if (this.checked) {
            taskItem.style.opacity = '0.6';
            taskItem.style.textDecoration = 'line-through';
        } else {
            taskItem.style.opacity = '1';
            taskItem.style.textDecoration = 'none';
        }
    });
});

// Funcionalidad para las alertas
document.querySelectorAll('.alert-dismissible .btn-close').forEach(button => {
    button.addEventListener('click', function() {
        const alert = this.closest('.alert');
        alert.style.transition = 'opacity 0.5s';
        alert.style.opacity = '0';
        setTimeout(() => {
            alert.remove();
        }, 500);
    });
});

// Actualizar contadores en tiempo real (ejemplo)
setInterval(() => {
    // Aquí se podría hacer una llamada AJAX para actualizar los contadores
    console.log('Actualizando dashboard...');
}, 30000);

// Mostrar notificación de bienvenida
document.addEventListener('DOMContentLoaded', function() {
    const welcomeNotification = new bootstrap.Toast(document.getElementById('welcomeToast'));
    welcomeNotification.show();
    
    // Inicializar gráficos
    initializeCharts();
    
    // Inicializar calendario
    initializeCalendar();
});

// Funciones para el editor de contenido
function saveContent() {
    const mainTitle = document.getElementById('mainTitle').value;
    const welcomeMessage = document.getElementById('welcomeMessage').value;
    const siteStatus = document.getElementById('siteStatus').value;
    
    // Simular guardado
    showNotification('Contenido guardado correctamente', 'success');
    
    // Aquí se enviaría la información al servidor
    console.log('Guardando contenido:', { mainTitle, welcomeMessage, siteStatus });
}

// Funciones para notificaciones
function sendNotification() {
    const target = document.getElementById('notificationTarget').value;
    const subject = document.getElementById('notificationSubject').value;
    const message = document.getElementById('notificationMessage').value;
    
    if (!subject || !message) {
        showNotification('Por favor completa todos los campos', 'error');
        return;
    }
    
    // Simular envío
    showNotification('Notificación enviada a ' + target + ' usuarios', 'success');
    
    // Limpiar formulario
    document.getElementById('notificationSubject').value = '';
    document.getElementById('notificationMessage').value = '';
}

// Plantillas de mensajes
function loadTemplate(type) {
    const templates = {
        evento: {
            subject: 'Nuevo Evento - Filá Mariscales',
            message: 'Se ha programado un nuevo evento. Por favor revisa los detalles en el calendario de la web.'
        },
        reunion: {
            subject: 'Reunión de Directiva',
            message: 'Se convoca a todos los miembros de la directiva a la próxima reunión. Fecha y hora por confirmar.'
        },
        cuota: {
            subject: 'Recordatorio de Cuota',
            message: 'Recordatorio: Tu cuota mensual está próxima a vencer. Por favor, realiza el pago correspondiente.'
        },
        general: {
            subject: 'Información General - Filá Mariscales',
            message: 'Información importante para todos los miembros de la Filá Mariscales de Caballeros Templarios.'
        }
    };
    
    const template = templates[type];
    if (template) {
        document.getElementById('notificationSubject').value = template.subject;
        document.getElementById('notificationMessage').value = template.message;
    }
}

// Sistema de notificaciones
function showNotification(message, type = 'info') {
    const toastContainer = document.querySelector('.toast-container');
    const toastId = 'toast-' + Date.now();
    
    const toastHtml = `
        <div id="${toastId}" class="toast" role="alert">
            <div class="toast-header">
                <i class="fas fa-${type === 'success' ? 'check-circle text-success' : type === 'error' ? 'exclamation-triangle text-danger' : 'info-circle text-info'} me-2"></i>
                <strong class="me-auto">Notificación</strong>
                <small>Ahora</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        </div>
    `;
    
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    
    const toast = new bootstrap.Toast(document.getElementById(toastId));
    toast.show();
    
    // Eliminar toast después de que se oculte
    document.getElementById(toastId).addEventListener('hidden.bs.toast', function() {
        this.remove();
    });
}

// Inicializar gráficos
function initializeCharts() {
    // Gráfico de visitas
    const visitsCtx = document.getElementById('visitsChart');
    if (visitsCtx) {
        new Chart(visitsCtx, {
            type: 'line',
            data: {
                labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                datasets: [{
                    label: 'Visitas',
                    data: [65, 59, 80, 81, 56, 55, 40],
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.1)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    
    // Gráfico de eventos
    const eventsCtx = document.getElementById('eventsChart');
    if (eventsCtx) {
        new Chart(eventsCtx, {
            type: 'doughnut',
            data: {
                labels: ['Activos', 'Completados', 'Cancelados'],
                datasets: [{
                    data: [12, 19, 3],
                    backgroundColor: [
                        'rgb(54, 162, 235)',
                        'rgb(75, 192, 192)',
                        'rgb(255, 99, 132)'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
    

}

// Inicializar calendario
function initializeCalendar() {
    const calendarEl = document.getElementById('miniCalendar');
    if (calendarEl) {
        // Crear un calendario simple
        const today = new Date();
        const currentMonth = today.getMonth();
        const currentYear = today.getFullYear();
        
        const monthNames = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                           'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        
        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
        const firstDay = new Date(currentYear, currentMonth, 1).getDay();
        
        let calendarHTML = `
            <div class="text-center mb-2">
                <h6>${monthNames[currentMonth]} ${currentYear}</h6>
            </div>
            <div class="calendar-grid">
                <div class="calendar-header">
                    <span>L</span><span>M</span><span>X</span><span>J</span><span>V</span><span>S</span><span>D</span>
                </div>
                <div class="calendar-body">
        `;
        
        // Días vacíos al inicio
        for (let i = 0; i < firstDay; i++) {
            calendarHTML += '<span class="calendar-day empty"></span>';
        }
        
        // Días del mes
        for (let day = 1; day <= daysInMonth; day++) {
            const isToday = day === today.getDate();
            const hasEvent = [15, 20, 25].includes(day); // Días con eventos
            
            calendarHTML += `
                <span class="calendar-day ${isToday ? 'today' : ''} ${hasEvent ? 'has-event' : ''}">
                    ${day}
                </span>
            `;
        }
        
        calendarHTML += '</div></div>';
        calendarEl.innerHTML = calendarHTML;
    }
}


</script>

<style>
/* Estilos adicionales para las nuevas funcionalidades */
.calendar-grid {
    display: grid;
    gap: 2px;
}

.calendar-header {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    text-align: center;
    font-weight: bold;
    font-size: 0.8rem;
    color: #666;
}

.calendar-body {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 1px;
}

.calendar-day {
    padding: 4px;
    text-align: center;
    font-size: 0.8rem;
    border-radius: 3px;
    cursor: pointer;
    transition: background-color 0.2s;
}

.calendar-day:hover {
    background-color: #f8f9fa;
}

.calendar-day.today {
    background-color: #007bff;
    color: white;
}

.calendar-day.has-event {
    background-color: #ffc107;
    color: #212529;
    font-weight: bold;
}

.calendar-day.empty {
    background-color: transparent;
}



.permission-item {
    padding: 8px 0;
    border-bottom: 1px solid #eee;
}

.permission-item:last-child {
    border-bottom: none;
}

.template-item button {
    transition: all 0.2s;
}

.template-item button:hover {
    background-color: #007bff;
    color: white;
}

.system-status .status-item {
    padding: 8px 0;
    border-bottom: 1px solid #eee;
}

.system-status .status-item:last-child {
    border-bottom: none;
}

.event-item {
    transition: background-color 0.2s;
    padding: 8px;
    border-radius: 5px;
}

.event-item:hover {
    background-color: #f8f9fa;
}

.event-date {
    font-size: 0.7rem;
    line-height: 1;
}

.event-info {
    flex: 1;
}

/* Animaciones para las tarjetas */
.card {
    transition: box-shadow 0.3s ease;
}

.card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .calendar-day {
        font-size: 0.7rem;
        padding: 2px;
    }
    

}
</style>

<!-- Toast de bienvenida -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="welcomeToast" class="toast" role="alert">
        <div class="toast-header">
            <i class="fas fa-user-shield me-2 text-danger"></i>
            <strong class="me-auto">Bienvenido al Panel de Control</strong>
            <small>Ahora</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            ¡Hola! Has iniciado sesión correctamente en el panel de administración de la Filá Mariscales.
        </div>
    </div>
</div>

<!-- Panel de Control Avanzado -->
<div class="row mb-4">
    <div class="col-md-8 mb-3">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0 text-white">
                    <i class="fas fa-chart-line me-2"></i>Estadísticas en Tiempo Real
                </h6>
            </div>
            <div class="card-body d-flex flex-column">
                <div class="row flex-grow-1">
                    <div class="col-md-6">
                        <canvas id="visitsChart" width="300" height="200"></canvas>
                    </div>
                    <div class="col-md-6">
                        <canvas id="eventsChart" width="300" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0 text-white">
                    <i class="fas fa-clock me-2"></i>Estado del Sistema
                </h6>
            </div>
            <div class="card-body d-flex flex-column">
                <div class="system-status flex-grow-1">
                    <div class="status-item d-flex justify-content-between align-items-center mb-3">
                        <span>Base de Datos</span>
                        <span class="badge bg-success">Online</span>
                    </div>
                    <div class="status-item d-flex justify-content-between align-items-center mb-3">
                        <span>Servidor Web</span>
                        <span class="badge bg-success">Activo</span>
                    </div>
                    <div class="status-item d-flex justify-content-between align-items-center mb-3">
                        <span>Último Backup</span>
                        <span class="badge bg-info">Hace 2h</span>
                    </div>
                    <div class="status-item d-flex justify-content-between align-items-center">
                        <span>Espacio en Disco</span>
                        <span class="badge bg-warning">75%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gestión de Contenido -->
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0 text-white">
                    <i class="fas fa-edit me-2"></i>Editor Rápido de Contenido
                </h6>
            </div>
            <div class="card-body d-flex flex-column">
                <div class="mb-3">
                    <label class="form-label">Título de la Página Principal</label>
                    <input type="text" class="form-control" value="Filá Mariscales de Caballeros Templarios" id="mainTitle">
                </div>
                <div class="mb-3">
                    <label class="form-label">Mensaje de Bienvenida</label>
                    <textarea class="form-control" rows="3" id="welcomeMessage">Bienvenidos a la página oficial de la Filá Mariscales de Caballeros Templarios de Elche.</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Estado del Sitio</label>
                    <select class="form-select" id="siteStatus">
                        <option value="online">Online</option>
                        <option value="maintenance">Mantenimiento</option>
                        <option value="offline">Offline</option>
                    </select>
                </div>
                <button class="btn btn-primary mt-auto" onclick="saveContent()">
                    <i class="fas fa-save me-2"></i>Guardar Cambios
                </button>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0 text-white">
                    <i class="fas fa-users me-2"></i>Gestión de Permisos
                </h6>
            </div>
            <div class="card-body d-flex flex-column">
                <div class="permission-grid flex-grow-1">
                    <div class="permission-item d-flex justify-content-between align-items-center mb-3">
                        <span>Crear Eventos</span>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="permEvents" checked>
                        </div>
                    </div>
                    <div class="permission-item d-flex justify-content-between align-items-center mb-3">
                        <span>Gestionar Usuarios</span>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="permUsers" checked>
                        </div>
                    </div>
                    <div class="permission-item d-flex justify-content-between align-items-center mb-3">
                        <span>Subir Archivos</span>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="permFiles" checked>
                        </div>
                    </div>
                    <div class="permission-item d-flex justify-content-between align-items-center mb-3">
                        <span>Ver Logs</span>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="permLogs">
                        </div>
                    </div>
                    <div class="permission-item d-flex justify-content-between align-items-center">
                        <span>Acceso Completo</span>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="permAdmin" checked>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Comunicaciones y Notificaciones -->
<div class="row mb-4">
    <div class="col-md-8 mb-3">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0 text-white">
                    <i class="fas fa-bullhorn me-2"></i>Centro de Comunicaciones
                </h6>
            </div>
            <div class="card-body d-flex flex-column">
                <div class="row flex-grow-1">
                    <div class="col-md-6">
                        <h6>Enviar Notificación Masiva</h6>
                        <div class="mb-3">
                            <label class="form-label">Destinatarios</label>
                            <select class="form-select" id="notificationTarget">
                                <option value="all">Todos los usuarios</option>
                                <option value="socios">Solo socios</option>
                                <option value="admin">Solo administradores</option>
                                <option value="custom">Personalizado</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Asunto</label>
                            <input type="text" class="form-control" id="notificationSubject" placeholder="Asunto de la notificación">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mensaje</label>
                            <textarea class="form-control" rows="4" id="notificationMessage" placeholder="Escribe tu mensaje aquí..."></textarea>
                        </div>
                        <button class="btn btn-warning" onclick="sendNotification()">
                            <i class="fas fa-paper-plane me-2"></i>Enviar Notificación
                        </button>
                    </div>
                    <div class="col-md-6">
                        <h6>Plantillas de Mensajes</h6>
                        <div class="template-list">
                            <div class="template-item mb-2">
                                <button class="btn btn-outline-secondary btn-sm w-100 text-start" onclick="loadTemplate('evento')">
                                    <i class="fas fa-calendar me-2"></i>Nuevo Evento
                                </button>
                            </div>
                            <div class="template-item mb-2">
                                <button class="btn btn-outline-secondary btn-sm w-100 text-start" onclick="loadTemplate('reunion')">
                                    <i class="fas fa-users me-2"></i>Reunión de Directiva
                                </button>
                            </div>
                            <div class="template-item mb-2">
                                <button class="btn btn-outline-secondary btn-sm w-100 text-start" onclick="loadTemplate('cuota')">
                                    <i class="fas fa-credit-card me-2"></i>Recordatorio de Cuota
                                </button>
                            </div>
                            <div class="template-item">
                                <button class="btn btn-outline-secondary btn-sm w-100 text-start" onclick="loadTemplate('general')">
                                    <i class="fas fa-info-circle me-2"></i>Información General
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0 text-white">
                    <i class="fas fa-calendar-alt me-2"></i>Calendario Rápido
                </h6>
            </div>
            <div class="card-body d-flex flex-column">
                <div id="miniCalendar"></div>
                <div class="mt-3 flex-grow-1">
                    <h6>Próximos Eventos</h6>
                    <div class="upcoming-events">
                        <div class="event-item d-flex align-items-center mb-2">
                            <div class="event-date bg-danger text-white rounded p-2 me-2 text-center" style="min-width: 40px;">
                                <small>15</small><br><small>Feb</small>
                            </div>
                            <div class="event-info">
                                <small class="text-muted">Reunión de Directiva</small><br>
                                <small>20:00 - Sede Social</small>
                            </div>
                        </div>
                        <div class="event-item d-flex align-items-center mb-2">
                            <div class="event-date bg-success text-white rounded p-2 me-2 text-center" style="min-width: 40px;">
                                <small>20</small><br><small>Feb</small>
                            </div>
                            <div class="event-info">
                                <small class="text-muted">Ensayo General</small><br>
                                <small>19:30 - Punto de encuentro</small>
                            </div>
                        </div>
                        <div class="event-item d-flex align-items-center">
                            <div class="event-date bg-warning text-white rounded p-2 me-2 text-center" style="min-width: 40px;">
                                <small>25</small><br><small>Feb</small>
                            </div>
                            <div class="event-info">
                                <small class="text-muted">Cena de Hermandad</small><br>
                                <small>21:00 - Restaurante</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Scripts personalizados del dashboard -->
    <script>
        // Función para enviar notificaciones
        function sendNotification() {
            const target = document.getElementById('notificationTarget').value;
            const subject = document.getElementById('notificationSubject').value;
            const message = document.getElementById('notificationMessage').value;
            
            if (!subject || !message) {
                alert('Por favor, completa todos los campos');
                return;
            }
            
            // Aquí iría la lógica para enviar la notificación
            alert('Notificación enviada correctamente');
            
            // Limpiar campos
            document.getElementById('notificationSubject').value = '';
            document.getElementById('notificationMessage').value = '';
        }
        
        // Función para cargar plantillas
        function loadTemplate(type) {
            const templates = {
                evento: {
                    subject: 'Nuevo Evento - Filá Mariscales',
                    message: 'Se ha programado un nuevo evento. Revisa el calendario para más detalles.'
                },
                reunion: {
                    subject: 'Reunión de Directiva',
                    message: 'Se convoca a todos los miembros de la directiva a la próxima reunión.'
                },
                cuota: {
                    subject: 'Recordatorio de Cuota',
                    message: 'Recordatorio: La cuota mensual vence pronto. Por favor, realiza el pago correspondiente.'
                },
                general: {
                    subject: 'Información General',
                    message: 'Información importante para todos los miembros de la Filá Mariscales.'
                }
            };
            
            if (templates[type]) {
                document.getElementById('notificationSubject').value = templates[type].subject;
                document.getElementById('notificationMessage').value = templates[type].message;
            }
        }
        
        // Inicializar tooltips de Bootstrap
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Verificar si Font Awesome se cargó correctamente
            setTimeout(function() {
                var testIcon = document.createElement('i');
                testIcon.className = 'fas fa-test';
                testIcon.style.position = 'absolute';
                testIcon.style.left = '-9999px';
                testIcon.style.visibility = 'hidden';
                document.body.appendChild(testIcon);
                
                var computedStyle = window.getComputedStyle(testIcon);
                var fontFamily = computedStyle.getPropertyValue('font-family');
                
                document.body.removeChild(testIcon);
                
                if (!fontFamily.includes('Font Awesome') && !fontFamily.includes('FontAwesome')) {
                    console.warn('Font Awesome no se cargó correctamente. Intentando cargar desde CDN alternativo...');
                    
                    // Crear un nuevo enlace para Font Awesome desde un CDN alternativo
                    var link = document.createElement('link');
                    link.rel = 'stylesheet';
                    link.href = 'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.0.0/css/all.min.css';
                    document.head.appendChild(link);
                } else {
                    console.log('Font Awesome cargado correctamente');
                }
            }, 1000);
        });
    </script>
</body>
</html>