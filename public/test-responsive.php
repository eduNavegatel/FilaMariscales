<?php
/**
 * Página de prueba para el Responsive Design Moderno
 * Verifica que todos los componentes funcionen correctamente
 */

require_once '../vendor/autoload.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🧪 Test Responsive Design Moderno - Filá Mariscales</title>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
    
    <style>
        .test-section {
            padding: 4rem 0;
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }
        
        .test-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            margin: 1rem 0;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .test-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }
        
        .test-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin: 2rem 0;
        }
        
        .test-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        
        .test-image:hover {
            transform: scale(1.05);
        }
        
        .test-counter {
            font-size: 3rem;
            font-weight: bold;
            color: var(--templar-red);
            text-align: center;
        }
        
        .test-progress {
            margin: 1rem 0;
        }
        
        .test-badge {
            margin: 0.25rem;
        }
        
        .test-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
    </style>
</head>
<body>
    <!-- Navbar de Prueba -->
    <nav class="navbar fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-shield-alt"></i> Filá Mariscales
            </a>
            
            <button class="navbar-toggler" type="button">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#grid">Grid</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#animations">Animaciones</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#components">Componentes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#forms">Formularios</a>
                </li>
                <li class="nav-item mobile-only">
                    <button class="nav-link close-menu" type="button">
                        <i class="fas fa-times"></i> Cerrar Menú
                    </button>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" style="background: linear-gradient(135deg, var(--templar-red) 0%, var(--templar-red-dark) 100%);">
        <div class="hero-content">
            <h1 class="hero-title">🧪 Test Responsive Design</h1>
            <p class="hero-subtitle">Verificando que todo funcione perfectamente en todos los dispositivos</p>
            <div class="hero-buttons">
                <button class="btn btn-primary" onclick="showNotification('success', '¡Test exitoso!')">
                    <i class="fas fa-check"></i> Test Exitoso
                </button>
                <button class="btn btn-secondary" onclick="showNotification('error', 'Test de error')">
                    <i class="fas fa-times"></i> Test Error
                </button>
            </div>
        </div>
    </section>

    <!-- Grid System Test -->
    <section id="grid" class="test-section">
        <div class="container">
            <h2 class="text-center mb-5" data-animate>🎯 Sistema de Grid</h2>
            
            <div class="grid grid-auto-fit">
                <div class="test-card" data-animate>
                    <h3><i class="fas fa-mobile-alt"></i> Mobile First</h3>
                    <p>Diseño optimizado para dispositivos móviles con breakpoints responsivos.</p>
                    <div class="test-badges">
                        <span class="badge badge-primary">Responsive</span>
                        <span class="badge badge-success">Mobile</span>
                    </div>
                </div>
                
                <div class="test-card" data-animate>
                    <h3><i class="fas fa-tablet-alt"></i> Tablet Ready</h3>
                    <p>Adaptación perfecta para tablets con layouts intermedios.</p>
                    <div class="test-badges">
                        <span class="badge badge-primary">Tablet</span>
                        <span class="badge badge-info">Adaptive</span>
                    </div>
                </div>
                
                <div class="test-card" data-animate>
                    <h3><i class="fas fa-desktop"></i> Desktop Optimized</h3>
                    <p>Experiencia completa en pantallas grandes con máximo detalle.</p>
                    <div class="test-badges">
                        <span class="badge badge-primary">Desktop</span>
                        <span class="badge badge-warning">Full HD</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Animations Test -->
    <section id="animations" class="test-section">
        <div class="container">
            <h2 class="text-center mb-5" data-animate>🎭 Animaciones</h2>
            
            <div class="grid grid-3">
                <div class="test-card text-center" data-animate>
                    <div class="test-counter" data-counter="150">0</div>
                    <h4>Contador Animado</h4>
                    <p>Se anima cuando entra en el viewport</p>
                </div>
                
                <div class="test-card text-center" data-animate>
                    <div class="test-counter" data-counter="250">0</div>
                    <h4>Otro Contador</h4>
                    <p>También se anima automáticamente</p>
                </div>
                
                <div class="test-card text-center" data-animate>
                    <div class="test-counter" data-counter="100">0</div>
                    <h4>Más Contadores</h4>
                    <p>Para probar múltiples animaciones</p>
                </div>
            </div>
            
            <div class="test-progress">
                <h4>Barra de Progreso Animada</h4>
                <div class="progress">
                    <div class="progress-bar" style="width: 75%"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Components Test -->
    <section id="components" class="test-section">
        <div class="container">
            <h2 class="text-center mb-5" data-animate>🔧 Componentes</h2>
            
            <div class="test-grid">
                <div class="test-card" data-animate>
                    <h3><i class="fas fa-image"></i> Lightbox Test</h3>
                    <p>Haz clic en las imágenes para probar el lightbox:</p>
                    <img src="https://picsum.photos/300/200?random=1" 
                         data-lightbox 
                         class="test-image" 
                         alt="Imagen de prueba 1">
                    <img src="https://picsum.photos/300/200?random=2" 
                         data-lightbox 
                         class="test-image" 
                         alt="Imagen de prueba 2">
                </div>
                
                <div class="test-card" data-animate>
                    <h3><i class="fas fa-tooltip"></i> Tooltips</h3>
                    <p>Pasa el mouse sobre estos elementos:</p>
                    <button class="btn btn-primary tooltip" data-tooltip="Este es un tooltip de prueba">
                        Botón con Tooltip
                    </button>
                    <br><br>
                    <span class="tooltip" data-tooltip="Texto con tooltip informativo">
                        <i class="fas fa-info-circle"></i> Información
                    </span>
                </div>
                
                <div class="test-card" data-animate>
                    <h3><i class="fas fa-bell"></i> Notificaciones</h3>
                    <p>Prueba las notificaciones:</p>
                    <button class="btn btn-success" onclick="showNotification('success', '¡Operación exitosa!')">
                        Éxito
                    </button>
                    <button class="btn btn-danger" onclick="showNotification('error', 'Algo salió mal')">
                        Error
                    </button>
                    <button class="btn btn-warning" onclick="showNotification('warning', 'Advertencia importante')">
                        Advertencia
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Forms Test -->
    <section id="forms" class="test-section">
        <div class="container">
            <h2 class="text-center mb-5" data-animate>📝 Formularios Modernos</h2>
            
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="test-card">
                        <form id="testForm">
                            <div class="floating-label">
                                <input type="text" id="name" required>
                                <label for="name">Nombre Completo</label>
                            </div>
                            
                            <div class="floating-label">
                                <input type="email" id="email" required>
                                <label for="email">Correo Electrónico</label>
                            </div>
                            
                            <div class="floating-label">
                                <input type="tel" id="phone" required>
                                <label for="phone">Teléfono</label>
                            </div>
                            
                            <div class="floating-label">
                                <textarea id="message" data-autoresize required></textarea>
                                <label for="message">Mensaje</label>
                            </div>
                            
                            <div class="floating-label">
                                <select id="country" required>
                                    <option value="">Selecciona un país</option>
                                    <option value="es">España</option>
                                    <option value="mx">México</option>
                                    <option value="ar">Argentina</option>
                                    <option value="co">Colombia</option>
                                </select>
                                <label for="country">País</label>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Enviar Formulario
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Scroll to Top Button -->
    <button class="scroll-to-top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Notifications Container -->
    <div id="notifications"></div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/main.js"></script>
    
    <script>
        // Función para mostrar notificaciones
        function showNotification(type, message) {
            const notifications = document.getElementById('notifications');
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.innerHTML = `
                <div class="notification-title">${type.charAt(0).toUpperCase() + type.slice(1)}</div>
                <div class="notification-message">${message}</div>
            `;
            
            notifications.appendChild(notification);
            
            // Mostrar notificación
            setTimeout(() => {
                notification.classList.add('show');
            }, 100);
            
            // Ocultar notificación después de 5 segundos
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    notifications.removeChild(notification);
                }, 300);
            }, 5000);
        }
        
        // Test del formulario
        document.getElementById('testForm').addEventListener('submit', function(e) {
            e.preventDefault();
            showNotification('success', '¡Formulario enviado correctamente!');
        });
        
        // Test de responsive
        function testResponsive() {
            const width = window.innerWidth;
            let device = 'Desktop';
            
            if (width < 576) device = 'Mobile';
            else if (width < 768) device = 'Tablet Portrait';
            else if (width < 992) device = 'Tablet Landscape';
            else if (width < 1200) device = 'Desktop';
            else device = 'Large Desktop';
            
            console.log(`📱 Dispositivo detectado: ${device} (${width}px)`);
        }
        
        // Ejecutar test al cargar y al cambiar tamaño
        window.addEventListener('load', testResponsive);
        window.addEventListener('resize', testResponsive);
        
        // Test de performance
        window.addEventListener('load', function() {
            const loadTime = performance.now();
            console.log(`⚡ Tiempo de carga: ${loadTime.toFixed(2)}ms`);
            
            if (loadTime < 2000) {
                console.log('✅ Performance excelente');
            } else if (loadTime < 4000) {
                console.log('⚠️ Performance aceptable');
            } else {
                console.log('❌ Performance necesita mejora');
            }
        });
    </script>
</body>
</html>
