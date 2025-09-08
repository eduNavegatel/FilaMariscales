<?php
// Página interactiva con elementos dinámicos
?>

<!-- Hero Section Interactivo -->
<section class="hero-interactive py-5 particles">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center scroll-reveal">
                <h1 class="display-3 fw-bold text-gradient mb-4 animate-fadeInDown">
                    <i class="bi bi-magic me-3 animate-float"></i>
                    <span class="text-shimmer">Zona Interactiva</span>
                </h1>
                <p class="lead mb-5 animate-fadeInUp">
                    Descubre la tradición templaria de forma interactiva
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Sección de Contadores Animados -->
<section class="counters-section py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 mb-4">
                <div class="counter-card card-animated hover-lift">
                    <div class="counter-icon animate-bounce">
                        <i class="bi bi-calendar-event fs-1 text-primary"></i>
                    </div>
                    <h3 class="counter text-gradient" data-target="50">0</h3>
                    <p class="text-muted">Años de Tradición</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="counter-card card-animated hover-lift">
                    <div class="counter-icon animate-bounce">
                        <i class="bi bi-people-fill fs-1 text-success"></i>
                    </div>
                    <h3 class="counter text-gradient" data-target="150">0</h3>
                    <p class="text-muted">Miembros Activos</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="counter-card card-animated hover-lift">
                    <div class="counter-icon animate-bounce">
                        <i class="bi bi-trophy-fill fs-1 text-warning"></i>
                    </div>
                    <h3 class="counter text-gradient" data-target="25">0</h3>
                    <p class="text-muted">Premios Obtenidos</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="counter-card card-animated hover-lift">
                    <div class="counter-icon animate-bounce">
                        <i class="bi bi-heart-fill fs-1 text-danger"></i>
                    </div>
                    <h3 class="counter text-gradient" data-target="1000">0</h3>
                    <p class="text-muted">Seguidores</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección de Tarjetas Interactivas -->
<section class="interactive-cards py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5 scroll-reveal">
                <h2 class="display-5 fw-bold text-gradient animate-fadeInUp">
                    <i class="bi bi-stars me-3 animate-glow"></i>
                    Explora Nuestra Historia
                </h2>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="interactive-card card-tilt hover-lift scroll-reveal">
                    <div class="card-header bg-primary text-white">
                        <i class="bi bi-shield-fill fs-1 animate-float"></i>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Orígenes Templarios</h5>
                        <p class="card-text">Descubre cómo comenzó nuestra tradición en 1975 y los valores que nos guían.</p>
                        <button class="btn btn-primary btn-animated" onclick="showModal('templarios')">
                            <i class="bi bi-eye me-2"></i>Explorar
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="interactive-card card-tilt hover-lift scroll-reveal">
                    <div class="card-header bg-success text-white">
                        <i class="bi bi-music-note-beamed fs-1 animate-float"></i>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Bandas de Música</h5>
                        <p class="card-text">Conoce nuestras bandas y la música que nos acompaña en cada desfile.</p>
                        <button class="btn btn-success btn-animated" onclick="showModal('bandas')">
                            <i class="bi bi-play-circle me-2"></i>Escuchar
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="interactive-card card-tilt hover-lift scroll-reveal">
                    <div class="card-header bg-warning text-white">
                        <i class="bi bi-calendar-event fs-1 animate-float"></i>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Eventos Anuales</h5>
                        <p class="card-text">Participa en nuestros eventos y actividades durante todo el año.</p>
                        <button class="btn btn-warning btn-animated" onclick="showModal('eventos')">
                            <i class="bi bi-calendar-check me-2"></i>Ver Eventos
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección de Timeline Interactivo -->
<section class="timeline-section py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5 scroll-reveal">
                <h2 class="display-5 fw-bold text-gradient animate-fadeInUp">
                    <i class="bi bi-clock-history me-3 animate-pulse"></i>
                    Nuestra Historia
                </h2>
            </div>
        </div>
        
        <div class="timeline">
            <div class="timeline-item scroll-reveal">
                <div class="timeline-marker animate-glow"></div>
                <div class="timeline-content card-animated">
                    <h5>1975 - Fundación</h5>
                    <p>Se funda la Filá Mariscales de Caballeros Templarios en Elche.</p>
                </div>
            </div>
            
            <div class="timeline-item scroll-reveal">
                <div class="timeline-marker animate-glow"></div>
                <div class="timeline-content card-animated">
                    <h5>1980 - Primer Desfile</h5>
                    <p>Participamos por primera vez en las Fiestas de Moros y Cristianos.</p>
                </div>
            </div>
            
            <div class="timeline-item scroll-reveal">
                <div class="timeline-marker animate-glow"></div>
                <div class="timeline-content card-animated">
                    <h5>1995 - Expansión</h5>
                    <p>Ampliamos nuestras actividades y creamos nuevas secciones.</p>
                </div>
            </div>
            
            <div class="timeline-item scroll-reveal">
                <div class="timeline-marker animate-glow"></div>
                <div class="timeline-content card-animated">
                    <h5>2020 - Modernización</h5>
                    <p>Incorporamos nuevas tecnologías y mejoramos nuestra presencia digital.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección de Juego Interactivo -->
