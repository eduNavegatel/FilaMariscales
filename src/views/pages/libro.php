<?php 
// Datos de la carta de menú - Temática Filá Mariscales
$carta_menu = [
    'tradiciones' => [
        'titulo' => 'Tradiciones',
        'platos' => [
            [
                'nombre' => 'Historia de la Fundación',
                'descripcion' => 'Conoce los orígenes de la Filá Mariscales desde 1985 hasta nuestros días',
                'precio' => 'Gratis'
            ],
            [
                'nombre' => 'Estatutos y Reglamentos',
                'descripcion' => 'Documentos oficiales que rigen la vida de nuestra hermandad templaria',
                'precio' => 'Gratis'
            ],
            [
                'nombre' => 'Código de Honor',
                'descripcion' => 'Los valores y principios que guían a cada caballero templario',
                'precio' => 'Gratis'
            ],
            [
                'nombre' => 'Ceremonias y Rituales',
                'descripcion' => 'Tradiciones ancestrales que se mantienen vivas en cada celebración',
                'precio' => 'Gratis'
            ]
        ]
    ],
    'actividades' => [
        'titulo' => 'Actividades',
        'platos' => [
            [
                'nombre' => 'Desfiles de Moros y Cristianos',
                'descripcion' => 'Participación en las fiestas patronales de Elche con trajes históricos',
                'precio' => 'Socio'
            ],
            [
                'nombre' => 'Ensayos y Preparación',
                'descripcion' => 'Entrenamientos regulares para mantener la excelencia en cada actuación',
                'precio' => 'Socio'
            ],
            [
                'nombre' => 'Eventos Culturales',
                'descripcion' => 'Conferencias, exposiciones y actividades educativas sobre historia medieval',
                'precio' => 'Público'
            ],
            [
                'nombre' => 'Hermanamientos',
                'descripcion' => 'Intercambios culturales con otras filaes templarias de España',
                'precio' => 'Socio'
            ],
            [
                'nombre' => 'Actividades Sociales',
                'descripcion' => 'Comidas de hermandad, excursiones y eventos de convivencia',
                'precio' => 'Socio'
            ]
        ]
    ],
    'servicios' => [
        'titulo' => 'Servicios',
        'platos' => [
            [
                'nombre' => 'Alquiler de Trajes',
                'descripcion' => 'Vestimenta completa de caballero templario para eventos especiales',
                'precio' => 'Consultar'
            ],
            [
                'nombre' => 'Asesoramiento Histórico',
                'descripcion' => 'Consultoría sobre tradiciones y protocolo de las fiestas',
                'precio' => 'Gratis'
            ],
            [
                'nombre' => 'Formación de Nuevos Miembros',
                'descripcion' => 'Programa de iniciación para futuros caballeros templarios',
                'precio' => 'Socio'
            ],
            [
                'nombre' => 'Archivo Histórico',
                'descripcion' => 'Acceso a documentos, fotografías y memorias de la filá',
                'precio' => 'Gratis'
            ]
        ]
    ],
    'informacion' => [
        'titulo' => 'Información',
        'platos' => [
            [
                'nombre' => 'Contacto Directivo',
                'descripcion' => 'Información de contacto con la junta directiva de la filá',
                'precio' => 'Gratis'
            ],
            [
                'nombre' => 'Proceso de Admisión',
                'descripcion' => 'Requisitos y pasos para formar parte de nuestra hermandad',
                'precio' => 'Gratis'
            ],
            [
                'nombre' => 'Calendario de Eventos',
                'descripcion' => 'Próximas actividades, ensayos y celebraciones programadas',
                'precio' => 'Gratis'
            ],
            [
                'nombre' => 'Redes Sociales',
                'descripcion' => 'Síguenos en Facebook, Instagram y otras plataformas digitales',
                'precio' => 'Gratis'
            ]
        ]
    ]
];

?>

