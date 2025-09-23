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
                    
                <!-- Libro Interactivo -->
                <div class="book-container" id="bookContainer">
                    <!-- Portada del Libro -->
                        <div class="book-cover" id="bookCover">
                                <div class="cover-content">
                            <h1 class="book-title">Filá Mariscales</h1>
                            <h2 class="book-subtitle">Caballeros Templarios</h2>
                                    <div class="cover-decoration">
                                        <div class="templar-cross">⚔️</div>
                                    </div>
                            <p class="cover-description">Tradición, Honor y Hermandad</p>
                                    </div>
                        <div class="page-corner" id="pageCorner"></div>
                                </div>
                    
                    <!-- Páginas del Libro -->
                    <div class="book-pages" id="bookPages">
                        <!-- Página 1: Tradiciones (Hoja Izquierda) -->
                        <div class="book-page page-left page-1" id="page1">
                            <div class="page-content">
                                <h2 class="page-title">Tradiciones</h2>
                                <div class="page-items">
                                    <?php foreach ($carta_menu['tradiciones']['platos'] as $plato): ?>
                                    <div class="page-item">
                                        <h3 class="item-title"><?php echo $plato['nombre']; ?></h3>
                                        <p class="item-desc"><?php echo $plato['descripcion']; ?></p>
                            </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                                    </div>
                        
                        <!-- Página 2: Actividades (Hoja Derecha) -->
                        <div class="book-page page-right page-2" id="page2">
                            <div class="page-content">
                                <h2 class="page-title">Actividades</h2>
                                <div class="page-items">
                                    <?php foreach ($carta_menu['actividades']['platos'] as $plato): ?>
                                    <div class="page-item">
                                        <h3 class="item-title"><?php echo $plato['nombre']; ?></h3>
                                        <p class="item-desc"><?php echo $plato['descripcion']; ?></p>
                                    </div>
                                    <?php endforeach; ?>
                                    </div>
                                <div class="page-corner next-page" data-page="3">→</div>
                            </div>
                        </div>
                        
                        <!-- Página 3: Servicios (Hoja Izquierda) -->
                        <div class="book-page page-left page-3" id="page3">
                                <div class="page-content">
                                <h2 class="page-title">Servicios</h2>
                                <div class="page-items">
                                    <?php foreach ($carta_menu['servicios']['platos'] as $plato): ?>
                                    <div class="page-item">
                                        <h3 class="item-title"><?php echo $plato['nombre']; ?></h3>
                                        <p class="item-desc"><?php echo $plato['descripcion']; ?></p>
                                    </div>
                                    <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            
                        <!-- Página 4: Información (Hoja Derecha) -->
                        <div class="book-page page-right page-4" id="page4">
                                <div class="page-content">
                                <h2 class="page-title">Información</h2>
                                <div class="page-items">
                                    <?php foreach ($carta_menu['informacion']['platos'] as $plato): ?>
                                    <div class="page-item">
                                        <h3 class="item-title"><?php echo $plato['nombre']; ?></h3>
                                        <p class="item-desc"><?php echo $plato['descripcion']; ?></p>
                                    </div>
                                    <?php endforeach; ?>
                                                </div>
                                <div class="page-corner prev-page" data-page="1">←</div>
                                <div class="book-footer">
                                    <p class="footer-text">Muchas gracias</p>
                                    <p class="footer-subtitle">Por su interés en la Filá Mariscales</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
    background: transparent;
    padding: 0;
    box-shadow: none;
    backdrop-filter: none;
    border: none;
}

/* Libro Interactivo */
.book-container {
    position: relative;
    width: 100%;
    max-width: 600px;
    height: 450px;
    margin: 0 auto;
    perspective: 1200px;
    transform-style: preserve-3d;
}

/* Portada del Libro */
.book-cover {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #8B4513 0%, #A0522D 50%, #8B4513 100%);
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    transform-origin: left center;
    transition: transform 1.2s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 10;
    cursor: pointer;
    border: 3px solid #654321;
}

.book-cover:hover {
    transform: rotateY(-15deg);
}

.book-cover.open {
    transform: rotateY(-180deg);
}

.cover-content {
    padding: 3rem;
    text-align: center;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: #f4e4c1;
    position: relative;
}

.book-title {
    font-family: 'Cinzel', serif;
    font-size: 3rem;
    font-weight: 700;
    color: #d4af37;
    margin-bottom: 0.5rem;
    text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.7);
}

.book-subtitle {
    font-family: 'Cinzel', serif;
    font-size: 1.5rem;
    color: #f4e4c1;
    margin-bottom: 2rem;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
}