<section class="game-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5 scroll-reveal">
                <h2 class="display-5 fw-bold text-gradient animate-fadeInUp">
                    <i class="bi bi-controller me-3 animate-bounce"></i>
                    Juego de Conocimiento
                </h2>
                <p class="lead">Pon a prueba tus conocimientos sobre la Filá Mariscales</p>
            </div>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="quiz-container card-animated scroll-reveal">
                    <div class="quiz-header">
                        <h4>¿Cuánto sabes sobre nosotros?</h4>
                        <div class="progress mb-3">
                            <div class="progress-bar animate-pulse" role="progressbar" style="width: 0%"></div>
                        </div>
                    </div>
                    
                    <div class="quiz-content">
                        <div class="question" id="question1">
                            <h5>¿En qué año se fundó la Filá Mariscales?</h5>
                            <div class="answers">
                                <button class="btn btn-outline-primary answer-btn" data-correct="true">1975</button>
                                <button class="btn btn-outline-primary answer-btn" data-correct="false">1980</button>
                                <button class="btn btn-outline-primary answer-btn" data-correct="false">1970</button>
                                <button class="btn btn-outline-primary answer-btn" data-correct="false">1985</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="quiz-results" style="display: none;">
                        <h4 class="text-center">¡Resultados!</h4>
                        <div class="score-display">
                            <h2 class="text-gradient counter" data-target="0">0</h2>
                            <p>puntos obtenidos</p>
                        </div>
                        <button class="btn btn-primary btn-animated" onclick="restartQuiz()">
                            <i class="bi bi-arrow-clockwise me-2"></i>Jugar de Nuevo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modales Interactivos -->
<div class="modal fade" id="interactiveModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalContent">
                <!-- Contenido dinámico -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Estilos específicos para la página interactiva */
.counter-card {
    padding: 2rem;
    border-radius: 15px;
    text-align: center;
    transition: all 0.3s ease;
}

.counter-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}

