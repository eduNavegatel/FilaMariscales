<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login de Socios - Filá Mariscales</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #dc143c;
            --primary-dark: #8b0000;
        }
        
        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
    </style>
</head>
<body>

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
                        <h3 class="text-gradient mb-0"><?php echo $data['stats']['socios_activos']; ?>+</h3>
                        <small class="text-muted">Socios Activos</small>
                    </div>
                    <div class="stat-item">
                        <h3 class="text-gradient mb-0"><?php echo $data['stats']['anios_historia']; ?></h3>
                        <small class="text-muted">Años de Historia</small>
                    </div>
                    <div class="stat-item">
                        <h3 class="text-gradient mb-0"><?php echo $data['stats']['eventos_anuales']; ?>+</h3>
                        <small class="text-muted">Eventos Anuales</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Login Section -->
<section class="login-section py-5">
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
                    
                    <form action="/prueba-php/public/socios/login" method="POST" class="login-form">
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope me-2"></i>Correo Electrónico
                            </label>
                            <input type="email" 
                                   class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" 
                                   id="email" 
                                   name="email" 
                                   value="<?php echo $data['email'] ?? ''; ?>"
                                   required>
                            <?php if (!empty($data['email_err'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo $data['email_err']; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group mb-4">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock me-2"></i>Contraseña
                            </label>
                            <input type="password" 
                                   class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" 
                                   id="password" 
                                   name="password" 
                                   required>
                            <?php if (!empty($data['password_err'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo $data['password_err']; ?>
                                </div>
                            <?php endif; ?>
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
                            ¿No tienes cuenta de socio? 
                            <a href="#" class="register-link" onclick="contactarDirectiva()">Contacta con la directiva</a>
                        </p>
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

.form-control.is-invalid {
    border-color: #dc3545;
}

.form-control.is-invalid:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
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

/* Responsive */
@media (max-width: 768px) {
    .socios-stats {
        flex-direction: column;
        gap: 1rem;
    }
    
    .login-card {
        padding: 2rem 1.5rem;
    }
}
</style>



<!-- Toast Container -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="loginToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="bi bi-info-circle me-2"></i>
            <strong class="me-auto" id="toastTitle">Notificación</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toastMessage">
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Función para mostrar toast
function showToast(title, message, type = 'info') {
    const toast = document.getElementById('loginToast');
    const toastTitle = document.getElementById('toastTitle');
    const toastMessage = document.getElementById('toastMessage');
    
    // Configurar el tipo de toast
    toast.className = `toast ${type === 'error' ? 'bg-danger text-white' : type === 'success' ? 'bg-success text-white' : ''}`;
    
    // Configurar icono según el tipo
    let icon = 'bi-info-circle';
    if (type === 'error') icon = 'bi-exclamation-triangle';
    if (type === 'success') icon = 'bi-check-circle';
    
    toastTitle.innerHTML = `<i class="bi ${icon} me-2"></i>${title}`;
    toastMessage.textContent = message;
    
    // Mostrar el toast
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
}

// Manejar envío del formulario
document.querySelector('.login-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Mostrar estado de carga
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Verificando...';
    
    // Enviar petición AJAX
    fetch('/prueba-php/public/socios/login', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('¡Éxito!', 'Iniciando sesión...', 'success');
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1000);
        } else {
            showToast('Error', data.message, 'error');
            // Limpiar contraseña
            document.getElementById('password').value = '';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error', 'Error de conexión. Inténtalo de nuevo.', 'error');
    })
    .finally(() => {
        // Restaurar botón
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Función para contactar directiva
function contactarDirectiva() {
    showToast('Información', 'Para obtener una cuenta de socio, contacta con la directiva de la Filá Mariscales.', 'info');
}

// Handle forgot password form
document.getElementById('forgotPasswordForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    showToast('Información', 'Se ha enviado un enlace de recuperación a tu correo electrónico.', 'info');
    const modal = bootstrap.Modal.getInstance(document.getElementById('forgotPasswordModal'));
    modal.hide();
});

// Animaciones de entrada
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('.login-card, .stat-item').forEach(item => {
    item.style.opacity = '0';
    item.style.transform = 'translateY(20px)';
    item.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(item);
});
</script>

</body>
</html>
