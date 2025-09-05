<?php
// Página de Socios - Filá Mariscales
// Debug: Verificar que la página se está ejecutando
error_log("Página de socios iniciando...");

// Conexión a la base de datos
require_once dirname(dirname(dirname(__DIR__))) . '/src/config/config.php';
require_once dirname(dirname(dirname(__DIR__))) . '/src/models/Database.php';

try {
    $db = new Database();
    
    // Verificar conexión
    if (!$db) {
        throw new Exception("No se pudo conectar a la base de datos");
    }
    
    // Habilitar reporte de errores para depuración
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
} catch (Exception $e) {
    error_log("Error en página de socios: " . $e->getMessage());
    echo "<div style='background: #f8d7da; color: #721c24; padding: 1rem; margin: 1rem; border: 1px solid #f5c6cb; border-radius: 0.25rem;'>";
    echo "<strong>Error de conexión:</strong> " . htmlspecialchars($e->getMessage());
    echo "</div>";
    return;
}

// Obtener estadísticas reales
$stats = [];

<<<<<<< HEAD
// Contar socios activos (usuarios con rol 'socio' y activo = 1)
$db->query("SELECT COUNT(*) as count FROM users WHERE rol = 'socio' AND activo = 1");
$result = $db->single();
$stats['socios_activos'] = $result ? $result->count : 0;
=======
try {
    // Contar socios activos (usuarios con rol 'socio' y activo = 1)
    $db->query("SELECT COUNT(*) as count FROM users WHERE rol = 'socio' AND activo = 1");
    $result = $db->single();
    $stats['socios_activos'] = $result ? $result->count : 0;
    
    if ($result === false) {
        error_log("Error contando socios activos en página de socios");
        $stats['socios_activos'] = 0;
    }
} catch (Exception $e) {
    error_log("Error en consulta de socios activos: " . $e->getMessage());
    $stats['socios_activos'] = 0;
}
>>>>>>> parece-que-es-buena

// Calcular años de historia (desde 1985)
$anio_fundacion = 1985;
$anio_actual = date('Y');
$stats['anios_historia'] = $anio_actual - $anio_fundacion;

// Contar eventos del año actual
$db->query("SELECT COUNT(*) as count FROM eventos WHERE YEAR(fecha_inicio) = :anio AND estado = 'activo'");
$db->bind(':anio', $anio_actual);
$result = $db->single();
$stats['eventos_anuales'] = $result ? $result->count : 0;

// Obtener datos de ejemplo de socio (primer socio activo)
<<<<<<< HEAD
$db->query("SELECT * FROM users WHERE rol = 'socio' AND activo = 1 ORDER BY fecha_registro ASC LIMIT 1");
$socio_ejemplo = $db->single();
=======
try {
    $db->query("SELECT * FROM users WHERE rol = 'socio' AND activo = 1 ORDER BY fecha_registro ASC LIMIT 1");
    $socio_ejemplo = $db->single();
    
    if ($socio_ejemplo === false) {
        error_log("No se encontraron socios activos en página de socios");
        $socio_ejemplo = null;
    }
} catch (Exception $e) {
    error_log("Error obteniendo socio de ejemplo: " . $e->getMessage());
    $socio_ejemplo = null;
}
>>>>>>> parece-que-es-buena

if ($socio_ejemplo) {
    $socio_data = [
        'nombre' => $socio_ejemplo->nombre . ' ' . $socio_ejemplo->apellidos,
        'numero_socio' => 'MS-' . date('Y') . '-' . str_pad($socio_ejemplo->id, 3, '0', STR_PAD_LEFT),
        'fecha_ingreso' => date('d/m/Y', strtotime($socio_ejemplo->fecha_registro)),
        'categoria' => 'Socio Activo',
        'cuota_al_dia' => true, // Por defecto, se puede implementar lógica de cuotas
        'ultima_cuota' => date('F Y', strtotime('-1 month')),
        'proximo_evento' => 'Reunión de Directiva - ' . date('d/m/Y', strtotime('+1 week'))
    ];
} else {
    // Datos por defecto si no hay socios
    $socio_data = [
        'nombre' => 'Juan Carlos Martínez',
        'numero_socio' => 'MS-2024-001',
        'fecha_ingreso' => '15/03/2020',
        'categoria' => 'Socio Activo',
        'cuota_al_dia' => true,
        'ultima_cuota' => 'Enero 2024',
        'proximo_evento' => 'Reunión de Directiva - 25/02/2024'
    ];
}

// Obtener eventos reales de la base de datos
try {
    $db->query("SELECT * FROM eventos WHERE fecha_inicio >= NOW() AND estado = 'activo' ORDER BY fecha_inicio ASC LIMIT 5");
    $eventos_db = $db->resultSet();
    
    if ($eventos_db === false) {
        error_log("Error obteniendo eventos en página de socios");
        $eventos_db = [];
    }
} catch (Exception $e) {
    error_log("Error en consulta de eventos: " . $e->getMessage());
    $eventos_db = [];
}

$eventos_socios = [];
if ($eventos_db) {
    foreach ($eventos_db as $evento) {
        $eventos_socios[] = [
            'titulo' => $evento->titulo,
            'fecha' => date('d/m/Y', strtotime($evento->fecha_inicio)),
            'hora' => date('H:i', strtotime($evento->fecha_inicio)),
            'lugar' => $evento->ubicacion ?: 'Sede Social',
            'tipo' => 'evento'
        ];
    }
} else {
    // Eventos por defecto si no hay eventos en la BD
    $eventos_socios = [
        [
            'titulo' => 'Reunión de Directiva',
            'fecha' => '25/02/2024',
            'hora' => '20:00',
            'lugar' => 'Sede Social',
            'tipo' => 'reunion'
        ],
        [
            'titulo' => 'Ensayos de Bandas',
            'fecha' => '28/02/2024',
            'hora' => '19:30',
            'lugar' => 'Sala de Ensayos',
            'tipo' => 'ensayo'
        ],
        [
            'titulo' => 'Cena de Hermandad',
            'fecha' => '15/03/2024',
            'hora' => '21:00',
            'lugar' => 'Restaurante El Castillo',
            'tipo' => 'social'
        ]
    ];
}

// Documentos por defecto (se pueden implementar en una tabla separada)
$documentos_socios = [
    [
        'nombre' => 'Estatutos de la Filá',
        'tipo' => 'PDF',
        'tamaño' => '2.5 MB',
        'fecha' => '15/01/2024'
    ],
    [
        'nombre' => 'Calendario 2024',
        'tipo' => 'PDF',
        'tamaño' => '1.8 MB',
        'fecha' => '10/01/2024'
    ],
    [
        'nombre' => 'Reglamento Interno',
        'tipo' => 'PDF',
        'tamaño' => '3.2 MB',
        'fecha' => '05/01/2024'
    ]
];
?>

<!-- Debug Info (temporal) -->
<?php if (isset($_GET['debug'])): ?>
<div style="background: #e7f3ff; border: 1px solid #b3d9ff; padding: 1rem; margin: 1rem; border-radius: 0.25rem;">
    <h4>🔍 Información de Depuración</h4>
    <p><strong>Socios activos:</strong> <?php echo $stats['socios_activos']; ?></p>
    <p><strong>Años de historia:</strong> <?php echo $stats['anios_historia']; ?></p>
    <p><strong>Eventos anuales:</strong> <?php echo $stats['eventos_anuales']; ?></p>
    <p><strong>Socio ejemplo encontrado:</strong> <?php echo $socio_ejemplo ? 'Sí' : 'No'; ?></p>
    <p><strong>Eventos encontrados:</strong> <?php echo count($eventos_db); ?></p>
    <p><strong>Timestamp:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
</div>
<?php endif; ?>

