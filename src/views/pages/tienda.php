<?php $content = '\n';
ob_start(); // Start output buffering
?>

<!-- Hero Section -->
<section class="hero-section text-white text-center py-8 mb-8" style="background: linear-gradient(rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.1)), url('/mariscales1-php/public/assets/images/backgrounds/knight-templar-background.jpg') center/cover; backdrop-filter: blur(5px); -webkit-backdrop-filter: blur(5px);">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">Tienda Online</h1>
        <p class="lead">Productos oficiales de la Filá Mariscales</p>
    </div>
</section>

<!-- Main Content -->
<section class="py-5">
    <div class="container">
        <!-- Categories -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <button class="btn btn-outline-primary active">Todos los productos</button>
                    <button class="btn btn-outline-primary">Ropa</button>
                    <button class="btn btn-outline-primary">Accesorios</button>
                    <button class="btn btn-outline-primary">Música</button>
                    <button class="btn btn-outline-primary">Recuerdos</button>
                </div>
            </div>
        </div>

        <!-- Featured Products -->
        <div class="row mb-5">
            <div class="col-12 mb-4">
                <h2 class="fw-bold">Productos Destacados</h2>
                <p class="text-muted">Los artículos más populares de nuestra tienda</p>
            </div>
            
            <!-- Product 1 -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 border-0 shadow-sm product-card">
                    <div class="position-relative">
                        <img src="/mariscales1-php/public/assets/images/tienda/camiseta.jpg" class="card-img-top" alt="Camiseta Oficial">
                        <div class="badge bg-danger position-absolute top-0 end-0 m-2">¡Nuevo!</div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">Camiseta Oficial 2025</h5>
                            <div class="text-warning">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star"></i>
                            </div>
                        </div>
                        <p class="text-muted small">Camiseta oficial de la Filá Mariscales temporada 2024-2025</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-primary">25,00 €</h5>
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-cart-plus"></i> Añadir
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Product 2 -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 border-0 shadow-sm product-card">
                    <img src="/mariscales1-php/public/assets/images/tienda/gorra.jpg" class="card-img-top" alt="Gorra Bordada">
                    <div class="card-body">
                        <h5 class="card-title">Gorra Bordada</h5>
                        <p class="text-muted small">Gorra con el escudo bordado de la Filá Mariscales</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-primary">15,00 €</h5>
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-cart-plus"></i> Añadir
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Product 3 -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 border-0 shadow-sm product-card">
                    <div class="position-relative">
                        <img src="/mariscales1-php/public/assets/images/tienda/cd.jpg" class="card-img-top" alt="CD Música">
                        <div class="badge bg-success position-absolute top-0 end-0 m-2">¡Más vendido!</div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">CD Música Oficial</h5>
                        <p class="text-muted small">Recopilación de nuestras mejores marchas y pasodobles</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-primary">12,00 €</h5>
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-cart-plus"></i> Añadir
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Product 4 -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 border-0 shadow-sm product-card">
                    <img src="/mariscales1-php/public/assets/images/tienda/bandera.jpg" class="card-img-top" alt="Bandera">
                    <div class="card-body">
                        <h5 class="card-title">Bandera Oficial</h5>
                        <p class="text-muted small">Bandera oficial de la Filá Mariscales (100x150cm)</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-primary">35,00 €</h5>
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-cart-plus"></i> Añadir
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- All Products -->
        <div class="row">
            <div class="col-12 mb-4">
                <h2 class="fw-bold">Todos los Productos</h2>
                <div class="d-flex justify-content-between align-items-center">
                    <p class="text-muted mb-0">Descubre nuestra amplia gama de productos</p>
                    <div class="d-flex align-items-center">
                        <label for="sortBy" class="me-2 mb-0">Ordenar por:</label>
                        <select class="form-select form-select-sm" style="width: auto;">
                            <option selected>Destacados</option>
                            <option>Precio: menor a mayor</option>
                            <option>Precio: mayor a menor</option>
                            <option>Más recientes</option>
                            <option>Más vendidos</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Product Grid -->
            <?php for($i = 1; $i <= 8; $i++): ?>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 border-0 shadow-sm product-card">
                    <img src="/mariscales1-php/public/assets/images/tienda/producto<?php echo ($i % 4) + 1; ?>.jpg" class="card-img-top" alt="Producto <?php echo $i; ?>">
                    <div class="card-body">
                        <h5 class="card-title">Producto <?php echo $i; ?></h5>
                        <p class="text-muted small">Descripción breve del producto <?php echo $i; ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-primary"><?php echo (10 + $i * 2); ?>,00 €</h5>
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-cart-plus"></i> Añadir
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endfor; ?>
        </div>
        
        <!-- Pagination -->
        <nav aria-label="Page navigation" class="mt-5">
            <ul class="pagination justify-content-center">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Anterior</a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">Siguiente</a>
                </li>
            </ul>
        </nav>
    </div>
</section>

<!-- Shopping Cart Sidebar -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="shoppingCart" aria-labelledby="shoppingCartLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="shoppingCartLabel">Mi Carrito</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="text-center py-5">
            <i class="bi bi-cart-x display-1 text-muted"></i>
            <p class="mt-3">Tu carrito está vacío</p>
            <button class="btn btn-primary mt-2" data-bs-dismiss="offcanvas">Seguir comprando</button>
        </div>
    </div>
</div>

<!-- Newsletter Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h3 class="fw-bold mb-4">¿Quieres estar al día de nuestras novedades?</h3>
                <p class="text-muted mb-4">Suscríbete a nuestro boletín y recibe ofertas exclusivas y novedades sobre nuevos productos.</p>
                <form class="row g-3 justify-content-center">
                    <div class="col-md-8">
                        <input type="email" class="form-control form-control-lg" placeholder="Tu correo electrónico">
                    </div>
                    <div class="col-md-auto">
                        <button type="submit" class="btn btn-primary btn-lg">Suscribirse</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
