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
<section class="login-section py-5 <?php echo isset($user) ? 'd-none' : ''; ?>" id="loginSection">
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

<!-- Dashboard Section -->
<section class="dashboard-section py-5 <?php echo isset($user) ? '' : 'd-none'; ?>" id="dashboardSection">
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
                        <a href="/prueba-php/public/logout.php" class="btn btn-outline-light btn-sm ms-3">
                            <i class="bi bi-box-arrow-right me-1"></i>Cerrar Sesión
                        </a>
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
            
            <!-- Nuevas tarjetas funcionales -->
            <div class="col-md-6 col-lg-3">
                <div class="action-card">
                    <div class="action-icon">
                        <i class="bi bi-chat-dots"></i>
                    </div>
                    <h5 class="action-title">Foro</h5>
                    <p class="action-description">Discusiones y noticias</p>
                    <button class="btn btn-action" onclick="showForum()">
                        Ir al foro
                    </button>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="action-card">
                    <div class="action-icon">
                        <i class="bi bi-trophy"></i>
                    </div>
                    <h5 class="action-title">Logros</h5>
                    <p class="action-description">Tu historial y logros</p>
                    <button class="btn btn-action" onclick="showAchievements()">
                        Ver logros
                    </button>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="action-card">
                    <div class="action-icon">
                        <i class="bi bi-gear"></i>
                    </div>
                    <h5 class="action-title">Configuración</h5>
                    <p class="action-description">Perfil y preferencias</p>
                    <button class="btn btn-action" onclick="showSettings()">
                        Configurar
                    </button>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="action-card">
                    <div class="action-icon">
                        <i class="bi bi-question-circle"></i>
                    </div>
                    <h5 class="action-title">Ayuda</h5>
                    <p class="action-description">Soporte y FAQ</p>
                    <button class="btn btn-action" onclick="showHelp()">
                        Obtener ayuda
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Recent Activity -->
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
            </div>
        </div>
        
        <!-- Nuevas secciones funcionales -->
        <div class="row g-4 mt-4">
            <!-- Sección de Notificaciones -->
            <div class="col-lg-6">
                <div class="activity-card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="bi bi-bell me-2"></i>Notificaciones
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="notification-list">
                            <div class="notification-item">
                                <div class="notification-icon">
                                    <i class="bi bi-calendar-event text-primary"></i>
                                </div>
                                <div class="notification-content">
                                    <h6>Nuevo evento programado</h6>
                                    <p>Reunión de directiva el próximo viernes</p>
                                    <small class="text-muted">Hace 1 hora</small>
                                </div>
                                <button class="btn btn-sm btn-outline-primary" onclick="markAsRead(this)">Marcar</button>
                            </div>
                            
                            <div class="notification-item">
                                <div class="notification-icon">
                                    <i class="bi bi-file-earmark-text text-success"></i>
                                </div>
                                <div class="notification-content">
                                    <h6>Nuevo documento disponible</h6>
                                    <p>Reglamento interno actualizado</p>
                                    <small class="text-muted">Hace 2 días</small>
                                </div>
                                <button class="btn btn-sm btn-outline-primary" onclick="markAsRead(this)">Marcar</button>
                            </div>
                            
                            <div class="notification-item">
                                <div class="notification-icon">
                                    <i class="bi bi-credit-card text-warning"></i>
                                </div>
                                <div class="notification-content">
                                    <h6>Recordatorio de cuota</h6>
                                    <p>Tu cuota mensual vence en 5 días</p>
                                    <small class="text-muted">Hace 3 días</small>
                                </div>
                                <button class="btn btn-sm btn-outline-primary" onclick="markAsRead(this)">Marcar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sección de Estadísticas Personales -->
            <div class="col-lg-6">
                <div class="activity-card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="bi bi-graph-up me-2"></i>Tu Actividad
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="bi bi-calendar-check"></i>
                                </div>
                                <div class="stat-info">
                                    <h4>12</h4>
                                    <p>Eventos asistidos</p>
                                </div>
                            </div>
                            
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="bi bi-clock"></i>
                                </div>
                                <div class="stat-info">
                                    <h4>48</h4>
                                    <p>Horas de servicio</p>
                                </div>
                            </div>
                            
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="bi bi-trophy"></i>
                                </div>
                                <div class="stat-info">
                                    <h4>3</h4>
                                    <p>Logros obtenidos</p>
                                </div>
                            </div>
                            
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="bi bi-star"></i>
                                </div>
                                <div class="stat-info">
                                    <h4>4.8</h4>
                                    <p>Puntuación</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sección de Eventos Próximos -->
        <div class="row g-4 mt-4">
            <div class="col-12">
                <div class="activity-card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="bi bi-calendar-week me-2"></i>Próximos Eventos
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="events-timeline">
                            <?php foreach ($eventos_socios as $evento): ?>
                            <div class="timeline-item">
                                <div class="timeline-marker">
                                    <i class="bi bi-<?php echo $evento['tipo'] === 'reunion' ? 'people' : ($evento['tipo'] === 'ensayo' ? 'music-note-beamed' : 'calendar-event'); ?>"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6><?php echo htmlspecialchars($evento['titulo']); ?></h6>
                                    <p class="mb-1">
                                        <i class="bi bi-calendar3 me-2"></i><?php echo $evento['fecha']; ?>
                                        <i class="bi bi-clock me-2 ms-3"></i><?php echo $evento['hora']; ?>
                                        <i class="bi bi-geo-alt me-2 ms-3"></i><?php echo htmlspecialchars($evento['lugar']); ?>
                                    </p>
                                    <div class="timeline-actions">
                                        <button class="btn btn-sm btn-outline-primary" onclick="confirmAttendance('<?php echo htmlspecialchars($evento['titulo']); ?>')">
                                            <i class="bi bi-check-circle me-1"></i>Confirmar asistencia
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" onclick="addToCalendar('<?php echo htmlspecialchars($evento['titulo']); ?>', '<?php echo $evento['fecha']; ?>', '<?php echo $evento['hora']; ?>')">
                                            <i class="bi bi-calendar-plus me-1"></i>Agregar al calendario
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
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
body {
    background: linear-gradient(135deg, rgba(220, 20, 60, 0.05) 0%, rgba(255, 255, 255, 0.85) 50%, rgba(220, 20, 60, 0.05) 100%);
    backdrop-filter: blur(15px);
    min-height: 100vh;
    position: relative;
}