<!-- Hero Section -->
<section class="hero-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <h1 class="display-4 fw-bold text-gradient mb-4">
                    <i class="bi bi-shield-lock me-3"></i>Zona de Socios
                </h1>
                <p class="lead mb-5">Área exclusiva para miembros de la Filá Mariscales de Caballeros Templarios</p>
                <div class="socios-stats d-flex justify-content-center gap-4 mb-5">
                    <div class="stat-item">
                        <h3 class="text-gradient mb-0"><?php echo $stats['socios_activos']; ?>+</h3>
                        <small class="text-muted">Socios Activos</small>
                    </div>
                    <div class="stat-item">
                        <h3 class="text-gradient mb-0"><?php echo $stats['anios_historia']; ?></h3>
                        <small class="text-muted">Años de Historia</small>
                    </div>
                    <div class="stat-item">
                        <h3 class="text-gradient mb-0"><?php echo $stats['eventos_anuales']; ?>+</h3>
                        <small class="text-muted">Eventos Anuales</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Login Section -->
<section class="login-section py-5" id="loginSection">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="login-card">
                    <div class="login-header text-center mb-4">
                        <div class="login-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <h2 class="login-title">Acceso de Socios</h2>
                        <p class="login-subtitle">Ingresa tus credenciales para acceder</p>
                    </div>
                    
                    <form id="loginForm" class="login-form">
                        <div class="form-group mb-3">
                            <label for="username" class="form-label">
                                <i class="bi bi-person me-2"></i>Usuario o Email
                            </label>
                            <input type="text" class="form-control" id="username" required>
                        </div>
                        
                        <div class="form-group mb-4">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock me-2"></i>Contraseña
                            </label>
                                <input type="password" class="form-control" id="password" required>
                                <div class="text-end mt-2">
                                <a href="#" class="forgot-link" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-login w-100">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                        </button>
                    </form>
                    
                    <div class="login-footer text-center mt-4">
                        <p class="mb-0">
                            ¿No tienes cuenta? 
                            <a href="#" id="showRegister" class="register-link">Regístrate</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Dashboard Section (hidden by default) -->