.cover-decoration {
    margin: 2rem 0;
}

.templar-cross {
    font-size: 4rem;
    color: #d4af37;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
}

.cover-description {
    font-family: 'Crimson Text', serif;
    font-size: 1.2rem;
    color: #d4af37;
    font-style: italic;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
}

/* Esquina de la página */
.page-corner {
    position: absolute;
    bottom: 20px;
    right: 20px;
    width: 60px;
    height: 60px;
    background: linear-gradient(45deg, #f4e4c1 0%, #d4af37 100%);
    border-radius: 0 0 0 100%;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #8B4513;
    font-weight: bold;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}

.page-corner:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
}

/* Páginas del Libro */
.book-pages {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    transform-style: preserve-3d;
}

.book-page {
    position: absolute;
    top: 0;
    width: 50%;
    height: 100%;
    background: #f4e4c1;
    border-radius: 10px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
    transform-origin: left center;
    transition: transform 1.5s cubic-bezier(0.4, 0, 0.2, 1);
    display: none;
    border: 2px solid #d4af37;
    overflow: hidden;
}

/* Hoja izquierda */
.book-page.page-left {
    left: 0;
    border-radius: 10px 0 0 10px;
    border-right: 1px solid #d4af37;
}

/* Hoja derecha */
.book-page.page-right {
    right: 0;
    border-radius: 0 10px 10px 0;
    border-left: 1px solid #d4af37;
}

.book-page.active {
    display: block;
}

.book-page.flip {
    transform: rotateY(-180deg);
}

.page-content {
    padding: 2rem;
    height: 100%;
    overflow-y: auto;
    color: #8B4513;
}

.page-title {
    font-family: 'Cinzel', serif;
    font-size: 2.5rem;
    color: #8B4513;
    text-align: center;
    margin-bottom: 2rem;
    border-bottom: 2px solid #d4af37;
    padding-bottom: 1rem;
}