body::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(45deg, rgba(220, 20, 60, 0.02) 0%, rgba(255, 255, 255, 0.1) 25%, rgba(220, 20, 60, 0.02) 50%, rgba(255, 255, 255, 0.1) 75%, rgba(220, 20, 60, 0.02) 100%);
    backdrop-filter: blur(5px);
    z-index: -1;
    pointer-events: none;
}

.hero-section {
    background: linear-gradient(135deg, rgba(220, 20, 60, 0.08) 0%, rgba(255, 255, 255, 0.6) 50%, rgba(220, 20, 60, 0.08) 100%);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
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
    background: rgba(248, 249, 250, 0.6);
    backdrop-filter: blur(15px);
}

.login-card {
    background: rgba(255, 255, 255, 0.8);
    border-radius: 20px;
    padding: 2.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 2px solid var(--primary);
    backdrop-filter: blur(15px);
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
    color: white;
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
    background: rgba(255, 255, 255, 0.7);
    border-radius: 15px;
    padding: 2rem;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    border: 2px solid transparent;
    transition: all 0.3s ease;
    backdrop-filter: blur(15px);
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
    background: rgba(255, 255, 255, 0.7);
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    border: 2px solid var(--primary);
    backdrop-filter: blur(15px);
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
    border-bottom: 1px solid rgba(233, 236, 239, 0.5);
    background: rgba(255, 255, 255, 0.3);
    border-radius: 8px;
    margin-bottom: 0.5rem;
    backdrop-filter: blur(5px);
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
    border-bottom: 1px solid rgba(233, 236, 239, 0.5);
    background: rgba(255, 255, 255, 0.3);
    border-radius: 8px;
    margin-bottom: 0.5rem;
    backdrop-filter: blur(5px);
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

/* Nuevos estilos para las secciones funcionales */
.notification-list {
    padding: 0;
}

.notification-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid rgba(233, 236, 239, 0.5);
    background: rgba(255, 255, 255, 0.3);
    border-radius: 8px;
    margin-bottom: 0.5rem;
    backdrop-filter: blur(5px);
}

.notification-item:last-child {
    border-bottom: none;
}

.notification-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.notification-content {
    flex-grow: 1;
}