<section class="dashboard-section py-5 d-none" id="dashboardSection">
    <div class="container">
        <!-- Welcome Banner -->
        <div class="welcome-banner mb-5">
            <div class="welcome-content">
                <div class="welcome-avatar">
                    <i class="bi bi-person-circle"></i>
                    </div>
                <div class="welcome-info">
                    <h2 class="welcome-title">Bienvenido/a, <span id="userName"><?php echo $socio_data['nombre']; ?></span></h2>
                    <p class="welcome-subtitle">
                        <i class="bi bi-card-text me-2"></i>
                        Nº Socio: <span id="memberNumber"><?php echo $socio_data['numero_socio']; ?></span>
                    </p>
                    <div class="welcome-status">
                        <span class="status-badge <?php echo $socio_data['cuota_al_dia'] ? 'status-active' : 'status-pending'; ?>">
                            <i class="bi bi-check-circle me-1"></i>
                            <?php echo $socio_data['cuota_al_dia'] ? 'Cuota al día' : 'Cuota pendiente'; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="row g-4 mb-5">
            <div class="col-md-6 col-lg-3">
                <div class="action-card">
                    <div class="action-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <h5 class="action-title">Eventos</h5>
                    <p class="action-description">Próximos eventos y reuniones</p>
                    <button class="btn btn-action" onclick="showEvents()">
                        Ver eventos
                    </button>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="action-card">
                    <div class="action-icon">
                        <i class="bi bi-credit-card"></i>
                    </div>
                    <h5 class="action-title">Cuotas</h5>
                    <p class="action-description">Estado de cuotas y pagos</p>
                    <button class="btn btn-action" onclick="showPayments()">
                        Ver estado
                    </button>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="action-card">
                    <div class="action-icon">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <h5 class="action-title">Documentos</h5>
                    <p class="action-description">Documentos y formularios</p>
                    <button class="btn btn-action" onclick="showDocuments()">
                        Ver documentos
                    </button>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="action-card">
                    <div class="action-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <h5 class="action-title">Directorio</h5>
                    <p class="action-description">Directorio de socios</p>
                    <button class="btn btn-action" onclick="showDirectory()">
                        Ver directorio
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Upcoming Events Section -->
        <div class="row g-4 mb-5">
            <div class="col-lg-8">
                <div class="events-card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="bi bi-calendar-event me-2"></i>Próximos Eventos
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="events-timeline">
                            <?php foreach ($eventos_socios as $evento): ?>
                            <div class="event-item">
                                <div class="event-date">
                                    <div class="event-day"><?php echo date('d', strtotime($evento['fecha'])); ?></div>
                                    <div class="event-month"><?php echo date('M', strtotime($evento['fecha'])); ?></div>
                                </div>
                                <div class="event-content">
                                    <h6 class="event-title"><?php echo $evento['titulo']; ?></h6>
                                    <p class="event-details">
                                        <i class="bi bi-clock me-1"></i><?php echo $evento['hora']; ?> |
                                        <i class="bi bi-geo-alt me-1"></i><?php echo $evento['lugar']; ?>
                                    </p>
                                    <div class="event-actions">
                                        <button class="btn btn-sm btn-outline-primary" onclick="confirmAttendance(<?php echo array_search($evento, $eventos_socios); ?>)">
                                            <i class="bi bi-check-circle me-1"></i>Confirmar Asistencia
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" onclick="addToCalendar(<?php echo array_search($evento, $eventos_socios); ?>)">
                                            <i class="bi bi-calendar-plus me-1"></i>Añadir al Calendario
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="calendar-card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="bi bi-calendar3 me-2"></i>Calendario
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="calendar-widget">
                            <div class="calendar-header">
                                <button class="btn btn-sm btn-outline-primary" onclick="previousMonth()">
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                                <h6 id="currentMonth"><?php echo date('F Y'); ?></h6>
                                <button class="btn btn-sm btn-outline-primary" onclick="nextMonth()">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            </div>
                            <div class="calendar-grid" id="calendarGrid">
                                <!-- Calendar will be generated by JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Activity and Notifications -->
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="activity-card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="bi bi-clock-history me-2"></i>Actividad Reciente
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="activity-list">
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="bi bi-calendar-event"></i>
                                </div>
                                <div class="activity-content">
                                    <h6>Próximo evento: <?php echo $socio_data['proximo_evento']; ?></h6>
                                    <p>No olvides asistir a la próxima reunión</p>
                                    <small class="text-muted">Hace 2 horas</small>
                                </div>
                            </div>
                            
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <div class="activity-content">
                                    <h6>Cuota de <?php echo $socio_data['ultima_cuota']; ?> pagada</h6>
                                    <p>Tu cuota mensual ha sido procesada correctamente</p>
                                    <small class="text-muted">Hace 3 días</small>
                                </div>
                            </div>
                            
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="bi bi-file-earmark"></i>
                                </div>
                                <div class="activity-content">
                                    <h6>Nuevo documento disponible</h6>
                                    <p>Calendario de eventos <?php echo date('Y'); ?> actualizado</p>
                                    <small class="text-muted">Hace 1 semana</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Notifications Section -->
                <div class="notifications-card mt-4">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="bi bi-bell me-2"></i>Notificaciones
                            <span class="badge bg-danger ms-2">3</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="notifications-list">
                            <div class="notification-item unread">
                                <div class="notification-icon">
                                    <i class="bi bi-exclamation-triangle"></i>
                                </div>
                                <div class="notification-content">
                                    <h6>Recordatorio: Ensayo de Bandas</h6>
                                    <p>Mañana a las 19:30 en la Sala de Ensayos</p>
                                    <small class="text-muted">Hace 1 hora</small>
                                </div>
                                <button class="btn btn-sm btn-outline-primary" onclick="markAsRead(1)">
                                    <i class="bi bi-check"></i>
                                </button>
                            </div>
                            
                            <div class="notification-item unread">
                                <div class="notification-icon">
                                    <i class="bi bi-gift"></i>
                                </div>
                                <div class="notification-content">
                                    <h6>¡Feliz Cumpleaños!</h6>
                                    <p>La directiva te desea un feliz cumpleaños</p>
                                    <small class="text-muted">Hace 3 horas</small>
                                </div>
                                <button class="btn btn-sm btn-outline-primary" onclick="markAsRead(2)">
                                    <i class="bi bi-check"></i>
                                </button>
                            </div>
                            
                            <div class="notification-item unread">
                                <div class="notification-icon">
                                    <i class="bi bi-calendar-plus"></i>
                                </div>
                                <div class="notification-content">
                                    <h6>Nuevo evento: Cena de Hermandad</h6>
                                    <p>Se ha añadido un nuevo evento al calendario</p>
                                    <small class="text-muted">Hace 1 día</small>
                                </div>
                                <button class="btn btn-sm btn-outline-primary" onclick="markAsRead(3)">
                                    <i class="bi bi-check"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="info-card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="bi bi-info-circle me-2"></i>Información del Socio
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="info-list">
                            <div class="info-item">
                                <span class="info-label">Categoría:</span>
                                <span class="info-value"><?php echo $socio_data['categoria']; ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Fecha de ingreso:</span>
                                <span class="info-value"><?php echo $socio_data['fecha_ingreso']; ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Última cuota:</span>
                                <span class="info-value"><?php echo $socio_data['ultima_cuota']; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Stats -->
                <div class="stats-card mt-4">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="bi bi-graph-up me-2"></i>Estadísticas Personales
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-number">15</div>
                                <div class="stat-label">Eventos Asistidos</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">8</div>
                                <div class="stat-label">Meses Activo</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">95%</div>
                                <div class="stat-label">Asistencia</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">12</div>
                                <div class="stat-label">Amigos</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Weather Widget -->
                <div class="weather-card mt-4">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="bi bi-cloud-sun me-2"></i>Clima para Eventos
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="weather-info">
                            <div class="weather-main">
                                <i class="bi bi-sun weather-icon"></i>
                                <div class="weather-details">
                                    <div class="weather-temp">22°C</div>
                                    <div class="weather-desc">Soleado</div>
                                </div>
                            </div>
                            <div class="weather-forecast">
                                <div class="forecast-item">
                                    <span>Mañana</span>
                                    <i class="bi bi-cloud"></i>
                                    <span>18°C</span>
                                </div>
                                <div class="forecast-item">
                                    <span>Noche</span>
                                    <i class="bi bi-moon"></i>
                                    <span>15°C</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Documents and Resources Section -->
        <div class="row g-4 mt-5">
            <div class="col-lg-8">
                <div class="documents-card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="bi bi-file-earmark-text me-2"></i>Documentos y Recursos
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="documents-grid">
                            <?php foreach ($documentos_socios as $documento): ?>
                            <div class="document-item">
                                <div class="document-icon">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </div>
                                <div class="document-info">
                                    <h6 class="document-title"><?php echo $documento['nombre']; ?></h6>
                                    <p class="document-meta">
                                        <span class="document-type"><?php echo $documento['tipo']; ?></span> |
                                        <span class="document-size"><?php echo $documento['tamaño']; ?></span> |
                                        <span class="document-date"><?php echo $documento['fecha']; ?></span>
                                    </p>
                                </div>
                                <div class="document-actions">
                                    <button class="btn btn-sm btn-primary" onclick="downloadDocument('<?php echo $documento['nombre']; ?>')">
                                        <i class="bi bi-download me-1"></i>Descargar
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="previewDocument('<?php echo $documento['nombre']; ?>')">
                                        <i class="bi bi-eye me-1"></i>Vista Previa
                                    </button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="resources-card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="bi bi-link-45deg me-2"></i>Enlaces Útiles
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="resources-list">
                            <a href="#" class="resource-link" onclick="openResource('directiva')">
                                <i class="bi bi-people-fill me-2"></i>
                                <span>Directiva Actual</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                            <a href="#" class="resource-link" onclick="openResource('reglamento')">
                                <i class="bi bi-journal-text me-2"></i>
                                <span>Reglamento Interno</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                            <a href="#" class="resource-link" onclick="openResource('contactos')">
                                <i class="bi bi-telephone-fill me-2"></i>
                                <span>Contactos de Emergencia</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                            <a href="#" class="resource-link" onclick="openResource('galeria')">
                                <i class="bi bi-images me-2"></i>
                                <span>Galería de Fotos</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                            <a href="#" class="resource-link" onclick="openResource('historia')">
                                <i class="bi bi-book me-2"></i>
                                <span>Historia de la Filá</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Messaging and Communication Section -->
        <div class="row g-4 mt-5">
            <div class="col-lg-8">
                <div class="messaging-card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="bi bi-chat-dots me-2"></i>Mensajería Interna
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="messaging-container">
                            <div class="chat-list">
                                <div class="chat-item active" onclick="selectChat('directiva')">
                                    <div class="chat-avatar">
                                        <i class="bi bi-shield-fill"></i>
                                    </div>
                                    <div class="chat-info">
                                        <h6>Directiva</h6>
                                        <p>Último mensaje: Reunión extraordinaria...</p>
                                        <small class="text-muted">Hace 2 horas</small>
                                    </div>
                                    <span class="unread-badge">3</span>
                                </div>
                                
                                <div class="chat-item" onclick="selectChat('banda')">
                                    <div class="chat-avatar">
                                        <i class="bi bi-music-note-beamed"></i>
                                    </div>
                                    <div class="chat-info">
                                        <h6>Grupo de Banda</h6>
                                        <p>Ensayo mañana a las 19:00</p>
                                        <small class="text-muted">Hace 1 día</small>
                                    </div>
                                </div>
                                
                                <div class="chat-item" onclick="selectChat('general')">
                                    <div class="chat-avatar">
                                        <i class="bi bi-people-fill"></i>
                                    </div>
                                    <div class="chat-info">
                                        <h6>Chat General</h6>
                                        <p>¡Hola a todos los socios!</p>
                                        <small class="text-muted">Hace 3 días</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="chat-messages" id="chatMessages">
                                <div class="message-list">
                                    <div class="message received">
                                        <div class="message-content">
                                            <p>Hola <?php echo $socio_data['nombre']; ?>, ¿cómo estás?</p>
                                            <small class="message-time">10:30</small>
                                        </div>
                                    </div>
                                    
                                    <div class="message sent">
                                        <div class="message-content">
                                            <p>¡Hola! Todo bien, gracias por preguntar</p>
                                            <small class="message-time">10:32</small>
                                        </div>
                                    </div>
                                    
                                    <div class="message received">
                                        <div class="message-content">
                                            <p>Perfecto, nos vemos en el próximo evento</p>
                                            <small class="message-time">10:35</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="message-input">
                                    <input type="text" class="form-control" id="messageInput" placeholder="Escribe tu mensaje...">
                                    <button class="btn btn-primary" onclick="sendMessage()">
                                        <i class="bi bi-send"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="achievements-card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="bi bi-trophy me-2"></i>Logros y Badges
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="achievements-grid">
                            <div class="achievement-item earned">
                                <div class="achievement-icon">
                                    <i class="bi bi-star-fill"></i>
                                </div>
                                <div class="achievement-info">
                                    <h6>Socio Activo</h6>
                                    <p>6 meses consecutivos</p>
                                </div>
                            </div>
                            
                            <div class="achievement-item earned">
                                <div class="achievement-icon">
                                    <i class="bi bi-calendar-check"></i>
                                </div>
                                <div class="achievement-info">
                                    <h6>Asistente Fiel</h6>
                                    <p>10 eventos asistidos</p>
                                </div>
                            </div>
                            
                            <div class="achievement-item">
                                <div class="achievement-icon locked">
                                    <i class="bi bi-lock"></i>
                                </div>
                                <div class="achievement-info">
                                    <h6>Veterano</h6>
                                    <p>1 año de membresía</p>
                                </div>
                            </div>
                            
                            <div class="achievement-item">
                                <div class="achievement-icon locked">
                                    <i class="bi bi-lock"></i>
                                </div>
                                <div class="achievement-info">
                                    <h6>Líder</h6>
                                    <p>Organizar 5 eventos</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="progress-section mt-3">
                            <h6>Progreso General</h6>
                            <div class="progress">
                                <div class="progress-bar" style="width: 65%">65%</div>
                            </div>
                            <small class="text-muted">13 de 20 logros completados</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Surveys and Feedback Section -->
        <div class="row g-4 mt-5">
            <div class="col-lg-8">
                <div class="surveys-card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="bi bi-clipboard-data me-2"></i>Encuestas y Feedback
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="surveys-list">
                            <div class="survey-item">
                                <div class="survey-header">
                                    <h6>Evaluación del Último Evento</h6>
                                    <span class="badge bg-warning">Pendiente</span>
                                </div>
                                <p class="survey-description">Ayúdanos a mejorar evaluando el evento de la semana pasada</p>
                                <div class="survey-meta">
                                    <small class="text-muted">Creada: 15/01/2024 | Duración: 5 min</small>
                                </div>
                                <button class="btn btn-primary btn-sm" onclick="startSurvey(1)">
                                    <i class="bi bi-play-circle me-1"></i>Comenzar Encuesta
                                </button>
                            </div>
                            
                            <div class="survey-item">
                                <div class="survey-header">
                                    <h6>Preferencias para Próximos Eventos</h6>
                                    <span class="badge bg-success">Completada</span>
                                </div>
                                <p class="survey-description">¿Qué tipo de eventos te gustaría ver este año?</p>
                                <div class="survey-meta">
                                    <small class="text-muted">Completada: 10/01/2024 | Duración: 3 min</small>
                                </div>
                                <button class="btn btn-outline-secondary btn-sm" onclick="viewSurveyResults(2)">
                                    <i class="bi bi-graph-up me-1"></i>Ver Resultados
                                </button>
                            </div>
                            
                            <div class="survey-item">
                                <div class="survey-header">
                                    <h6>Satisfacción General</h6>
                                    <span class="badge bg-info">Nueva</span>
                                </div>
                                <p class="survey-description">Cuéntanos cómo te sientes como socio de la filá</p>
                                <div class="survey-meta">
                                    <small class="text-muted">Creada: 20/01/2024 | Duración: 7 min</small>
                                </div>
                                <button class="btn btn-primary btn-sm" onclick="startSurvey(3)">
                                    <i class="bi bi-play-circle me-1"></i>Comenzar Encuesta
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="quick-actions-card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="bi bi-lightning me-2"></i>Acciones Rápidas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="quick-actions-list">
                            <button class="quick-action-btn" onclick="quickAction('report')">
                                <i class="bi bi-flag"></i>
                                <span>Reportar Problema</span>
                            </button>
                            
                            <button class="quick-action-btn" onclick="quickAction('suggestion')">
                                <i class="bi bi-lightbulb"></i>
                                <span>Sugerencia</span>
                            </button>
                            
                            <button class="quick-action-btn" onclick="quickAction('emergency')">
                                <i class="bi bi-exclamation-triangle"></i>
                                <span>Contacto Emergencia</span>
                            </button>
                            
                            <button class="quick-action-btn" onclick="quickAction('volunteer')">
                                <i class="bi bi-hand-index-thumb"></i>
                                <span>Voluntariado</span>
                            </button>
                            
                            <button class="quick-action-btn" onclick="quickAction('donation')">
                                <i class="bi bi-heart"></i>
                                <span>Donación</span>
                            </button>
                            
                            <button class="quick-action-btn" onclick="quickAction('training')">
                                <i class="bi bi-mortarboard"></i>
                                <span>Solicitar Formación</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Gamification and News Section -->
        <div class="row g-4 mt-5">
            <div class="col-lg-8">
                <div class="news-card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="bi bi-newspaper me-2"></i>Noticias para Socios
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="news-list">
                            <div class="news-item featured">
                                <div class="news-image">
                                    <i class="bi bi-star-fill"></i>
                                </div>
                                <div class="news-content">
                                    <div class="news-meta">
                                        <span class="news-category">Destacado</span>
                                        <span class="news-date">Hoy</span>
                                    </div>
                                    <h6>Nueva Sede Social Inaugurada</h6>
                                    <p>Se ha inaugurado oficialmente nuestra nueva sede social con todas las comodidades para los socios.</p>
                                    <button class="btn btn-sm btn-outline-primary" onclick="readNews(1)">
                                        <i class="bi bi-arrow-right me-1"></i>Leer más
                                    </button>
                                </div>
                            </div>
                            
                            <div class="news-item">
                                <div class="news-image">
                                    <i class="bi bi-calendar-event"></i>
                                </div>
                                <div class="news-content">
                                    <div class="news-meta">
                                        <span class="news-category">Eventos</span>
                                        <span class="news-date">Ayer</span>
                                    </div>
                                    <h6>Próximo Ensayo de Bandas</h6>
                                    <p>El próximo sábado tendremos un ensayo especial con invitados de otras filás.</p>
                                    <button class="btn btn-sm btn-outline-primary" onclick="readNews(2)">
                                        <i class="bi bi-arrow-right me-1"></i>Leer más
                                    </button>
                                </div>
                            </div>
                            
                            <div class="news-item">
                                <div class="news-image">
                                    <i class="bi bi-trophy"></i>
                                </div>
                                <div class="news-content">
                                    <div class="news-meta">
                                        <span class="news-category">Logros</span>
                                        <span class="news-date">Hace 2 días</span>
                                    </div>
                                    <h6>Premio a la Mejor Filá 2024</h6>
                                    <p>Nuestra filá ha sido galardonada con el premio a la mejor organización del año.</p>
                                    <button class="btn btn-sm btn-outline-primary" onclick="readNews(3)">
                                        <i class="bi bi-arrow-right me-1"></i>Leer más
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="gamification-card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="bi bi-controller me-2"></i>Sistema de Puntos
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="points-summary">
                            <div class="points-display">
                                <div class="points-number">1,250</div>
                                <div class="points-label">Puntos Totales</div>
                            </div>
                            <div class="level-info">
                                <div class="level-badge">Nivel 8</div>
                                <div class="level-progress">
                                    <div class="progress">
                                        <div class="progress-bar" style="width: 75%"></div>
                                    </div>
                                    <small>750/1000 para el siguiente nivel</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="recent-activities mt-4">
                            <h6>Actividades Recientes</h6>
                            <div class="activity-list">
                                <div class="activity-points">
                                    <i class="bi bi-calendar-check text-success"></i>
                                    <span>Asistir a evento</span>
                                    <span class="points-earned">+50 pts</span>
                                </div>
                                <div class="activity-points">
                                    <i class="bi bi-chat-dots text-primary"></i>
                                    <span>Participar en chat</span>
                                    <span class="points-earned">+10 pts</span>
                                </div>
                                <div class="activity-points">
                                    <i class="bi bi-clipboard-check text-warning"></i>
                                    <span>Completar encuesta</span>
                                    <span class="points-earned">+25 pts</span>
                                </div>
                                <div class="activity-points">
                                    <i class="bi bi-people text-info"></i>
                                    <span>Invitar amigo</span>
                                    <span class="points-earned">+100 pts</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="rewards-section mt-4">
                            <h6>Recompensas Disponibles</h6>
                            <div class="rewards-list">
                                <div class="reward-item">
                                    <div class="reward-icon">
                                        <i class="bi bi-gift"></i>
                                    </div>
                                    <div class="reward-info">
                                        <h6>Descuento 20% Tienda</h6>
                                        <p>500 puntos</p>
                                    </div>
                                    <button class="btn btn-sm btn-primary" onclick="claimReward(1)">
                                        Canjear
                                    </button>
                                </div>
                                
                                <div class="reward-item">
                                    <div class="reward-icon">
                                        <i class="bi bi-ticket-perforated"></i>
                                    </div>
                                    <div class="reward-info">
                                        <h6>Entrada Gratuita</h6>
                                        <p>1000 puntos</p>
                                    </div>
                                    <button class="btn btn-sm btn-outline-secondary" disabled>
                                        Insuficiente
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Forgot Password Modal -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-key me-2"></i>Recuperar Contraseña
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="forgotPasswordForm">
                    <div class="mb-3">
                        <label for="recoveryEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="recoveryEmail" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-envelope me-2"></i>Enviar enlace
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* Socios Styles */
.hero-section {
    background: linear-gradient(135deg, rgba(220, 20, 60, 0.05) 0%, rgba(255, 255, 255, 0.7) 50%, rgba(220, 20, 60, 0.05) 100%);
    backdrop-filter: blur(5px);
}

