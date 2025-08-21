<?php $content = '\n';
ob_start(); // Start output buffering
?>

<!-- Hero Section -->
<section class="hero-section position-relative overflow-hidden bg-primary text-white py-8 py-lg-10 mb-6">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: url('/mariscales1-php/public/assets/images/pattern-dots-light.svg') repeat; opacity: 0.1;"></div>
    <div class="position-absolute bottom-0 end-0">
        <div class="position-relative" style="width: 300px; height: 300px;">
            <div class="position-absolute bottom-0 end-0 w-100 h-100 bg-white bg-opacity-10 rounded-circle" style="animation: pulse 8s ease-in-out infinite;"></div>
            <div class="position-absolute bottom-0 end-0 w-100 h-100 bg-white bg-opacity-5 rounded-circle" style="animation: pulse 8s ease-in-out 2s infinite;"></div>
        </div>
    </div>
    <div class="container position-relative">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center" data-aos="fade-up">
                <span class="badge bg-white bg-opacity-10 text-white px-4 py-2 rounded-pill mb-4 d-inline-flex align-items-center">
                    <i class="bi bi-newspaper me-2"></i>
                    <span>Últimas noticias</span>
                </span>
                <h1 class="display-3 fw-bold mb-3">Noticias y <span class="text-warning">Actualidad</span></h1>
                <p class="lead mb-0 mx-auto" style="max-width: 700px; opacity: 0.9;">Mantente informado sobre las últimas novedades, eventos y actividades de la Filá Mariscales de Caballeros Templarios de Elche.</p>
            </div>
        </div>
    </div>
    <div class="position-absolute bottom-0 start-0 w-100">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120" fill="white">
            <path d="M0,64L80,69.3C160,75,320,85,480,80C640,75,800,53,960,48C1120,43,1280,53,1360,58.7L1440,64L1440,120L1360,120C1280,120,1120,120,960,120C800,120,640,120,480,120C320,120,160,120,80,120L0,120Z"></path>
        </svg>
    </div>
</section>