<!-- Hero Section -->
<section class="hero-section py-5" style="background: linear-gradient(135deg, rgba(220, 20, 60, 0.1) 0%, rgba(255, 255, 255, 0.8) 50%, rgba(220, 20, 60, 0.1) 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <h1 class="display-4 fw-bold text-gradient mb-4">
                    <i class="bi bi-shield-check me-3"></i>Filá Mariscales
                </h1>
                <p class="lead mb-5">Caballeros Templarios - Tradición, Honor y Hermandad</p>
                <div class="menu-stats d-flex justify-content-center gap-4 mb-5">
                    <div class="stat-item">
                        <h3 class="text-gradient mb-0">4</h3>
                        <small class="text-muted">Categorías</small>
                    </div>
                    <div class="stat-item">
                        <h3 class="text-gradient mb-0">17</h3>
                        <small class="text-muted">Servicios</small>
                    </div>
                    <div class="stat-item">
                        <h3 class="text-gradient mb-0">39</h3>
                        <small class="text-muted">Años</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Carta de Menú Section -->
<section class="menu-section py-5" style="background: linear-gradient(135deg, rgba(248, 249, 250, 0.9) 0%, rgba(233, 236, 239, 0.9) 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <!-- Menu Header -->
                <div class="menu-header text-center mb-5">
                    <h2 class="display-5 fw-bold text-gradient mb-3">
                        <i class="bi bi-shield-check me-3"></i>Servicios y Actividades
                    </h2>
                    <p class="lead mb-4">Descubre todo lo que ofrece la Filá Mariscales de Caballeros Templarios</p>
                </div>

                <!-- Menu Content -->
                <div class="menu-content" id="menuContent">
                    <div class="menu-container">
                        <!-- Menu Navigation -->
                        <div class="menu-nav mb-4">
                            <div class="nav nav-pills justify-content-center" id="menuTabs" role="tablist">
                                <?php foreach ($carta_menu as $key => $categoria): ?>
                                <button class="nav-link menu-tab-btn" 
                                        id="<?php echo $key; ?>-tab" 
                                        data-bs-toggle="pill" 
                                        data-bs-target="#<?php echo $key; ?>" 
                                        type="button" 
                                        role="tab">
                                    <i class="bi bi-<?php echo $key === 'tradiciones' ? 'book' : ($key === 'actividades' ? 'calendar-event' : ($key === 'servicios' ? 'tools' : 'info-circle')); ?> me-2"></i>
                                    <?php echo $categoria['titulo']; ?>
                                </button>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Menu Categories -->
                        <div class="tab-content" id="menuTabContent">
                            <?php foreach ($carta_menu as $key => $categoria): ?>
                            <div class="tab-pane fade <?php echo $key === 'tradiciones' ? 'show active' : ''; ?>" 
                                 id="<?php echo $key; ?>" 
                                 role="tabpanel">
                                <div class="menu-category">
                                    <div class="category-header">
                                        <h3 class="category-title">
                                            <i class="bi bi-<?php echo $key === 'tradiciones' ? 'book' : ($key === 'actividades' ? 'calendar-event' : ($key === 'servicios' ? 'tools' : 'info-circle')); ?> me-3"></i>
                                            <?php echo $categoria['titulo']; ?>
                                        </h3>
                                        <div class="category-decoration"></div>
                                    </div>
                                    
                                    <div class="menu-items">
                                        <?php foreach ($categoria['platos'] as $plato): ?>
                                        <div class="menu-item">
                                            <div class="item-content">
                                                <div class="item-header">
                                                    <h4 class="item-name"><?php echo $plato['nombre']; ?></h4>
                                                    <span class="item-price"><?php echo $plato['precio']; ?>€</span>
                                                </div>
                                                <p class="item-description"><?php echo $plato['descripcion']; ?></p>
                                            </div>
                                            <div class="item-actions">
                                                <button class="btn btn-outline-primary btn-sm add-to-cart" 
                                                        data-item="<?php echo htmlspecialchars($plato['nombre']); ?>" 
                                                        data-price="<?php echo $plato['precio']; ?>">
                                                    <i class="bi bi-info-circle me-1"></i>Más Info
                                                </button>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Menu Footer -->
                        <div class="menu-footer mt-5">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="menu-info">
                                        <h5><i class="bi bi-shield-check me-2"></i>Nuestra Misión</h5>
                                        <p class="mb-1">Mantener viva la tradición templaria y el honor caballeresco</p>
                                        <p class="mb-0">Promover la cultura y las tradiciones de Elche</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="menu-contact">
                                        <h5><i class="bi bi-telephone me-2"></i>Contacto</h5>
                                        <p class="mb-1">Teléfono: 965 123 456</p>
                                        <p class="mb-0">Email: info@filamariscales.com</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, rgba(220, 20, 60, 0.05) 0%, rgba(255, 255, 255, 0.7) 50%, rgba(220, 20, 60, 0.05) 100%);
    border-bottom: 3px solid var(--primary);
    backdrop-filter: blur(5px);
}