.socios-stats .stat-item {
    text-align: center;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.7);
    border-radius: 10px;
    border: 2px solid var(--primary);
    min-width: 120px;
    backdrop-filter: blur(10px);
}

.login-section {
    background: rgba(248, 249, 250, 0.8);
    backdrop-filter: blur(10px);
}

.login-card {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 20px;
    padding: 2.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 2px solid var(--primary);
    backdrop-filter: blur(10px);
}

.login-header {
    margin-bottom: 2rem;
}

.login-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

.login-icon i {
    font-size: 2rem;
    color: white;
}

.login-title {
    font-family: 'Cinzel', serif;
    font-size: 1.8rem;
    color: var(--primary);
    margin-bottom: 0.5rem;
}

.login-subtitle {
    color: #6c757d;
    font-size: 1rem;
}

.form-control {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 0.2rem rgba(220, 20, 60, 0.25);
}

.btn-login {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    color: #FFFFFF;
    border: none;
    border-radius: 10px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(220, 20, 60, 0.3);
}

.forgot-link {
    color: var(--primary);
    text-decoration: none;
    font-size: 0.9rem;
}

.forgot-link:hover {
    text-decoration: underline;
}

.register-link {
    color: var(--primary);
    font-weight: 600;
    text-decoration: none;
}