<!-- News Section -->
<section class="py-6 py-lg-8 position-relative">
    <div class="container position-relative">
        <div class="row g-4">
            <!-- News Item 1 -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="0">
                <article class="card h-100 border-0 shadow-sm hover-lift overflow-hidden">
                    <div class="position-relative overflow-hidden" style="height: 200px;">
                        <img src="/mariscales1-php/public/assets/images/noticia1.jpg" class="card-img-top h-100 w-100" alt="Noticia 1" style="object-fit: cover;">
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge bg-primary bg-opacity-90 px-3 py-2">Novedad</span>
                        </div>
                        <div class="position-absolute bottom-0 start-0 w-100 p-3 bg-gradient-dark-top">
                            <small class="text-white-50"><i class="bi bi-calendar3 me-1"></i> 31/07/2025</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <h3 class="h5 card-title fw-bold mb-3">Preparativos para las Fiestas 2025</h3>
                        <p class="card-text text-muted mb-4">La Filá Mariscales ya está trabajando en los preparativos para las próximas fiestas de Moros y Cristianos 2025. Conoce todos los detalles de lo que se está preparando.</p>
                        <div class="d-flex align-items-center justify-content-between">
                            <a href="#" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                <span>Leer más</span>
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                            <small class="text-muted"><i class="bi bi-clock me-1"></i> 5 min lectura</small>
                        </div>
                    </div>
                </article>
            </div>
            
            <!-- News Item 2 -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <article class="card h-100 border-0 shadow-sm hover-lift overflow-hidden">
                    <div class="position-relative overflow-hidden" style="height: 200px;">
                        <img src="/mariscales1-php/public/assets/images/noticia2.jpg" class="card-img-top h-100 w-100" alt="Noticia 2" style="object-fit: cover;">
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge bg-success bg-opacity-90 px-3 py-2">Evento</span>
                        </div>
                        <div class="position-absolute bottom-0 start-0 w-100 p-3 bg-gradient-dark-top">
                            <small class="text-white-50"><i class="bi bi-calendar3 me-1"></i> 25/07/2025</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <h3 class="h5 card-title fw-bold mb-3">Éxito en la Cena de Hermandad</h3>
                        <p class="card-text text-muted mb-4">Más de 200 personas disfrutaron de la tradicional cena de hermandad celebrada el pasado fin de semana en un ambiente festivo y familiar.</p>
                        <div class="d-flex align-items-center justify-content-between">
                            <a href="#" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                <span>Leer más</span>
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                            <small class="text-muted"><i class="bi bi-clock me-1"></i> 4 min lectura</small>
                        </div>
                    </div>
                </article>
            </div>
            
            <!-- News Item 3 -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <article class="card h-100 border-0 shadow-sm hover-lift overflow-hidden">
                    <div class="position-relative overflow-hidden" style="height: 200px;">
                        <img src="/mariscales1-php/public/assets/images/noticia3.jpg" class="card-img-top h-100 w-100" alt="Noticia 3" style="object-fit: cover;">
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge bg-info bg-opacity-90 px-3 py-2">Actualidad</span>
                        </div>
                        <div class="position-absolute bottom-0 start-0 w-100 p-3 bg-gradient-dark-top">
                            <small class="text-white-50"><i class="bi bi-calendar3 me-1"></i> 15/07/2025</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <h3 class="h5 card-title fw-bold mb-3">Nuevos Proyectos para la Sede Social</h3>
                        <p class="card-text text-muted mb-4">Presentamos las mejoras que se implementarán en nuestra sede social durante los próximos meses para ofrecer mejores instalaciones a todos nuestros socios.</p>
                        <div class="d-flex align-items-center justify-content-between">
                            <a href="#" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                <span>Leer más</span>
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                            <small class="text-muted"><i class="bi bi-clock me-1"></i> 6 min lectura</small>
                        </div>
                    </div>
                </article>
            </div>
        </div>
        
        <!-- Pagination -->
        <nav aria-label="Page navigation" class="mt-6" data-aos="fade-up">
            <ul class="pagination justify-content-center">
                <li class="page-item disabled">
                    <a class="page-link rounded-pill me-2" href="#" tabindex="-1" aria-disabled="true">
                        <i class="bi bi-chevron-left me-1"></i> Anterior
                    </a>
                </li>
                <li class="page-item d-none d-sm-block">
                    <a class="page-link rounded-circle mx-1" href="#">1</a>
                </li>
                <li class="page-item active d-none d-sm-block" aria-current="page">
                    <a class="page-link rounded-circle mx-1" href="#">2</a>
                </li>
                <li class="page-item d-none d-sm-block">
                    <a class="page-link rounded-circle mx-1" href="#">3</a>
                </li>
                <li class="page-item disabled d-none d-sm-block">
                    <span class="page-link rounded-circle mx-1">...</span>
                </li>
                <li class="page-item d-none d-sm-block">
                    <a class="page-link rounded-circle mx-1" href="#">8</a>
                </li>
                <li class="page-item">
                    <a class="page-link rounded-pill ms-2" href="#">
                        Siguiente <i class="bi bi-chevron-right ms-1"></i>
                    </a>
                </li>
            </ul>
            <div class="text-center mt-3">
                <small class="text-muted">Página 2 de 8</small>
            </div>
        </nav>
    </div>
    
    <!-- Background Elements -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="z-index: -1;">
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: url('/mariscales1-php/public/assets/images/pattern-dots-light.svg') repeat; opacity: 0.05;"></div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="py-7 py-lg-9 position-relative overflow-hidden bg-light">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: url('/mariscales1-php/public/assets/images/pattern-dots.svg') repeat; opacity: 0.03;"></div>
    <div class="position-absolute bottom-0 end-0">
        <div class="position-relative" style="width: 300px; height: 300px;">
            <div class="position-absolute bottom-0 end-0 w-100 h-100 bg-primary bg-opacity-10 rounded-circle" style="animation: pulse 8s ease-in-out infinite;"></div>
            <div class="position-absolute bottom-0 end-0 w-100 h-100 bg-primary bg-opacity-05 rounded-circle" style="animation: pulse 8s ease-in-out 2s infinite;"></div>
        </div>
    </div>
    
    <div class="container position-relative">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center" data-aos="fade-up">
                <span class="badge bg-primary bg-opacity-10 text-primary px-4 py-2 rounded-pill mb-4 d-inline-flex align-items-center">
                    <i class="bi bi-envelope-paper-heart me-2"></i>
                    <span>Mantente informado</span>
                </span>
                <h2 class="display-5 fw-bold mb-4">Suscríbete a nuestro <span class="text-gradient">boletín</span></h2>
                <p class="lead text-muted mb-5 mx-auto" style="max-width: 600px;">Recibe las últimas noticias, eventos y actualizaciones directamente en tu correo electrónico. ¡No te pierdas nada de lo que ocurre en la Filá Mariscales!</p>
                
                <form class="row g-3 justify-content-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="col-md-8">
                        <div class="input-group input-group-lg shadow-sm">
                            <span class="input-group-text bg-white border-end-0" id="email-addon">
                                <i class="bi bi-envelope text-primary"></i>
                            </span>
                            <input type="email" class="form-control border-start-0 ps-0" placeholder="tucorreo@ejemplo.com" aria-label="Correo electrónico" aria-describedby="email-addon" required>
                        </div>
                    </div>
                    <div class="col-md-auto">
                        <button type="submit" class="btn btn-primary btn-lg px-5 py-3 fw-semibold hover-lift shadow">
                            <span class="d-flex align-items-center">
                                <i class="bi bi-send-check me-2"></i>
                                <span>Suscribirme</span>
                            </span>
                        </button>
                    </div>
                </form>
                
                <div class="mt-4" data-aos="fade-up" data-aos-delay="150">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="privacyPolicy" required>
                        <label class="form-check-label small text-muted" for="privacyPolicy">
                            Acepto la <a href="/mariscales1-php/public/pages/privacidad" class="text-decoration-none">política de privacidad</a>
                        </label>
                    </div>
                </div>
                
                <div class="mt-5" data-aos="fade-up" data-aos-delay="200">
                    <p class="text-muted mb-3">También puedes seguirnos en:</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="#" class="btn btn-outline-primary rounded-circle p-3 hover-lift" data-bs-toggle="tooltip" data-bs-placement="top" title="Facebook">
                            <i class="bi bi-facebook fs-5"></i>
                        </a>
                        <a href="#" class="btn btn-outline-primary rounded-circle p-3 hover-lift" data-bs-toggle="tooltip" data-bs-placement="top" title="Instagram">
                            <i class="bi bi-instagram fs-5"></i>
                        </a>
                        <a href="#" class="btn btn-outline-primary rounded-circle p-3 hover-lift" data-bs-toggle="tooltip" data-bs-placement="top" title="Twitter">
                            <i class="bi bi-twitter-x fs-5"></i>
                        </a>
                        <a href="#" class="btn btn-outline-primary rounded-circle p-3 hover-lift" data-bs-toggle="tooltip" data-bs-placement="top" title="YouTube">
                            <i class="bi bi-youtube fs-5"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Wave Divider -->
    <div class="position-absolute bottom-0 start-0 w-100">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120" fill="white">
            <path d="M0,64L80,69.3C160,75,320,85,480,80C640,75,800,53,960,48C1120,43,1280,53,1360,58.7L1440,64L1440,120L1360,120C1280,120,1120,120,960,120C800,120,640,120,480,120C320,120,160,120,80,120L0,120Z"></path>
        </svg>
    </div>
</section>