.page-items {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.page-item {
    background: rgba(212, 175, 55, 0.1);
    border: 1px solid rgba(212, 175, 55, 0.3);
    border-radius: 8px;
    padding: 1rem;
    transition: all 0.3s ease;
}

.page-item:hover {
    background: rgba(212, 175, 55, 0.2);
    transform: translateX(5px);
}

.item-title {
    font-family: 'Cinzel', serif;
    font-size: 1.3rem;
    color: #8B4513;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.item-desc {
    font-family: 'Crimson Text', serif;
    font-size: 1rem;
    color: #654321;
    line-height: 1.5;
    margin: 0;
    font-style: italic;
}

/* Esquinas de navegación */
.next-page {
    bottom: 20px;
    right: 20px;
}

.prev-page {
    bottom: 20px;
    left: 20px;
    border-radius: 0 0 100% 0;
}

/* Footer del libro */
.book-footer {
    text-align: center;
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 2px solid #d4af37;
}

.footer-text {
    font-family: 'Cinzel', serif;
    font-size: 2rem;
    color: #8B4513;
    margin-bottom: 0.5rem;
    font-style: italic;
}

.footer-subtitle {
    font-family: 'Crimson Text', serif;
    font-size: 1.1rem;
    color: #654321;
    font-style: italic;
}

/* Responsive Design para el Libro */
@media (max-width: 768px) {
    .book-container {
        height: 400px;
        max-width: 90%;
    }
    
    .book-title {
        font-size: 1.8rem;
    }
    
    .book-subtitle {
        font-size: 1rem;
    }
    
    .templar-cross {
        font-size: 2.5rem;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
    
    .item-title {
        font-size: 1rem;
    }
    
    .item-desc {
        font-size: 0.85rem;
    }
    
    .page-corner {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    
    .page-content {
        padding: 1rem;
    }
}

@media (max-width: 480px) {
    .book-container {
        height: 350px;
        max-width: 95%;
    }
    
    .book-title {
        font-size: 1.5rem;
    }
    
    .book-subtitle {
        font-size: 0.9rem;
    }
    
    .templar-cross {
        font-size: 2rem;
    }
    
    .page-title {
        font-size: 1.2rem;
    }
    
    .item-title {
        font-size: 0.9rem;
    }
    
    .item-desc {
        font-size: 0.8rem;
    }
    
    .page-corner {
        width: 35px;
        height: 35px;
        font-size: 0.9rem;
    }
    
    .page-content {
        padding: 0.8rem;
    }
}

.menu-nav {
    margin-bottom: 2rem;
}

/* Responsive Design for Carta */
@media (max-width: 768px) {
    .carta-container {
        padding: 2rem 1.5rem;
        margin: 0 1rem;
    }
    
    .carta-title {
        font-size: 2rem;
    }
    
    .section-title {
        font-size: 1.5rem;
    }
    
    .item-name {
        font-size: 1rem;
    }
    
    .item-description {
        font-size: 0.85rem;
    }
    
    .footer-text {
        font-size: 1.5rem;
    }
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
    // ===== FUNCIONALIDAD DEL LIBRO INTERACTIVO =====
    
    // Variables del libro
    let isBookOpen = false;
    let currentSpread = 1; // Cada "spread" tiene 2 páginas (izquierda y derecha)
    const totalSpreads = 2; // 2 spreads: (1,2) y (3,4)
    
    // Elementos del DOM
    const bookCover = document.getElementById('bookCover');
    const bookPages = document.getElementById('bookPages');
    const pageCorner = document.getElementById('pageCorner');
    
    // Función para abrir/cerrar el libro
    function toggleBook() {
        if (!isBookOpen) {
            // Abrir el libro
            bookCover.classList.add('open');
        setTimeout(() => {
            bookCover.style.display = 'none';
                showSpread(1);
            }, 600);
            isBookOpen = true;
        } else {
            // Cerrar el libro
            bookCover.style.display = 'block';
            bookCover.classList.remove('open');
            hideAllPages();
            isBookOpen = false;
            currentSpread = 1;
        }
    }
    
    // Función para mostrar un spread específico (dos páginas)
    function showSpread(spreadNumber) {
        hideAllPages();
        
        if (spreadNumber === 1) {
            // Mostrar páginas 1 y 2
            document.getElementById('page1').classList.add('active');
            document.getElementById('page2').classList.add('active');
        } else if (spreadNumber === 2) {
            // Mostrar páginas 3 y 4
            document.getElementById('page3').classList.add('active');
            document.getElementById('page4').classList.add('active');
        }
        
        currentSpread = spreadNumber;
    }
    
    // Función para ocultar todas las páginas
    function hideAllPages() {
        for (let i = 1; i <= 4; i++) {
            const page = document.getElementById(`page${i}`);
            if (page) {
                page.classList.remove('active');
            }
        }
    }
    
    // Función para ir a la siguiente página
    function nextPage() {
        if (currentSpread < totalSpreads) {
            // Voltear páginas actuales
            const leftPage = document.getElementById(`page${currentSpread * 2 - 1}`);
            const rightPage = document.getElementById(`page${currentSpread * 2}`);
            
            if (leftPage && rightPage) {
                leftPage.classList.add('flip');
                rightPage.classList.add('flip');
                
                setTimeout(() => {
                    leftPage.classList.remove('active', 'flip');
                    rightPage.classList.remove('active', 'flip');
                    showSpread(currentSpread + 1);
                }, 750);
            }
        }
    }
    
    // Función para ir a la página anterior
    function prevPage() {
        if (currentSpread > 1) {
            // Voltear páginas actuales
            const leftPage = document.getElementById(`page${currentSpread * 2 - 1}`);
            const rightPage = document.getElementById(`page${currentSpread * 2}`);
            
            if (leftPage && rightPage) {
                leftPage.classList.add('flip');
                rightPage.classList.add('flip');
                
                setTimeout(() => {
                    leftPage.classList.remove('active', 'flip');
                    rightPage.classList.remove('active', 'flip');
                    showSpread(currentSpread - 1);
                }, 750);
            }
        }
    }
    
    // Event listeners
    bookCover.addEventListener('click', toggleBook);
    pageCorner.addEventListener('click', toggleBook);
    
    // Event listeners para las esquinas de navegación
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('next-page')) {
            e.preventDefault();
            nextPage();
        } else if (e.target.classList.contains('prev-page')) {
            e.preventDefault();
            prevPage();
        }
    });
    
    // Efecto hover en la portada
    bookCover.addEventListener('mouseenter', function() {
        if (!isBookOpen) {
            this.style.transform = 'rotateY(-15deg)';
        }
    });
    
    bookCover.addEventListener('mouseleave', function() {
        if (!isBookOpen) {
            this.style.transform = 'rotateY(0deg)';
        }
    });
    
    // Efecto hover en las esquinas de las páginas
    document.addEventListener('mouseenter', function(e) {
        if (e.target.classList.contains('page-corner')) {
            e.target.style.transform = 'scale(1.1)';
        }
    }, true);
    
    document.addEventListener('mouseleave', function(e) {
        if (e.target.classList.contains('page-corner')) {
            e.target.style.transform = 'scale(1)';
        }
    }, true);
    
    console.log('Libro interactivo inicializado');
    
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
    
    console.log('Libro interactivo inicializado');
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