.register-link:hover {
    text-decoration: underline;
}

/* Dashboard Styles */
.dashboard-section {
    background: rgba(248, 249, 250, 0.8);
    backdrop-filter: blur(10px);
}

.welcome-banner {
    background: linear-gradient(135deg, rgba(220, 20, 60, 0.9) 0%, rgba(139, 0, 0, 0.9) 100%);
    border-radius: 20px;
    padding: 2rem;
    color: white;
    backdrop-filter: blur(10px);
}

.welcome-content {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.welcome-avatar {
    font-size: 3rem;
    color: rgba(255, 255, 255, 0.8);
}

.welcome-title {
    font-family: 'Cinzel', serif;
    font-size: 1.8rem;
    margin-bottom: 0.5rem;
}

.welcome-subtitle {
    font-size: 1.1rem;
    margin-bottom: 1rem;
    opacity: 0.9;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
}

.status-active {
    background: rgba(40, 167, 69, 0.2);
    color: #28a745;
    border: 1px solid #28a745;
}

.status-pending {
    background: rgba(255, 193, 7, 0.2);
    color: #ffc107;
    border: 1px solid #ffc107;
}

.action-card {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 15px;
    padding: 2rem;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    border: 2px solid transparent;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    height: 100%;
}

.action-card:hover {
    transform: translateY(-5px);
    border-color: var(--primary);
    box-shadow: 0 10px 30px rgba(220, 20, 60, 0.2);
}

.action-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

.action-icon i {
    font-size: 1.5rem;
    color: white;
}

.action-title {
    font-family: 'Cinzel', serif;
    font-size: 1.2rem;
    color: var(--primary);
    margin-bottom: 0.5rem;
}

.action-description {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

.btn-action {
    background: var(--primary);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.btn-action:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
}

.activity-card, .info-card {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    border: 2px solid var(--primary);
    backdrop-filter: blur(10px);
}

.card-header {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    color: white;
    padding: 1rem 1.5rem;
    border-radius: 13px 13px 0 0;
    border-bottom: none;
}

.card-title {
    font-family: 'Cinzel', serif;
    font-size: 1.2rem;
    margin: 0;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid #e9ecef;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    background: var(--primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.activity-icon i {
    color: white;
    font-size: 1rem;
}

.activity-content h6 {
    font-family: 'Cinzel', serif;
    color: var(--primary);
    margin-bottom: 0.25rem;
}

.info-list {
    padding: 0;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e9ecef;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: var(--primary);
}

.info-value {
    color: #6c757d;
}

/* Notifications Styles */
.notifications-card {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    border: 2px solid var(--primary);
    backdrop-filter: blur(10px);
}

.notifications-list {
    max-height: 400px;
    overflow-y: auto;
}

.notification-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    border-bottom: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.notification-item:last-child {
    border-bottom: none;
}

.notification-item.unread {
    background: rgba(220, 20, 60, 0.05);
    border-left: 4px solid var(--primary);
}

.notification-item:hover {
    background: rgba(220, 20, 60, 0.1);
}

.notification-icon {
    width: 40px;
    height: 40px;
    background: var(--primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.notification-icon i {
    color: white;
    font-size: 1rem;
}

.notification-content {
    flex: 1;
}

.notification-content h6 {
    font-family: 'Cinzel', serif;
    color: var(--primary);
    margin-bottom: 0.25rem;
}

/* Stats Styles */
.stats-card {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    border: 2px solid var(--primary);
    backdrop-filter: blur(10px);
}

.stats-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.stat-item {
    text-align: center;
    padding: 1rem;
    background: rgba(220, 20, 60, 0.05);
    border-radius: 10px;
    border: 1px solid rgba(220, 20, 60, 0.2);
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.8rem;
    color: #6c757d;
    font-weight: 600;
}

/* Weather Styles */
.weather-card {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    border: 2px solid var(--primary);
    backdrop-filter: blur(10px);
}

.weather-main {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.weather-icon {
    font-size: 2.5rem;
    color: #ffc107;
}

.weather-temp {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary);
}

.weather-desc {
    color: #6c757d;
    font-size: 0.9rem;
}

.weather-forecast {
    display: flex;
    justify-content: space-between;
}

.forecast-item {
    text-align: center;
    flex: 1;
}

.forecast-item span {
    display: block;
    font-size: 0.8rem;
    color: #6c757d;
}

.forecast-item i {
    font-size: 1.2rem;
    color: var(--primary);
    margin: 0.5rem 0;
}

/* Events Styles */
.events-card {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    border: 2px solid var(--primary);
    backdrop-filter: blur(10px);
}

.events-timeline {
    max-height: 500px;
    overflow-y: auto;
}

.event-item {
    display: flex;
    gap: 1rem;
    padding: 1.5rem;
    border-bottom: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.event-item:last-child {
    border-bottom: none;
}

.event-item:hover {
    background: rgba(220, 20, 60, 0.05);
}

.event-date {
    text-align: center;
    min-width: 80px;
    padding: 1rem;
    background: var(--primary);
    border-radius: 10px;
    color: white;
    flex-shrink: 0;
}

.event-day {
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1;
}

.event-month {
    font-size: 0.8rem;
    text-transform: uppercase;
    opacity: 0.9;
}

.event-content {
    flex: 1;
}

.event-title {
    font-family: 'Cinzel', serif;
    color: var(--primary);
    margin-bottom: 0.5rem;
}

.event-details {
    color: #6c757d;
    margin-bottom: 1rem;
}

.event-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

/* Calendar Styles */
.calendar-card {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    border: 2px solid var(--primary);
    backdrop-filter: blur(10px);
}

.calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.calendar-header h6 {
    margin: 0;
    font-weight: 600;
    color: var(--primary);
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 2px;
}

.calendar-day {
    aspect-ratio: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.calendar-day:hover {
    background: rgba(220, 20, 60, 0.1);
}

.calendar-day.today {
    background: var(--primary);
    color: white;
    font-weight: 700;
}

.calendar-day.event {
    background: rgba(220, 20, 60, 0.2);
    color: var(--primary);
    font-weight: 600;
}

.calendar-day.other-month {
    color: #ccc;
}

.calendar-day-header {
    font-weight: 600;
    color: var(--primary);
    background: rgba(220, 20, 60, 0.1);
}

/* Documents Styles */
.documents-card {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    border: 2px solid var(--primary);
    backdrop-filter: blur(10px);
}

.documents-grid {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.document-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: rgba(248, 249, 250, 0.8);
    border-radius: 10px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.document-item:hover {
    background: rgba(220, 20, 60, 0.05);
    border-color: var(--primary);
    transform: translateY(-2px);
}

.document-icon {
    width: 50px;
    height: 50px;
    background: var(--primary);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.document-icon i {
    color: white;
    font-size: 1.5rem;
}

.document-info {
    flex: 1;
}

.document-title {
    font-family: 'Cinzel', serif;
    color: var(--primary);
    margin-bottom: 0.25rem;
}

.document-meta {
    font-size: 0.8rem;
    color: #6c757d;
    margin: 0;
}

.document-actions {
    display: flex;
    gap: 0.5rem;
    flex-shrink: 0;
}

/* Resources Styles */
.resources-card {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    border: 2px solid var(--primary);
    backdrop-filter: blur(10px);
}

.resources-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.resource-link {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem;
    background: rgba(248, 249, 250, 0.8);
    border-radius: 10px;
    text-decoration: none;
    color: var(--primary);
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.resource-link:hover {
    background: rgba(220, 20, 60, 0.1);
    border-color: var(--primary);
    transform: translateX(5px);
    color: var(--primary);
    text-decoration: none;
}

.resource-link span {
    font-weight: 600;
}

.resource-link i:last-child {
    opacity: 0.7;
    transition: all 0.3s ease;
}

.resource-link:hover i:last-child {
    opacity: 1;
    transform: translateX(3px);
}

/* Messaging Styles */
.messaging-card {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    border: 2px solid var(--primary);
    backdrop-filter: blur(10px);
}

.messaging-container {
    display: flex;
    height: 400px;
    gap: 1rem;
}

.chat-list {
    width: 300px;
    border-right: 1px solid #e9ecef;
    overflow-y: auto;
}

.chat-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    border-bottom: 1px solid #f8f9fa;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.chat-item:hover {
    background: rgba(220, 20, 60, 0.05);
}

.chat-item.active {
    background: rgba(220, 20, 60, 0.1);
    border-left: 3px solid var(--primary);
}

.chat-avatar {
    width: 40px;
    height: 40px;
    background: var(--primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.chat-avatar i {
    color: white;
    font-size: 1rem;
}

.chat-info {
    flex: 1;
    min-width: 0;
}

.chat-info h6 {
    margin: 0;
    font-size: 0.9rem;
    color: var(--primary);
}

.chat-info p {
    margin: 0;
    font-size: 0.8rem;
    color: #6c757d;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.unread-badge {
    background: var(--primary);
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    font-weight: 600;
}

.chat-messages {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.message-list {
    flex: 1;
    overflow-y: auto;
    padding: 1rem;
}

.message {
    margin-bottom: 1rem;
    display: flex;
}

.message.received {
    justify-content: flex-start;
}

.message.sent {
    justify-content: flex-end;
}

.message-content {
    max-width: 70%;
    padding: 0.75rem 1rem;
    border-radius: 15px;
    position: relative;
}

.message.received .message-content {
    background: #f8f9fa;
    color: #495057;
}

.message.sent .message-content {
    background: var(--primary);
    color: white;
}

.message-time {
    font-size: 0.7rem;
    opacity: 0.7;
    margin-top: 0.25rem;
    display: block;
}

.message-input {
    display: flex;
    gap: 0.5rem;
    padding: 1rem;
    border-top: 1px solid #e9ecef;
}

.message-input input {
    flex: 1;
    border-radius: 20px;
    border: 1px solid #e9ecef;
    padding: 0.5rem 1rem;
}

.message-input button {
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Achievements Styles */
.achievements-card {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    border: 2px solid var(--primary);
    backdrop-filter: blur(10px);
}

.achievements-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.achievement-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: rgba(248, 249, 250, 0.8);
    border-radius: 10px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.achievement-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.achievement-item.earned {
    border-color: #28a745;
    background: rgba(40, 167, 69, 0.05);
}

.achievement-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.achievement-item.earned .achievement-icon {
    background: #28a745;
    color: white;
}

.achievement-icon.locked {
    background: #6c757d;
    color: white;
}

.achievement-info h6 {
    margin: 0;
    font-size: 0.9rem;
    color: var(--primary);
}

.achievement-info p {
    margin: 0;
    font-size: 0.8rem;
    color: #6c757d;
}

.progress-section {
    border-top: 1px solid #e9ecef;
    padding-top: 1rem;
}

.progress {
    height: 8px;
    border-radius: 4px;
    background: #e9ecef;
    overflow: hidden;
    margin: 0.5rem 0;
}

.progress-bar {
    background: linear-gradient(90deg, var(--primary) 0%, var(--primary-dark) 100%);
    height: 100%;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.7rem;
    font-weight: 600;
}

/* Surveys Styles */
.surveys-card {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    border: 2px solid var(--primary);
    backdrop-filter: blur(10px);
}

.surveys-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.survey-item {
    padding: 1.5rem;
    background: rgba(248, 249, 250, 0.8);
    border-radius: 10px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.survey-item:hover {
    background: rgba(220, 20, 60, 0.05);
    border-color: var(--primary);
}

.survey-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.survey-header h6 {
    margin: 0;
    color: var(--primary);
    font-weight: 600;
}

.survey-description {
    color: #6c757d;
    margin-bottom: 1rem;
    font-size: 0.9rem;
}

.survey-meta {
    margin-bottom: 1rem;
}

/* Quick Actions Styles */
.quick-actions-card {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    border: 2px solid var(--primary);
    backdrop-filter: blur(10px);
}

.quick-actions-list {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
}

.quick-action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    background: rgba(248, 249, 250, 0.8);
    border: 1px solid #e9ecef;
    border-radius: 10px;
    transition: all 0.3s ease;
    cursor: pointer;
    text-decoration: none;
    color: var(--primary);
}

.quick-action-btn:hover {
    background: rgba(220, 20, 60, 0.1);
    border-color: var(--primary);
    transform: translateY(-2px);
    color: var(--primary);
    text-decoration: none;
}

.quick-action-btn i {
    font-size: 1.5rem;
}

.quick-action-btn span {
    font-size: 0.8rem;
    font-weight: 600;
    text-align: center;
}

/* News Styles */
.news-card {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    border: 2px solid var(--primary);
    backdrop-filter: blur(10px);
}

.news-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.news-item {
    display: flex;
    gap: 1rem;
    padding: 1.5rem;
    background: rgba(248, 249, 250, 0.8);
    border-radius: 10px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.news-item:hover {
    background: rgba(220, 20, 60, 0.05);
    border-color: var(--primary);
    transform: translateY(-2px);
}

.news-item.featured {
    border-color: #ffc107;
    background: rgba(255, 193, 7, 0.05);
}

.news-image {
    width: 60px;
    height: 60px;
    background: var(--primary);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.news-item.featured .news-image {
    background: #ffc107;
}

.news-image i {
    color: white;
    font-size: 1.5rem;
}

.news-content {
    flex: 1;
}

.news-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.news-category {
    background: var(--primary);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 15px;
    font-size: 0.7rem;
    font-weight: 600;
}

.news-date {
    font-size: 0.8rem;
    color: #6c757d;
}

.news-content h6 {
    color: var(--primary);
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.news-content p {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

/* Gamification Styles */
.gamification-card {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    border: 2px solid var(--primary);
    backdrop-filter: blur(10px);
}

.points-summary {
    text-align: center;
    padding: 1rem;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    border-radius: 10px;
    color: white;
    margin-bottom: 1.5rem;
}

.points-number {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.points-label {
    font-size: 0.9rem;
    opacity: 0.9;
}

.level-info {
    margin-top: 1rem;
}

.level-badge {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    margin-bottom: 0.5rem;
    display: inline-block;
}

.level-progress .progress {
    height: 6px;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 3px;
    margin-bottom: 0.5rem;
}

.level-progress .progress-bar {
    background: white;
    border-radius: 3px;
}

.level-progress small {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.7rem;
}

.activity-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.activity-points {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: rgba(248, 249, 250, 0.8);
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.activity-points i {
    font-size: 1rem;
    width: 20px;
    text-align: center;
}

.activity-points span {
    flex: 1;
    font-size: 0.8rem;
    color: #6c757d;
}

.points-earned {
    font-weight: 600;
    color: #28a745 !important;
    font-size: 0.7rem !important;
}

.rewards-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.reward-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: rgba(248, 249, 250, 0.8);
    border-radius: 10px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.reward-item:hover {
    background: rgba(220, 20, 60, 0.05);
    border-color: var(--primary);
}

.reward-icon {
    width: 40px;
    height: 40px;
    background: var(--primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.reward-icon i {
    color: white;
    font-size: 1rem;
}

.reward-info {
    flex: 1;
}

.reward-info h6 {
    margin: 0;
    font-size: 0.9rem;
    color: var(--primary);
}

.reward-info p {
    margin: 0;
    font-size: 0.8rem;
    color: #6c757d;
}

/* Responsive */
@media (max-width: 768px) {
    .socios-stats {
        flex-direction: column;
        gap: 1rem;
    }
    
    .welcome-content {
        flex-direction: column;
        text-align: center;
    }
    
    .login-card {
        padding: 2rem 1.5rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .weather-forecast {
        flex-direction: column;
        gap: 0.5rem;
    }
}
</style>

<script>
// Toggle between login and dashboard (demo purposes)
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    document.getElementById('loginSection').classList.add('d-none');
    document.getElementById('dashboardSection').classList.remove('d-none');
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
});

// Show register form
document.getElementById('showRegister')?.addEventListener('click', function(e) {
    e.preventDefault();
    alert('Funcionalidad de registro pendiente de implementar. Contacta con la directiva para registrarte como socio.');
});

// Handle forgot password form
document.getElementById('forgotPasswordForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Se ha enviado un enlace de recuperación a tu correo electrónico.');
    const modal = bootstrap.Modal.getInstance(document.getElementById('forgotPasswordModal'));
    modal.hide();
});

// Dashboard functions
function showEvents() {
    alert('Funcionalidad de eventos en desarrollo. Próximamente podrás ver todos los eventos de la filá.');
}

function showPayments() {
    alert('Funcionalidad de pagos en desarrollo. Próximamente podrás gestionar tus cuotas online.');
}

function showDocuments() {
    alert('Funcionalidad de documentos en desarrollo. Próximamente podrás descargar todos los documentos de la filá.');
}

function showDirectory() {
    alert('Funcionalidad de directorio en desarrollo. Próximamente podrás ver el directorio completo de socios.');
}

// Notification functions
function markAsRead(notificationId) {
    const notification = document.querySelector(`[onclick="markAsRead(${notificationId})"]`).closest('.notification-item');
    notification.classList.remove('unread');
    notification.style.opacity = '0.7';
    
    // Update notification count
    const badge = document.querySelector('.badge');
    const currentCount = parseInt(badge.textContent);
    if (currentCount > 0) {
        badge.textContent = currentCount - 1;
        if (currentCount - 1 === 0) {
            badge.style.display = 'none';
        }
    }
    
    showToast('Notificación marcada como leída', 'success');
}

// Weather functions
function updateWeather() {
    // Simulate weather API call
    const weatherData = {
        current: { temp: 22, condition: 'Soleado', icon: 'bi-sun' },
        forecast: [
            { time: 'Mañana', temp: 18, icon: 'bi-cloud' },
            { time: 'Noche', temp: 15, icon: 'bi-moon' }
        ]
    };
    
    // Update weather display
    document.querySelector('.weather-temp').textContent = weatherData.current.temp + '°C';
    document.querySelector('.weather-desc').textContent = weatherData.current.condition;
    document.querySelector('.weather-icon').className = 'bi ' + weatherData.current.icon + ' weather-icon';
}

// Stats functions
function updateStats() {
    // Simulate stats update
    const stats = [
        { label: 'Eventos Asistidos', value: Math.floor(Math.random() * 20) + 10 },
        { label: 'Meses Activo', value: Math.floor(Math.random() * 12) + 1 },
        { label: 'Asistencia', value: Math.floor(Math.random() * 20) + 80 + '%' },
        { label: 'Amigos', value: Math.floor(Math.random() * 20) + 5 }
    ];
    
    const statNumbers = document.querySelectorAll('.stat-number');
    statNumbers.forEach((stat, index) => {
        stat.textContent = stats[index].value;
    });
}

// Event functions
function confirmAttendance(eventIndex) {
    const eventItems = document.querySelectorAll('.event-item');
    const eventItem = eventItems[eventIndex];
    const button = eventItem.querySelector('.btn-outline-primary');
    
    if (button.innerHTML.includes('Confirmar')) {
        button.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i>Asistencia Confirmada';
        button.className = 'btn btn-sm btn-success';
        showToast('Asistencia confirmada para el evento', 'success');
    } else {
        button.innerHTML = '<i class="bi bi-check-circle me-1"></i>Confirmar Asistencia';
        button.className = 'btn btn-sm btn-outline-primary';
        showToast('Asistencia cancelada', 'info');
    }
}

function addToCalendar(eventIndex) {
    const eventItems = document.querySelectorAll('.event-item');
    const eventItem = eventItems[eventIndex];
    const title = eventItem.querySelector('.event-title').textContent;
    
    // Create calendar event data
    const eventData = {
        title: title,
        start: new Date(),
        end: new Date(new Date().getTime() + 2 * 60 * 60 * 1000), // 2 hours later
        location: eventItem.querySelector('.event-details').textContent.split('|')[1].trim()
    };
    
    // Try to add to calendar (this would integrate with real calendar APIs)
    showToast('Evento añadido al calendario', 'success');
}

// Calendar functions
let currentDate = new Date();

function generateCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const startDate = new Date(firstDay);
    startDate.setDate(startDate.getDate() - firstDay.getDay());
    
    const calendarGrid = document.getElementById('calendarGrid');
    const currentMonthElement = document.getElementById('currentMonth');
    
    // Update month display
    const monthNames = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                       'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    currentMonthElement.textContent = monthNames[month] + ' ' + year;
    
    // Generate calendar HTML
    let calendarHTML = '';
    
    // Day headers
    const dayNames = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
    dayNames.forEach(day => {
        calendarHTML += `<div class="calendar-day calendar-day-header">${day}</div>`;
    });
    
    // Calendar days
    const today = new Date();
    for (let i = 0; i < 42; i++) {
        const currentDay = new Date(startDate);
        currentDay.setDate(startDate.getDate() + i);
        
        let dayClass = 'calendar-day';
        if (currentDay.getMonth() !== month) {
            dayClass += ' other-month';
        }
        if (currentDay.toDateString() === today.toDateString()) {
            dayClass += ' today';
        }
        if (currentDay.getDate() === 15 || currentDay.getDate() === 25) {
            dayClass += ' event';
        }
        
        calendarHTML += `<div class="${dayClass}" onclick="selectDate(${currentDay.getTime()})">${currentDay.getDate()}</div>`;
    }
    
    calendarGrid.innerHTML = calendarHTML;
}

function previousMonth() {
    currentDate.setMonth(currentDate.getMonth() - 1);
    generateCalendar();
}

function nextMonth() {
    currentDate.setMonth(currentDate.getMonth() + 1);
    generateCalendar();
}

function selectDate(timestamp) {
    const selectedDate = new Date(timestamp);
    showToast(`Fecha seleccionada: ${selectedDate.toLocaleDateString('es-ES')}`, 'info');
}

// Initialize calendar on page load
document.addEventListener('DOMContentLoaded', function() {
    generateCalendar();
});

// Document functions
function downloadDocument(documentName) {
    // Simulate document download
    showToast(`Descargando ${documentName}...`, 'info');
    
    // In a real implementation, this would trigger a file download
    setTimeout(() => {
        showToast(`${documentName} descargado correctamente`, 'success');
    }, 2000);
}

function previewDocument(documentName) {
    // Simulate document preview
    showToast(`Abriendo vista previa de ${documentName}`, 'info');
    
    // In a real implementation, this would open a modal with document preview
    setTimeout(() => {
        showToast(`Vista previa de ${documentName} disponible`, 'success');
    }, 1500);
}

// Resource functions
function openResource(resourceType) {
    const resourceNames = {
        'directiva': 'Directiva Actual',
        'reglamento': 'Reglamento Interno',
        'contactos': 'Contactos de Emergencia',
        'galeria': 'Galería de Fotos',
        'historia': 'Historia de la Filá'
    };
    
    showToast(`Abriendo ${resourceNames[resourceType]}...`, 'info');
    
    // In a real implementation, this would navigate to the specific resource
    setTimeout(() => {
        showToast(`${resourceNames[resourceType]} cargado correctamente`, 'success');
    }, 1500);
}

// Messaging functions
function selectChat(chatType) {
    // Remove active class from all chat items
    document.querySelectorAll('.chat-item').forEach(item => {
        item.classList.remove('active');
    });
    
    // Add active class to selected chat
    event.currentTarget.classList.add('active');
    
    // Update chat messages based on selected chat
    const chatMessages = document.getElementById('chatMessages');
    const messageList = chatMessages.querySelector('.message-list');
    
    const chatMessagesData = {
        'directiva': [
            { type: 'received', text: 'Hola, ¿cómo estás?', time: '10:30' },
            { type: 'sent', text: '¡Hola! Todo bien, gracias', time: '10:32' },
            { type: 'received', text: 'Perfecto, nos vemos en la reunión', time: '10:35' }
        ],
        'banda': [
            { type: 'received', text: 'Ensayo mañana a las 19:00', time: '09:15' },
            { type: 'sent', text: 'Perfecto, estaré allí', time: '09:20' },
            { type: 'received', text: 'No olvides traer tu instrumento', time: '09:22' }
        ],
        'general': [
            { type: 'received', text: '¡Hola a todos los socios!', time: '08:00' },
            { type: 'sent', text: '¡Hola! ¿Cómo va todo?', time: '08:05' },
            { type: 'received', text: 'Todo genial, gracias por preguntar', time: '08:10' }
        ]
    };
    
    const messages = chatMessagesData[chatType] || chatMessagesData['directiva'];
    
    messageList.innerHTML = messages.map(msg => `
        <div class="message ${msg.type}">
            <div class="message-content">
                <p>${msg.text}</p>
                <small class="message-time">${msg.time}</small>
            </div>
        </div>
    `).join('');
    
    // Clear unread badge if exists
    const unreadBadge = event.currentTarget.querySelector('.unread-badge');
    if (unreadBadge) {
        unreadBadge.style.display = 'none';
    }
    
    showToast(`Chat de ${chatType} seleccionado`, 'info');
}

function sendMessage() {
    const messageInput = document.getElementById('messageInput');
    const message = messageInput.value.trim();
    
    if (message) {
        const messageList = document.querySelector('.message-list');
        const currentTime = new Date().toLocaleTimeString('es-ES', { 
            hour: '2-digit', 
            minute: '2-digit' 
        });
        
        const newMessage = `
            <div class="message sent">
                <div class="message-content">
                    <p>${message}</p>
                    <small class="message-time">${currentTime}</small>
                </div>
            </div>
        `;
        
        messageList.insertAdjacentHTML('beforeend', newMessage);
        messageInput.value = '';
        
        // Scroll to bottom
        messageList.scrollTop = messageList.scrollHeight;
        
        showToast('Mensaje enviado', 'success');
    } else {
        showToast('Por favor escribe un mensaje', 'warning');
    }
}

// Survey functions
function startSurvey(surveyId) {
    const surveyNames = {
        1: 'Evaluación del Último Evento',
        2: 'Preferencias para Próximos Eventos',
        3: 'Satisfacción General'
    };
    
    showToast(`Iniciando encuesta: ${surveyNames[surveyId]}`, 'info');
    
    // Simulate survey loading
    setTimeout(() => {
        showToast(`Encuesta ${surveyNames[surveyId]} cargada`, 'success');
        // In a real implementation, this would open a modal with the survey
    }, 2000);
}

function viewSurveyResults(surveyId) {
    showToast('Cargando resultados de la encuesta...', 'info');
    
    setTimeout(() => {
        showToast('Resultados de la encuesta mostrados', 'success');
        // In a real implementation, this would show survey results
    }, 1500);
}

// Quick actions functions
function quickAction(actionType) {
    const actionNames = {
        'report': 'Reportar Problema',
        'suggestion': 'Sugerencia',
        'emergency': 'Contacto Emergencia',
        'volunteer': 'Voluntariado',
        'donation': 'Donación',
        'training': 'Solicitar Formación'
    };
    
    showToast(`Abriendo ${actionNames[actionType]}...`, 'info');
    
    setTimeout(() => {
        showToast(`${actionNames[actionType]} iniciado correctamente`, 'success');
    }, 1500);
}

// News functions
function readNews(newsId) {
    const newsTitles = {
        1: 'Nueva Sede Social Inaugurada',
        2: 'Próximo Ensayo de Bandas',
        3: 'Premio a la Mejor Filá 2024'
    };
    
    showToast(`Abriendo noticia: ${newsTitles[newsId]}`, 'info');
    
    setTimeout(() => {
        showToast(`Noticia ${newsTitles[newsId]} cargada`, 'success');
        // In a real implementation, this would open a modal with the full news article
    }, 1500);
}

// Gamification functions
function claimReward(rewardId) {
    const rewardNames = {
        1: 'Descuento 20% Tienda',
        2: 'Entrada Gratuita'
    };
    
    showToast(`Canjeando recompensa: ${rewardNames[rewardId]}`, 'info');
    
    setTimeout(() => {
        showToast(`¡Recompensa ${rewardNames[rewardId]} canjeada exitosamente!`, 'success');
        // In a real implementation, this would update the points and reward status
    }, 2000);
}

// Add keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + Enter to send message
    if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
        const messageInput = document.getElementById('messageInput');
        if (messageInput && document.activeElement === messageInput) {
            sendMessage();
        }
    }
    
    // Escape to close modals
    if (e.key === 'Escape') {
        const modals = document.querySelectorAll('.modal.show');
        modals.forEach(modal => {
            const modalInstance = bootstrap.Modal.getInstance(modal);
            if (modalInstance) {
                modalInstance.hide();
            }
        });
    }
});

// Utility functions
function showToast(message, type) {
    // Create toast notification
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'error' ? 'danger' : type} position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); border-radius: 8px;';
    
    // Add icon based on type
    const icons = {
        'success': 'bi-check-circle',
        'error': 'bi-exclamation-triangle',
        'warning': 'bi-exclamation-circle',
        'info': 'bi-info-circle'
    };
    
    toast.innerHTML = `
        <i class="bi ${icons[type] || icons.info} me-2"></i>
        ${message}
        <button type="button" class="btn-close ms-2" onclick="this.parentElement.remove()"></button>
    `;
    
    document.body.appendChild(toast);
    
    // Add entrance animation
    toast.style.opacity = '0';
    toast.style.transform = 'translateX(100%)';
    
    setTimeout(() => {
        toast.style.transition = 'all 0.3s ease';
        toast.style.opacity = '1';
        toast.style.transform = 'translateX(0)';
    }, 100);
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        if (toast.parentElement) {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 300);
        }
    }, 4000);
}

// Add smooth animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

document.querySelectorAll('.action-card, .activity-card, .info-card, .notifications-card, .stats-card, .weather-card, .events-card, .calendar-card, .documents-card, .resources-card, .messaging-card, .achievements-card, .surveys-card, .quick-actions-card, .news-card, .gamification-card').forEach(item => {
    item.style.opacity = '0';
    item.style.transform = 'translateY(20px)';
    item.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(item);
});
</script>
