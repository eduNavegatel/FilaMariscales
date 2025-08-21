<?php $content = '\n';
ob_start(); // Start output buffering
?>

<!-- Hero Section -->
<section class="hero-section text-white text-center py-8 mb-8" style="background: linear-gradient(rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.1)), url('/mariscales1-php/public/assets/images/backgrounds/knight-templar-background.jpg') center/cover; backdrop-filter: blur(5px); -webkit-backdrop-filter: blur(5px);">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">Junta Directiva</h1>
        <p class="lead">Conoce al equipo que dirige la Filá Mariscales</p>
    </div>
</section>

<!-- Board Members Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center mb-5">
                <h2 class="fw-bold mb-3">Nuestra Junta Directiva 2024-2026</h2>
                <p class="lead text-muted">Un equipo comprometido con el crecimiento y la tradición de nuestra filá</p>
            </div>
        </div>
        
        <!-- President -->
        <div class="row justify-content-center mb-5">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="row g-0 align-items-center">
                        <div class="col-md-4">
                            <img src="/mariscales1-php/public/assets/images/presidente.jpg" class="img-fluid rounded-start" alt="Presidente">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h4 class="card-title fw-bold mb-1">Juan Pérez</h4>
                                        <h5 class="text-primary mb-3">Presidente</h5>
                                    </div>
                                    <div class="social-icons">
                                        <a href="#" class="text-dark me-2"><i class="bi bi-envelope-fill"></i></a>
                                        <a href="#" class="text-dark"><i class="bi bi-telephone-fill"></i></a>
                                    </div>
                                </div>
                                <p class="card-text">Con más de 15 años en la directiva, Juan lidera nuestro proyecto con pasión y dedicación, trabajando incansablemente por el crecimiento de nuestra filá.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Vice President & Secretary -->
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <img src="/mariscales1-php/public/assets/images/vicepresidente.jpg" class="rounded-circle mb-3" width="150" alt="Vicepresidente">
                        <h4 class="card-title fw-bold mb-1">María López</h4>
                        <h5 class="text-primary mb-3">Vicepresidenta</h5>
                        <p class="card-text">Apoyando la gestión y coordinando los diferentes departamentos para el correcto funcionamiento de la filá.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <img src="/mariscales1-php/public/assets/images/secretario.jpg" class="rounded-circle mb-3" width="150" alt="Secretario">
                        <h4 class="card-title fw-bold mb-1">Carlos Martínez</h4>
                        <h5 class="text-primary mb-3">Secretario</h5>
                        <p class="card-text">Encargado de la documentación, actas y comunicación oficial de la filá.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Treasurer & Members -->
        <div class="row g-4 mt-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <img src="/mariscales1-php/public/assets/images/tesorero.jpg" class="rounded-circle mb-3" width="120" alt="Tesorero">
                        <h5 class="fw-bold mb-1">Antonio García</h5>
                        <p class="text-muted mb-3">Tesorero</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <img src="/mariscales1-php/public/assets/images/vocal1.jpg" class="rounded-circle mb-3" width="120" alt="Vocal 1">
                        <h5 class="fw-bold mb-1">Laura Sánchez</h5>
                        <p class="text-muted mb-3">Vocal de Actividades</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <img src="/mariscales1-php/public/assets/images/vocal2.jpg" class="rounded-circle mb-3" width="120" alt="Vocal 2">
                        <h5 class="fw-bold mb-1">David Torres</h5>
                        <p class="text-muted mb-3">Vocal de Comunicación</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="fw-bold mb-4">¿Quieres contactar con nosotros?</h2>
                <p class="lead mb-4">Estamos a tu disposición para cualquier consulta o sugerencia</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="/mariscales1-php/public/pages/contacto" class="btn btn-primary btn-lg">Enviar mensaje</a>
                    <a href="tel:+34900123456" class="btn btn-outline-primary btn-lg">Llamar ahora</a>
                </div>
            </div>
        </div>
    </div>
</section>