.menu-stats .stat-item {
    text-align: center;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.7);
    border-radius: 10px;
    border: 2px solid var(--primary);
    min-width: 120px;
    backdrop-filter: blur(10px);
}

/* Menu Section Styles */
.menu-section {
    background: linear-gradient(135deg, rgba(248, 249, 250, 0.9) 0%, rgba(233, 236, 239, 0.9) 100%);
    border-top: 3px solid var(--primary);
    backdrop-filter: blur(10px);
}

.menu-header {
    text-align: center;
    margin-bottom: 3rem;
}


.menu-container {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.menu-nav {
    margin-bottom: 2rem;
}

.menu-tab-btn {
    background: rgba(255, 255, 255, 0.8);
    border: 2px solid var(--primary);
    color: var(--primary);
    font-weight: 600;
    padding: 0.8rem 1.5rem;
    margin: 0 0.5rem;
    border-radius: 25px;
    transition: all 0.3s ease;
    backdrop-filter: blur(5px);
}

.menu-tab-btn:hover,
.menu-tab-btn.active {
    background: var(--primary);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(220, 20, 60, 0.3);
}

.menu-category {
    margin-bottom: 2rem;
}

.category-header {
    text-align: center;
    margin-bottom: 2rem;
    position: relative;
}

.category-title {
    font-family: 'Cinzel', serif;
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.category-decoration {
    width: 100px;
    height: 3px;
    background: linear-gradient(90deg, transparent, var(--primary), transparent);
    margin: 0 auto;
    border-radius: 2px;
}

.menu-items {
    display: grid;
    gap: 1.5rem;
}

.menu-item {
    background: rgba(255, 255, 255, 0.8);
    border: 1px solid rgba(220, 20, 60, 0.1);
    border-radius: 15px;
    padding: 1.5rem;
    transition: all 0.3s ease;
    backdrop-filter: blur(5px);
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.menu-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(220, 20, 60, 0.15);
    border-color: var(--primary);
}

.item-content {
    flex: 1;
    margin-right: 1rem;
}

.item-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.item-name {
    font-family: 'Cinzel', serif;
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--primary);
    margin: 0;
}

.item-price {
    font-family: 'Cinzel', serif;
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--accent);
    background: rgba(220, 20, 60, 0.1);
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    border: 1px solid var(--primary);
}

.item-description {
    font-family: 'Crimson Text', serif;
    font-size: 0.95rem;
    color: #666;
    line-height: 1.5;
    margin: 0;
    font-style: italic;
}

.item-actions {
    flex-shrink: 0;
}

.add-to-cart {
    background: rgba(220, 20, 60, 0.1);
    border: 2px solid var(--primary);
    color: var(--primary);
    font-weight: 600;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    transition: all 0.3s ease;
}

.add-to-cart:hover {
    background: var(--primary);
    color: white;
    transform: scale(1.05);
}

.menu-footer {
    background: rgba(248, 249, 250, 0.8);
    border-radius: 15px;
    padding: 2rem;
    border: 1px solid rgba(220, 20, 60, 0.1);
    backdrop-filter: blur(5px);
}

.menu-info h5,
.menu-contact h5 {
    font-family: 'Cinzel', serif;
    color: var(--primary);
    font-weight: 600;
    margin-bottom: 1rem;
}

.menu-info p,
.menu-contact p {
    font-family: 'Crimson Text', serif;
    color: #666;
    margin-bottom: 0.5rem;
}

