<?php
require_once __DIR__ . '/../../../src/config/config.php';
require_once __DIR__ . '/../../../src/helpers/FlipbookHelper.php';

// Inicializar el helper de flipbook
$flipbookHelper = new FlipbookHelper();

// Obtener flipbooks disponibles
$flipbooks = $flipbookHelper->getFlipbooks();

// Si no hay flipbooks, crear uno por defecto
if (empty($flipbooks)) {
    $result = $flipbookHelper->convertPdfToFlipbook('', 'filamariscales_default');
    if ($result['success']) {
        $flipbooks = $flipbookHelper->getFlipbooks();
    }
}

// Usar el primer flipbook disponible
$currentFlipbook = !empty($flipbooks) ? $flipbooks[0] : null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libro Interactivo - Filá Mariscales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Crimson+Text:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #dc143c;
            --secondary: #6c757d;
            --accent: #d4af37;
            --dark: #212529;
            --light: #f8f9fa;
        }

        body {
            font-family: 'Crimson Text', serif;
            color: var(--dark);
            background: linear-gradient(135deg, rgba(220, 20, 60, 0.05) 0%, rgba(255, 255, 255, 0.9) 50%, rgba(220, 20, 60, 0.05) 100%);
            min-height: 100vh;
        }

        .text-gradient {
            background: linear-gradient(45deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-section {
            background: linear-gradient(135deg, rgba(220, 20, 60, 0.1) 0%, rgba(255, 255, 255, 0.8) 50%, rgba(220, 20, 60, 0.1) 100%);
            border-bottom: 3px solid var(--primary);
            padding: 3rem 0;
        }

        .hero-section h1 {
            font-family: 'Cinzel', serif;
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .flipbook-container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .flipbook {
            width: 100%;
            height: 600px;
            background: #f8f9fa;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }

        .turn-viewport {
            width: 100%;
            height: 100%;
            position: relative;
        }

        .turn-pages {
            width: 100%;
            height: 100%;
            position: relative;
        }

        .page {
            width: 50%;
            height: 100%;
            position: absolute;
            background: #f4e4c1;
            border: 2px solid #d4af37;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .page:nth-child(odd) {
            left: 0;
            border-radius: 15px 0 0 15px;
            border-right: 1px solid #d4af37;
        }

        .page:nth-child(even) {
            right: 0;
            border-radius: 0 15px 15px 0;
            border-left: 1px solid #d4af37;
        }

        .page-content {
            padding: 2rem;
            height: 100%;
            overflow-y: auto;
            background: linear-gradient(135deg, #f4e4c1 0%, #f8f5e8 100%);
        }

        .page-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--accent);
        }

        .page-header h1 {
            font-family: 'Cinzel', serif;
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .page-header h2 {
            font-family: 'Cinzel', serif;
            font-size: 1.8rem;
            color: var(--accent);
            font-weight: 400;
        }

        .page-body {
            line-height: 1.8;
        }

        .page-body h2 {
            font-family: 'Cinzel', serif;
            color: var(--primary);
            font-size: 1.8rem;
            margin-bottom: 1rem;
            border-bottom: 1px solid var(--accent);
            padding-bottom: 0.5rem;
        }

        .page-body h3 {
            font-family: 'Cinzel', serif;
            color: var(--accent);
            font-size: 1.3rem;
            margin: 1.5rem 0 0.5rem 0;
        }

        .page-body p {
            margin-bottom: 1rem;
            text-align: justify;
        }

        .templar-symbol {
            font-size: 4rem;
            text-align: center;
            margin: 1rem 0;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .motto {
            font-family: 'Cinzel', serif;
            font-size: 1.5rem;
            color: var(--primary);
            text-align: center;
            font-style: italic;
            margin: 1rem 0;
        }

        .page-info {
            background: rgba(220, 20, 60, 0.1);
            padding: 1rem;
            border-radius: 10px;
            text-align: center;
            margin-top: 2rem;
        }

        .highlight-box {
            background: rgba(212, 175, 55, 0.1);
            padding: 1.5rem;
            border-radius: 10px;
            border-left: 4px solid var(--accent);
            margin: 1.5rem 0;
        }

        .tradition-item, .activity-item, .service-item {
            background: rgba(255, 255, 255, 0.7);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border-left: 3px solid var(--primary);
        }

        .contact-info {
            background: rgba(255, 255, 255, 0.8);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .photo-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin: 1rem 0;
        }

        .photo-placeholder {
            background: rgba(220, 20, 60, 0.1);
            border: 2px dashed var(--primary);
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            font-size: 2rem;
        }

        .join-info {
            background: rgba(40, 167, 69, 0.1);
            padding: 1.5rem;
            border-radius: 10px;
            margin: 1.5rem 0;
        }

        .join-info ul {
            margin: 1rem 0;
            padding-left: 1.5rem;
        }

        .join-info li {
            margin-bottom: 0.5rem;
        }

        .footer-motto {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 2px solid var(--accent);
        }

        .footer-motto p {
            margin-bottom: 0.5rem;
        }

        .flipbook-controls {
            text-align: center;
            margin: 2rem 0;
        }

        .flipbook-controls .btn {
            margin: 0 0.5rem;
            padding: 0.75rem 1.5rem;
            font-family: 'Cinzel', serif;
            font-weight: 600;
            border-radius: 25px;
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--primary), var(--accent));
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, var(--accent), var(--primary));
            transform: translateY(-2px);
        }

        .loading {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 400px;
            font-size: 1.2rem;
            color: var(--primary);
        }

        .spinner {
            border: 4px solid rgba(220, 20, 60, 0.1);
            border-left: 4px solid var(--primary);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin-right: 1rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2.5rem;
            }

            .flipbook {
                height: 500px;
            }

            .page-content {
                padding: 1rem;
            }

            .page-header h1 {
                font-size: 2rem;
            }

            .page-header h2 {
                font-size: 1.5rem;
            }

            .templar-symbol {
                font-size: 3rem;
            }

            .photo-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .flipbook {
                height: 400px;
            }

            .page-content {
                padding: 0.75rem;
            }

            .page-header h1 {
                font-size: 1.8rem;
            }

            .page-body h2 {
                font-size: 1.5rem;
            }

            .flipbook-controls .btn {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 text-center">
                    <h1 class="text-gradient">
                        <i class="bi bi-book me-3"></i>Libro Interactivo
                    </h1>
                    <p class="lead">Descubre la historia y tradiciones de la Filá Mariscales de Caballeros Templarios</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Flipbook Container -->
    <div class="flipbook-container">
        <?php if ($currentFlipbook): ?>
            <div class="flipbook" id="flipbook">
                <!-- Las páginas se cargarán dinámicamente -->
            </div>
            
            <!-- Navigation Controls -->
            <div class="flipbook-controls">
                <button class="btn btn-primary" id="prevBtn">
                    <i class="bi bi-chevron-left me-2"></i>Anterior
                </button>
                <span class="mx-3" id="pageInfo">Página 1 de <?php echo $currentFlipbook['total_pages']; ?></span>
                <button class="btn btn-primary" id="nextBtn">
                    Siguiente<i class="bi bi-chevron-right ms-2"></i>
                </button>
            </div>
        <?php else: ?>
            <div class="loading">
                <div class="spinner"></div>
                Cargando flipbook...
            </div>
        <?php endif; ?>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/flipbook/turn.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($currentFlipbook): ?>
                // Cargar páginas del flipbook
                loadFlipbookPages();
                
                // Inicializar turn.js
                const flipbook = turn({
                    container: '#flipbook',
                    pages: <?php echo $currentFlipbook['total_pages']; ?>,
                    pageWidth: 500,
                    pageHeight: 600,
                    autoCenter: true,
                    gradients: true,
                    acceleration: true,
                    elevation: 50,
                    when: {
                        turning: function(event, page, view) {
                            updatePageInfo(page);
                        },
                        turned: function(event, page, view) {
                            updatePageInfo(page);
                        }
                    }
                });
                
                // Event listeners para controles
                document.getElementById('prevBtn').addEventListener('click', function() {
                    flipbook.turn('previous');
                });
                
                document.getElementById('nextBtn').addEventListener('click', function() {
                    flipbook.turn('next');
                });
                
                // Función para cargar páginas
                function loadFlipbookPages() {
                    const flipbookElement = document.getElementById('flipbook');
                    
                    // Cargar páginas desde el servidor
                    for (let i = 1; i <= <?php echo $currentFlipbook['total_pages']; ?>; i++) {
                        fetch(`/prueba-php/public/serve-flipbook-page.php?flipbook=<?php echo $currentFlipbook['name']; ?>&page=${i}`)
                            .then(response => response.text())
                            .then(html => {
                                const pageElement = document.createElement('div');
                                pageElement.className = 'page';
                                pageElement.innerHTML = html;
                                flipbookElement.appendChild(pageElement);
                            })
                            .catch(error => {
                                console.error('Error loading page:', error);
                            });
                    }
                }
                
                // Función para actualizar información de página
                function updatePageInfo(page) {
                    const pageInfo = document.getElementById('pageInfo');
                    pageInfo.textContent = `Página ${page} de <?php echo $currentFlipbook['total_pages']; ?>`;
                }
            <?php endif; ?>
        });
    </script>
</body>
</html>