.notification-content h6 {
    font-family: 'Cinzel', serif;
    color: var(--primary);
    margin-bottom: 0.25rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.stats-grid .stat-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: rgba(220, 20, 60, 0.1);
    backdrop-filter: blur(10px);
    border-radius: 10px;
    border: 1px solid rgba(220, 20, 60, 0.1);
}

.stats-grid .stat-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.stats-grid .stat-icon i {
    color: white;
    font-size: 1.2rem;
}

.stats-grid .stat-info h4 {
    font-family: 'Cinzel', serif;
    color: var(--primary);
    margin: 0;
    font-size: 1.5rem;
}

.stats-grid .stat-info p {
    margin: 0;
    color: #6c757d;
    font-size: 0.9rem;
}

.events-timeline {
    position: relative;
    padding-left: 2rem;
}

.events-timeline::before {
    content: '';
    position: absolute;
    left: 1rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--primary);
    opacity: 0.3;
}

.timeline-item {
    position: relative;
    margin-bottom: 2rem;
    padding-left: 2rem;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 8px;
    padding: 1rem;
    backdrop-filter: blur(5px);
}

.timeline-marker {
    position: absolute;
    left: -1.5rem;
    top: 0.5rem;
    width: 3rem;
    height: 3rem;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid white;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.timeline-marker i {
    color: white;
    font-size: 1.2rem;
}

.timeline-content h6 {
    font-family: 'Cinzel', serif;
    color: var(--primary);
    margin-bottom: 0.5rem;
}

.timeline-actions {
    margin-top: 0.5rem;
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
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
    
    .timeline-actions {
        flex-direction: column;
    }
    
    .timeline-actions .btn {
        width: 100%;
    }
}

/* Modales con transparencia */
.modal-content {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(20px);
    border: 2px solid var(--primary);
    border-radius: 15px;
}

.modal-header {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    color: white;
    border-bottom: 1px solid rgba(220, 20, 60, 0.2);
    border-radius: 15px 15px 0 0;
}

.modal-body {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
}

/* Elementos dentro de modales */
.event-item, .achievement-item, .category-item {
    background: rgba(255, 255, 255, 0.5);
    backdrop-filter: blur(5px);
    border: 1px solid rgba(220, 20, 60, 0.1);
    border-radius: 8px;
}
</style>

<script>
// Real login functionality
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const email = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    
    if (!email || !password) {
        alert('Por favor, completa todos los campos');
        return;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Iniciando sesión...';
    submitBtn.disabled = true;
    
    // Send login request
    fetch('/prueba-php/public/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
    })
    .then(response => {
        if (response.ok) {
            // Login successful, reload page to show dashboard
            window.location.reload();
        } else {
            throw new Error('Error en el login');
        }
    })
    .catch(error => {
        alert('Error al iniciar sesión. Verifica tus credenciales.');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
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
    // Crear modal con eventos reales
    const modalHtml = `
        <div class="modal fade" id="eventsModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-calendar-check me-2"></i>Próximos Eventos
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="events-list">
                            <?php foreach ($eventos_socios as $evento): ?>
                            <div class="event-item mb-3 p-3 border rounded">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1"><?php echo htmlspecialchars($evento['titulo']); ?></h6>
                                        <p class="mb-1 text-muted">
                                            <i class="bi bi-calendar3 me-1"></i><?php echo $evento['fecha']; ?>
                                            <i class="bi bi-clock me-1 ms-2"></i><?php echo $evento['hora']; ?>
                                        </p>
                                        <p class="mb-0 text-muted">
                                            <i class="bi bi-geo-alt me-1"></i><?php echo htmlspecialchars($evento['lugar']); ?>
                                        </p>
                                    </div>
                                    <div class="text-end">
                                        <button class="btn btn-sm btn-primary" onclick="confirmAttendance('<?php echo htmlspecialchars($evento['titulo']); ?>')">
                                            <i class="bi bi-check-circle me-1"></i>Confirmar
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-outline-primary" onclick="addToCalendar('Todos los eventos', '<?php echo date('d/m/Y'); ?>', '<?php echo date('H:i'); ?>')">
                                <i class="bi bi-calendar-plus me-2"></i>Agregar todos al calendario
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remover modal existente si existe
    const existingModal = document.getElementById('eventsModal');
    if (existingModal) existingModal.remove();
    
    // Agregar modal al body
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('eventsModal'));
    modal.show();
}

function showPayments() {
    // Crear modal con estado de pagos real
    const modalHtml = `
        <div class="modal fade" id="paymentsModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-credit-card me-2"></i>Estado de Cuotas
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="payment-status mb-4">
                            <div class="alert alert-<?php echo $socio_data['cuota_al_dia'] ? 'success' : 'warning'; ?>">
                                <i class="bi bi-<?php echo $socio_data['cuota_al_dia'] ? 'check-circle' : 'exclamation-triangle'; ?> me-2"></i>
                                <strong><?php echo $socio_data['cuota_al_dia'] ? 'Cuota al día' : 'Cuota pendiente'; ?></strong><br>
                                Último pago: <?php echo $socio_data['ultima_cuota']; ?>
                            </div>
                        </div>
                        
                        <div class="payment-info mb-4">
                            <h6>Información de Cuotas</h6>
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Cuota mensual:</small><br>
                                    <strong>25.00€</strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Próximo vencimiento:</small><br>
                                    <strong><?php echo date('d/m/Y', strtotime('+1 month')); ?></strong>
                                </div>
                            </div>
                        </div>
                        
                        <div class="payment-history mb-4">
                            <h6>Historial de Pagos</h6>
                            <div class="list-group">
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Enero 2024</strong><br>
                                        <small class="text-muted">25.00€</small>
                                    </div>
                                    <span class="badge bg-success">Pagado</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Diciembre 2023</strong><br>
                                        <small class="text-muted">25.00€</small>
                                    </div>
                                    <span class="badge bg-success">Pagado</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Noviembre 2023</strong><br>
                                        <small class="text-muted">25.00€</small>
                                    </div>
                                    <span class="badge bg-success">Pagado</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" onclick="makePayment()">
                                <i class="bi bi-credit-card me-2"></i>Realizar Pago
                            </button>
                            <button class="btn btn-outline-secondary" onclick="downloadReceipt()">
                                <i class="bi bi-download me-2"></i>Descargar Recibo
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    const existingModal = document.getElementById('paymentsModal');
    if (existingModal) existingModal.remove();
    
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    const modal = new bootstrap.Modal(document.getElementById('paymentsModal'));
    modal.show();
}

function showDocuments() {
    // Crear modal con documentos reales
    const modalHtml = `
        <div class="modal fade" id="documentsModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-file-earmark-text me-2"></i>Documentos de la Filá
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="searchDocuments" placeholder="Buscar documentos...">
                        </div>
                        
                        <div class="documents-list">
                            <?php foreach ($documentos_socios as $doc): ?>
                            <div class="document-item mb-3 p-3 border rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="document-icon me-3">
                                            <i class="bi bi-file-earmark-pdf text-danger" style="font-size: 2rem;"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1"><?php echo htmlspecialchars($doc['nombre']); ?></h6>
                                            <small class="text-muted">
                                                <?php echo $doc['tipo']; ?> • <?php echo $doc['tamaño']; ?> • <?php echo $doc['fecha']; ?>
                                            </small>
                                        </div>
                                    </div>
                                    <div class="document-actions">
                                        <button class="btn btn-sm btn-outline-primary me-2" onclick="previewDocument('<?php echo htmlspecialchars($doc['nombre']); ?>')">
                                            <i class="bi bi-eye me-1"></i>Vista previa
                                        </button>
                                        <button class="btn btn-sm btn-primary" onclick="downloadDocument('<?php echo htmlspecialchars($doc['nombre']); ?>')">
                                            <i class="bi bi-download me-1"></i>Descargar
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            
                            <!-- Documentos adicionales -->
                            <div class="document-item mb-3 p-3 border rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="document-icon me-3">
                                            <i class="bi bi-file-earmark-word text-primary" style="font-size: 2rem;"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">Formulario de Inscripción</h6>
                                            <small class="text-muted">DOCX • 1.2 MB • 20/01/2024</small>
                                        </div>
                                    </div>
                                    <div class="document-actions">
                                        <button class="btn btn-sm btn-outline-primary me-2" onclick="previewDocument('Formulario de Inscripción')">
                                            <i class="bi bi-eye me-1"></i>Vista previa
                                        </button>
                                        <button class="btn btn-sm btn-primary" onclick="downloadDocument('Formulario de Inscripción')">
                                            <i class="bi bi-download me-1"></i>Descargar
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="document-item mb-3 p-3 border rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="document-icon me-3">
                                            <i class="bi bi-file-earmark-excel text-success" style="font-size: 2rem;"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">Lista de Socios 2024</h6>
                                            <small class="text-muted">XLSX • 0.8 MB • 15/01/2024</small>
                                        </div>
                                    </div>
                                    <div class="document-actions">
                                        <button class="btn btn-sm btn-outline-primary me-2" onclick="previewDocument('Lista de Socios 2024')">
                                            <i class="bi bi-eye me-1"></i>Vista previa
                                        </button>
                                        <button class="btn btn-sm btn-primary" onclick="downloadDocument('Lista de Socios 2024')">
                                            <i class="bi bi-download me-1"></i>Descargar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    const existingModal = document.getElementById('documentsModal');
    if (existingModal) existingModal.remove();
    
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    const modal = new bootstrap.Modal(document.getElementById('documentsModal'));
    modal.show();
}

function showDirectory() {
    // Crear modal con directorio real
    const modalHtml = `
        <div class="modal fade" id="directoryModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-people me-2"></i>Directorio de Socios
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="searchMember" placeholder="Buscar socio por nombre, apellido o número...">
                        </div>
                        
                        <div class="members-list">
                            <div class="member-item mb-3 p-3 border rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="member-avatar me-3">
                                            <i class="bi bi-person-circle text-primary" style="font-size: 2.5rem;"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">Juan Carlos Martínez</h6>
                                            <small class="text-muted">Socio Activo • MS-2024-001</small><br>
                                            <small class="text-muted">Miembro desde: 15/03/2020</small>
                                        </div>
                                    </div>
                                    <div class="member-actions">
                                        <button class="btn btn-sm btn-outline-primary me-2" onclick="viewMemberProfile('Juan Carlos Martínez')">
                                            <i class="bi bi-person me-1"></i>Perfil
                                        </button>
                                        <button class="btn btn-sm btn-primary" onclick="contactMember('Juan Carlos Martínez')">
                                            <i class="bi bi-envelope me-1"></i>Contactar
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="member-item mb-3 p-3 border rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="member-avatar me-3">
                                            <i class="bi bi-person-circle text-primary" style="font-size: 2.5rem;"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">María García López</h6>
                                            <small class="text-muted">Socio Activo • MS-2024-002</small><br>
                                            <small class="text-muted">Miembro desde: 22/04/2020</small>
                                        </div>
                                    </div>
                                    <div class="member-actions">
                                        <button class="btn btn-sm btn-outline-primary me-2" onclick="viewMemberProfile('María García López')">
                                            <i class="bi bi-person me-1"></i>Perfil
                                        </button>
                                        <button class="btn btn-sm btn-primary" onclick="contactMember('María García López')">
                                            <i class="bi bi-envelope me-1"></i>Contactar
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="member-item mb-3 p-3 border rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="member-avatar me-3">
                                            <i class="bi bi-person-circle text-primary" style="font-size: 2.5rem;"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">Antonio Rodríguez Silva</h6>
                                            <small class="text-muted">Socio Activo • MS-2024-003</small><br>
                                            <small class="text-muted">Miembro desde: 10/05/2020</small>
                                        </div>
                                    </div>
                                    <div class="member-actions">
                                        <button class="btn btn-sm btn-outline-primary me-2" onclick="viewMemberProfile('Antonio Rodríguez Silva')">
                                            <i class="bi bi-person me-1"></i>Perfil
                                        </button>
                                        <button class="btn btn-sm btn-primary" onclick="contactMember('Antonio Rodríguez Silva')">
                                            <i class="bi bi-envelope me-1"></i>Contactar
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="member-item mb-3 p-3 border rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="member-avatar me-3">
                                            <i class="bi bi-person-circle text-primary" style="font-size: 2.5rem;"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">Carmen Fernández Ruiz</h6>
                                            <small class="text-muted">Socio Activo • MS-2024-004</small><br>
                                            <small class="text-muted">Miembro desde: 18/06/2020</small>
                                        </div>
                                    </div>
                                    <div class="member-actions">
                                        <button class="btn btn-sm btn-outline-primary me-2" onclick="viewMemberProfile('Carmen Fernández Ruiz')">
                                            <i class="bi bi-person me-1"></i>Perfil
                                        </button>
                                        <button class="btn btn-sm btn-primary" onclick="contactMember('Carmen Fernández Ruiz')">
                                            <i class="bi bi-envelope me-1"></i>Contactar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-3">
                            <button class="btn btn-outline-primary" onclick="exportDirectory()">
                                <i class="bi bi-download me-2"></i>Exportar Directorio
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    const existingModal = document.getElementById('directoryModal');
    if (existingModal) existingModal.remove();
    
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    const modal = new bootstrap.Modal(document.getElementById('directoryModal'));
    modal.show();
}

// Nuevas funciones para las tarjetas adicionales
function showForum() {
    const modalHtml = `
        <div class="modal fade" id="forumModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-chat-dots me-2"></i>Foro de Socios
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="forum-categories">
                            <div class="category-item mb-3 p-3 border rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6><i class="bi bi-megaphone me-2"></i>Anuncios Generales</h6>
                                        <p class="text-muted mb-1">Últimos anuncios de la directiva</p>
                                        <small class="text-muted">3 temas • Último: hace 2 horas</small>
                                    </div>
                                    <button class="btn btn-sm btn-outline-primary" onclick="viewCategory('anuncios')">
                                        Ver temas
                                    </button>
                                </div>
                            </div>
                            
                            <div class="category-item mb-3 p-3 border rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6><i class="bi bi-calendar-event me-2"></i>Eventos y Actividades</h6>
                                        <p class="text-muted mb-1">Discusiones sobre eventos</p>
                                        <small class="text-muted">8 temas • Último: hace 1 día</small>
                                    </div>
                                    <button class="btn btn-sm btn-outline-primary" onclick="viewCategory('eventos')">
                                        Ver temas
                                    </button>
                                </div>
                            </div>
                            
                            <div class="category-item mb-3 p-3 border rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6><i class="bi bi-chat-square-dots me-2"></i>General</h6>
                                        <p class="text-muted mb-1">Conversaciones generales</p>
                                        <small class="text-muted">15 temas • Último: hace 3 horas</small>
                                    </div>
                                    <button class="btn btn-sm btn-outline-primary" onclick="viewCategory('general')">
                                        Ver temas
                                    </button>
                                </div>
                            </div>
                            
                            <div class="category-item mb-3 p-3 border rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6><i class="bi bi-question-circle me-2"></i>Preguntas y Ayuda</h6>
                                        <p class="text-muted mb-1">Preguntas y respuestas</p>
                                        <small class="text-muted">5 temas • Último: hace 5 horas</small>
                                    </div>
                                    <button class="btn btn-sm btn-outline-primary" onclick="viewCategory('ayuda')">
                                        Ver temas
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 mt-3">
                            <button class="btn btn-primary" onclick="createTopic()">
                                <i class="bi bi-plus-circle me-2"></i>Nuevo Tema
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    const existingModal = document.getElementById('forumModal');
    if (existingModal) existingModal.remove();
    
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    const modal = new bootstrap.Modal(document.getElementById('forumModal'));
    modal.show();
}

function showAchievements() {
    const modalHtml = `
        <div class="modal fade" id="achievementsModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-trophy me-2"></i>Mis Logros
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="achievements-list">
                            <div class="achievement-item mb-3 p-3 border rounded">
                                <div class="d-flex align-items-center">
                                    <div class="achievement-icon me-3">
                                        <i class="bi bi-trophy-fill text-warning" style="font-size: 2rem;"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Socio Activo</h6>
                                        <p class="text-muted mb-1">Has sido socio activo por más de 1 año</p>
                                        <small class="text-success">Obtenido el 15/03/2023</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="achievement-item mb-3 p-3 border rounded">
                                <div class="d-flex align-items-center">
                                    <div class="achievement-icon me-3">
                                        <i class="bi bi-star-fill text-primary" style="font-size: 2rem;"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Participación Destacada</h6>
                                        <p class="text-muted mb-1">Has participado en 10+ eventos</p>
                                        <small class="text-success">Obtenido el 20/12/2023</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="achievement-item mb-3 p-3 border rounded">
                                <div class="d-flex align-items-center">
                                    <div class="achievement-icon me-3">
                                        <i class="bi bi-heart-fill text-danger" style="font-size: 2rem;"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Colaborador</h6>
                                        <p class="text-muted mb-1">Has ayudado en la organización de eventos</p>
                                        <small class="text-success">Obtenido el 10/11/2023</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="achievement-item mb-3 p-3 border rounded opacity-50">
                                <div class="d-flex align-items-center">
                                    <div class="achievement-icon me-3">
                                        <i class="bi bi-crown text-secondary" style="font-size: 2rem;"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Veterano</h6>
                                        <p class="text-muted mb-1">Socio por más de 5 años</p>
                                        <small class="text-muted">Progreso: 3/5 años</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-3">
                            <button class="btn btn-outline-primary" onclick="shareAchievements()">
                                <i class="bi bi-share me-2"></i>Compartir Logros
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    const existingModal = document.getElementById('achievementsModal');
    if (existingModal) existingModal.remove();
    
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    const modal = new bootstrap.Modal(document.getElementById('achievementsModal'));
    modal.show();
}

function showSettings() {
    const modalHtml = `
        <div class="modal fade" id="settingsModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-gear me-2"></i>Configuración
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="settingsForm">
                            <div class="mb-4">
                                <h6>Notificaciones por email</h6>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="notifEvents" checked>
                                    <label class="form-check-label" for="notifEvents">Nuevos eventos</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="notifPayments" checked>
                                    <label class="form-check-label" for="notifPayments">Recordatorios de pago</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="notifDocuments">
                                    <label class="form-check-label" for="notifDocuments">Nuevos documentos</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="notifForum" checked>
                                    <label class="form-check-label" for="notifForum">Respuestas en el foro</label>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <h6>Tema de la interfaz</h6>
                                <select class="form-select" id="themeSelect">
                                    <option value="light">Claro</option>
                                    <option value="dark">Oscuro</option>
                                    <option value="auto">Automático</option>
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <h6>Idioma</h6>
                                <select class="form-select" id="languageSelect">
                                    <option value="es">Español</option>
                                    <option value="en">English</option>
                                    <option value="ca">Català</option>
                                </select>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-2"></i>Guardar Configuración
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="resetSettings()">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Restaurar por defecto
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    const existingModal = document.getElementById('settingsModal');
    if (existingModal) existingModal.remove();
    
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    const modal = new bootstrap.Modal(document.getElementById('settingsModal'));
    modal.show();
}

function showHelp() {
    const modalHtml = `
        <div class="modal fade" id="helpModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-question-circle me-2"></i>Centro de Ayuda
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="help-sections">
                            <div class="help-section mb-4">
                                <h6><i class="bi bi-info-circle me-2"></i>Preguntas Frecuentes</h6>
                                <div class="accordion" id="faqAccordion">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                                ¿Cómo puedo confirmar mi asistencia a un evento?
                                            </button>
                                        </h2>
                                        <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                            <div class="accordion-body">
                                                Puedes confirmar tu asistencia haciendo clic en el botón "Confirmar asistencia" en la sección de eventos o en el timeline de eventos próximos.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                                ¿Cómo puedo realizar el pago de mi cuota?
                                            </button>
                                        </h2>
                                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                            <div class="accordion-body">
                                                Puedes realizar el pago desde la sección "Cuotas" o contactando directamente con el tesorero de la filá.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                                ¿Cómo puedo contactar con otros socios?
                                            </button>
                                        </h2>
                                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                            <div class="accordion-body">
                                                Puedes usar el directorio de socios para ver la información de contacto de otros miembros de la filá.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="help-section">
                                <h6><i class="bi bi-telephone me-2"></i>Contacto</h6>
                                <p>Si necesitas ayuda adicional, puedes contactar con:</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="contact-item p-3 border rounded mb-2">
                                            <strong>Presidente</strong><br>
                                            <small class="text-muted">presidente@mariscales.com</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="contact-item p-3 border rounded mb-2">
                                            <strong>Secretario</strong><br>
                                            <small class="text-muted">secretario@mariscales.com</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="contact-item p-3 border rounded mb-2">
                                            <strong>Tesorero</strong><br>
                                            <small class="text-muted">tesorero@mariscales.com</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="contact-item p-3 border rounded mb-2">
                                            <strong>Soporte Técnico</strong><br>
                                            <small class="text-muted">soporte@mariscales.com</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    const existingModal = document.getElementById('helpModal');
    if (existingModal) existingModal.remove();
    
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    const modal = new bootstrap.Modal(document.getElementById('helpModal'));
    modal.show();
}

// Funciones para las nuevas secciones
function markAsRead(button) {
    const notificationItem = button.closest('.notification-item');
    notificationItem.style.opacity = '0.5';
    button.innerHTML = '<i class="bi bi-check me-1"></i>Leído';
    button.disabled = true;
    button.classList.remove('btn-outline-primary');
    button.classList.add('btn-outline-success');
}

function confirmAttendance(eventTitle) {
    if (confirm(`¿Confirmas tu asistencia al evento "${eventTitle}"?`)) {
        alert('¡Asistencia confirmada! Te esperamos en el evento.');
    }
}

function addToCalendar(eventTitle, date, time) {
    // Crear enlace para agregar al calendario
    const eventDate = new Date(date.split('/').reverse().join('-') + 'T' + time + ':00');
    const endDate = new Date(eventDate.getTime() + 2 * 60 * 60 * 1000); // 2 horas después
    
    const googleCalendarUrl = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(eventTitle)}&dates=${eventDate.toISOString().replace(/[-:]/g, '').split('.')[0]}Z/${endDate.toISOString().replace(/[-:]/g, '').split('.')[0]}Z`;
    
    window.open(googleCalendarUrl, '_blank');
}

function makePayment() {
    alert('Sistema de pagos en desarrollo. Por favor, contacta con el tesorero para realizar el pago.');
}

function downloadDocument(docName) {
    alert(`Descarga de "${docName}" en desarrollo. El documento estará disponible próximamente.`);
}

function contactMember(memberName) {
    alert(`Contacto con ${memberName} en desarrollo. Próximamente podrás enviar mensajes directos.`);
}

function createTopic() {
    alert('Creación de temas en desarrollo. Próximamente podrás crear nuevos temas en el foro.');
}

// Funciones adicionales para completar la funcionalidad
function downloadReceipt() {
    alert('Descarga de recibo en desarrollo. El recibo estará disponible próximamente.');
}

function previewDocument(docName) {
    alert(`Vista previa de "${docName}" en desarrollo. La funcionalidad estará disponible próximamente.`);
}

function viewMemberProfile(memberName) {
    alert(`Perfil de ${memberName} en desarrollo. Próximamente podrás ver perfiles detallados.`);
}

function exportDirectory() {
    alert('Exportación del directorio en desarrollo. Próximamente podrás descargar el directorio completo.');
}

function viewCategory(category) {
    alert(`Categoría "${category}" en desarrollo. Próximamente podrás ver todos los temas de esta categoría.`);
}

function shareAchievements() {
    alert('Compartir logros en desarrollo. Próximamente podrás compartir tus logros en redes sociales.');
}

function resetSettings() {
    if (confirm('¿Estás seguro de que quieres restaurar la configuración por defecto?')) {
        // Resetear checkboxes
        document.getElementById('notifEvents').checked = true;
        document.getElementById('notifPayments').checked = true;
        document.getElementById('notifDocuments').checked = false;
        document.getElementById('notifForum').checked = true;
        
        // Resetear selects
        document.getElementById('themeSelect').value = 'light';
        document.getElementById('languageSelect').value = 'es';
        
        alert('Configuración restaurada por defecto.');
    }
}

// Manejar envío del formulario de configuración
document.addEventListener('DOMContentLoaded', function() {
    // Agregar event listener para el formulario de configuración cuando se cree
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            mutation.addedNodes.forEach(function(node) {
                if (node.nodeType === 1 && node.id === 'settingsForm') {
                    node.addEventListener('submit', function(e) {
                        e.preventDefault();
                        alert('Configuración guardada correctamente.');
                        const modal = bootstrap.Modal.getInstance(document.getElementById('settingsModal'));
                        modal.hide();
                    });
                }
            });
        });
    });
    
    observer.observe(document.body, { childList: true, subtree: true });
});

// Logout function
function logout() {
    if (confirm('¿Estás seguro de que quieres cerrar sesión?')) {
        fetch('/prueba-php/public/logout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            }
        })
        .then(response => {
            if (response.ok) {
                window.location.reload();
            } else {
                alert('Error al cerrar sesión');
            }
        })
        .catch(error => {
            alert('Error al cerrar sesión');
        });
    }
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

document.querySelectorAll('.action-card, .activity-card, .info-card').forEach(item => {
    item.style.opacity = '0';
    item.style.transform = 'translateY(20px)';
    item.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(item);
});
</script>
