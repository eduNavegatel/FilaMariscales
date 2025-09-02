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