/* Menu Animation */
.menu-content {
    animation: fadeInUp 0.8s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.menu-item {
    animation: slideInLeft 0.6s ease-out;
    animation-fill-mode: both;
}

.menu-item:nth-child(1) { animation-delay: 0.1s; }
.menu-item:nth-child(2) { animation-delay: 0.2s; }
.menu-item:nth-child(3) { animation-delay: 0.3s; }
.menu-item:nth-child(4) { animation-delay: 0.4s; }
.menu-item:nth-child(5) { animation-delay: 0.5s; }

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .menu-stats {
        flex-direction: column;
        gap: 1rem;
    }
    
    .menu-container {
        padding: 1rem;
    }
    
    .menu-tab-btn {
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
        margin: 0 0.2rem;
    }
    
    .menu-item {
        flex-direction: column;
        text-align: center;
    }
    
    .item-content {
        margin-right: 0;
        margin-bottom: 1rem;
    }
    
    .item-header {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .category-title {
        font-size: 1.5rem;
    }
}

@media (max-width: 480px) {
    .menu-container {
        padding: 0.5rem;
    }
    
    .menu-item {
        padding: 1rem;
    }
    
    .item-name {
        font-size: 1rem;
    }
    
    .item-price {
        font-size: 1.1rem;
    }
    
    .item-description {
        font-size: 0.85rem;
    }
    
    .category-title {
        font-size: 1.3rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ===== FUNCIONALIDAD DE LA CARTA DE MENÚ =====
    
    // Función para inicializar las pestañas del menú
    function initializeMenuTabs() {
        const menuTabs = document.querySelectorAll('.menu-tab-btn');
        
        menuTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Remover clase active de todas las pestañas
                menuTabs.forEach(t => t.classList.remove('active'));
                
                // Añadir clase active a la pestaña clickeada
                this.classList.add('active');
                
                // Obtener el target del tab
                const targetId = this.getAttribute('data-bs-target');
                const targetPane = document.querySelector(targetId);
                
                // Ocultar todos los paneles
                document.querySelectorAll('.tab-pane').forEach(pane => {
                    pane.classList.remove('show', 'active');
                });
                
                // Mostrar el panel seleccionado
                if (targetPane) {
                    targetPane.classList.add('show', 'active');
                }
            });
        });
    }
    
    // Función para mostrar información (simulación)
    function showInfo(itemName, price) {
        console.log(`Mostrando información: ${itemName} - ${price}`);
        
        // Crear notificación visual
        const notification = document.createElement('div');
        notification.className = 'cart-notification';
        notification.innerHTML = `
            <div class="notification-content">
                <i class="bi bi-info-circle-fill me-2"></i>
                <span>Información sobre: ${itemName}</span>
            </div>
        `;
        
        // Estilos para la notificación
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--primary);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(220, 20, 60, 0.3);
            z-index: 1000;
            animation: slideInRight 0.3s ease-out;
        `;
        
        document.body.appendChild(notification);
        
        // Remover la notificación después de 3 segundos
        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease-in';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
        
        // Aquí podrías integrar con tu sistema de información
        // Por ejemplo: mostrar modal con detalles del servicio
    }
    
    // Event listeners para los botones de información
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-to-cart') || e.target.closest('.add-to-cart')) {
            const button = e.target.classList.contains('add-to-cart') ? e.target : e.target.closest('.add-to-cart');
            const itemName = button.getAttribute('data-item');
            const price = button.getAttribute('data-price');
            
            if (itemName && price) {
                showInfo(itemName, price);
                
                // Efecto visual en el botón
                button.style.transform = 'scale(0.95)';
                button.innerHTML = '<i class="bi bi-check me-1"></i>Visto';
                button.style.background = 'var(--success)';
                button.style.borderColor = 'var(--success)';
                
                setTimeout(() => {
                    button.style.transform = 'scale(1)';
                    button.innerHTML = '<i class="bi bi-info-circle me-1"></i>Más Info';
                    button.style.background = '';
                    button.style.borderColor = '';
                }, 1500);
            }
        }
    });
    
    // Inicializar las pestañas del menú
    initializeMenuTabs();
    
    console.log('Sistema de menú inicializado');
});
</script>

<style>
/* Estilos adicionales para las notificaciones */
@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(100%);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideOutRight {
    from {
        opacity: 1;
        transform: translateX(0);
    }
    to {
        opacity: 0;
        transform: translateX(100%);
    }
}

.cart-notification {
    font-family: 'Crimson Text', serif;
    font-weight: 600;
}

.notification-content {
    display: flex;
    align-items: center;
}

/* Variables CSS para colores */
:root {
    --success: #28a745;
}
</style>