.interactive-card {
    height: 100%;
    border: none;
    border-radius: 15px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.interactive-card .card-header {
    padding: 2rem;
    text-align: center;
    border: none;
}

.timeline {
    position: relative;
    padding: 2rem 0;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 50%;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, #dc143c, #8b0000);
    transform: translateX(-50%);
}

.timeline-item {
    position: relative;
    margin-bottom: 3rem;
    width: 50%;
}

.timeline-item:nth-child(odd) {
    left: 0;
    padding-right: 3rem;
}

.timeline-item:nth-child(even) {
    left: 50%;
    padding-left: 3rem;
}

.timeline-marker {
    position: absolute;
    width: 20px;
    height: 20px;
    background: #dc143c;
    border-radius: 50%;
    top: 0;
    border: 4px solid white;
    box-shadow: 0 0 0 4px #dc143c;
}

.timeline-item:nth-child(odd) .timeline-marker {
    right: -10px;
}

.timeline-item:nth-child(even) .timeline-marker {
    left: -10px;
}

.timeline-content {
    padding: 1.5rem;
    border-radius: 10px;
    background: white;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.quiz-container {
    padding: 2rem;
    border-radius: 15px;
    background: white;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.answers {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.answer-btn {
    padding: 1rem;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.answer-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.answer-btn.correct {
    background: #28a745;
    color: white;
    border-color: #28a745;
}

.answer-btn.incorrect {
    background: #dc3545;
    color: white;
    border-color: #dc3545;
}

.score-display {
    text-align: center;
    padding: 2rem;
}

@media (max-width: 768px) {
    .timeline::before {
        left: 20px;
    }
    
    .timeline-item {
        width: 100%;
        left: 0 !important;
        padding-left: 3rem !important;
        padding-right: 0 !important;
    }
    
    .timeline-marker {
        left: 10px !important;
    }
}
</style>

<script>
// Funciones para la página interactiva
function showModal(type) {
    const modal = new bootstrap.Modal(document.getElementById('interactiveModal'));
    const title = document.getElementById('modalTitle');
    const content = document.getElementById('modalContent');
    
    switch(type) {
        case 'templarios':
            title.textContent = 'Orígenes Templarios';
            content.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <img src="https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                             class="img-fluid rounded" alt="Templarios">
                    </div>
                    <div class="col-md-6">
                        <h6>Nuestra Historia</h6>
                        <p>La Filá Mariscales de Caballeros Templarios fue fundada en 1975, inspirada en la noble tradición de los caballeros templarios medievales.</p>
                        <ul>
                            <li>Fundación: 1975</li>
                            <li>Valores: Honor, Lealtad, Tradición</li>
                            <li>Colores: Rojo y Negro</li>
                            <li>Símbolo: Cruz Templaria</li>
                        </ul>
                    </div>
                </div>
            `;
            break;
        case 'bandas':
            title.textContent = 'Bandas de Música';
            content.innerHTML = `
                <div class="text-center">
                    <i class="bi bi-music-note-beamed fs-1 text-primary mb-3"></i>
                    <h6>Nuestras Bandas</h6>
                    <p>Contamos con varias bandas de música que nos acompañan en todos nuestros desfiles y eventos.</p>
                    <div class="row">
                        <div class="col-md-4">
                            <h6>Banda Principal</h6>
                            <p>La banda principal de la filá</p>
                        </div>
                        <div class="col-md-4">
                            <h6>Banda Juvenil</h6>
                            <p>Formada por nuestros miembros más jóvenes</p>
                        </div>
                        <div class="col-md-4">
                            <h6>Banda de Tambores</h6>
                            <p>Especializada en percusión</p>
                        </div>
                    </div>
                </div>
            `;
            break;
        case 'eventos':
            title.textContent = 'Eventos Anuales';
            content.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Eventos Principales</h6>
                        <ul>
                            <li>Fiestas de Moros y Cristianos (Abril)</li>
                            <li>Cena Anual de Hermandad (Marzo)</li>
                            <li>Ensayos Generales (Febrero)</li>
                            <li>Celebración de San Jorge (Abril)</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Actividades Regulares</h6>
                        <ul>
                            <li>Ensayos semanales</li>
                            <li>Reuniones de directiva</li>
                            <li>Actividades sociales</li>
                            <li>Eventos benéficos</li>
                        </ul>
                    </div>
                </div>
            `;
            break;
    }
    
    modal.show();
}

// Funciones del quiz
let currentQuestion = 1;
let score = 0;

function checkAnswer(button) {
    const isCorrect = button.getAttribute('data-correct') === 'true';
    
    // Deshabilitar todos los botones
    document.querySelectorAll('.answer-btn').forEach(btn => {
        btn.disabled = true;
        if (btn.getAttribute('data-correct') === 'true') {
            btn.classList.add('correct');
        } else if (btn === button && !isCorrect) {
            btn.classList.add('incorrect');
        }
    });
    
    if (isCorrect) {
        score += 10;
        showNotification('¡Correcto! +10 puntos', 'success');
    } else {
        showNotification('Incorrecto. La respuesta correcta era 1975', 'error');
    }
    
    // Actualizar progreso
    const progressBar = document.querySelector('.progress-bar');
    progressBar.style.width = '100%';
    
    // Mostrar resultados después de un delay
    setTimeout(() => {
        showResults();
    }, 2000);
}

function showResults() {
    document.querySelector('.quiz-content').style.display = 'none';
    document.querySelector('.quiz-results').style.display = 'block';
    
    // Animar contador de puntuación
    const scoreElement = document.querySelector('.score-display .counter');
    scoreElement.setAttribute('data-target', score);
    
    // Iniciar animación del contador
    animateCounter(scoreElement);
}

function restartQuiz() {
    currentQuestion = 1;
    score = 0;
    
    document.querySelector('.quiz-content').style.display = 'block';
    document.querySelector('.quiz-results').style.display = 'none';
    
    // Resetear botones
    document.querySelectorAll('.answer-btn').forEach(btn => {
        btn.disabled = false;
        btn.classList.remove('correct', 'incorrect');
    });
    
    // Resetear progreso
    document.querySelector('.progress-bar').style.width = '0%';
}

// Agregar event listeners a los botones de respuesta
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.answer-btn').forEach(button => {
        button.addEventListener('click', function() {
            checkAnswer(this);
        });
    });
});
</script>
