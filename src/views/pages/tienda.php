<?php
// Página de Tienda Online - Filá Mariscales
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
        
        <!-- Estadísticas de la Tienda -->
        <div class="row mb-5">
            <div class="col-md-3 col-6 mb-3">
                <div class="text-center">
                    <div class="display-6 text-primary fw-bold"><?= count($products) ?></div>
                    <small class="text-muted">Productos Disponibles</small>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="text-center">
                    <div class="display-6 text-success fw-bold">100%</div>
                    <small class="text-muted">Oficiales</small>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="text-center">
                    <div class="display-6 text-warning fw-bold">24h</div>
                    <small class="text-muted">Envío Rápido</small>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="text-center">
                    <div class="display-6 text-info fw-bold">1975</div>
                    <small class="text-muted">Desde</small>
                </div>
            </div>
        </div>

        <!-- Filtros de Categorías -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <button class="btn btn-outline-primary active" onclick="filterProducts('all')">
                        <i class="bi bi-grid me-2"></i>Todos los productos
                    </button>
                    <button class="btn btn-outline-primary" onclick="filterProducts('Ropa')">
                        <i class="bi bi-shirt me-2"></i>Ropa
                    </button>
                    <button class="btn btn-outline-primary" onclick="filterProducts('Accesorios')">
                        <i class="bi bi-gem me-2"></i>Accesorios
                    </button>
                    <button class="btn btn-outline-primary" onclick="filterProducts('Recuerdos')">
                        <i class="bi bi-gift me-2"></i>Recuerdos
                    </button>
                </div>
            </div>
        </div>

        <!-- Lista de Productos -->
        <?php if (!empty($products)): ?>
            <div class="row" id="products-container">
                <?php foreach ($products as $product): ?>
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-4 product-item" data-category="<?= trim($product->categoria ?? 'General') ?>">
                        <div class="card h-100 border-0 shadow-sm product-card">
                            <div class="position-relative">
                                <?php if (!empty($product->imagen)): ?>
                                    <img src="/prueba-php/public/uploads/products/<?= htmlspecialchars($product->imagen) ?>" 
                                         class="card-img-top" 
                                         alt="<?= htmlspecialchars($product->nombre) ?>"
                                         style="height: 250px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                         style="height: 250px;">
                                        <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($product->stock <= 5 && $product->stock > 0): ?>
                                    <div class="badge bg-warning position-absolute top-0 end-0 m-2">
                                        ¡Últimas unidades!
                                    </div>
                                <?php elseif ($product->stock == 0): ?>
                                    <div class="badge bg-danger position-absolute top-0 end-0 m-2">
                                        Agotado
                                    </div>
                                <?php else: ?>
                                    <div class="badge bg-success position-absolute top-0 end-0 m-2">
                                        Disponible
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="card-body d-flex flex-column">
                                <div class="mb-2">
                                    <h5 class="card-title mb-1"><?= htmlspecialchars($product->nombre) ?></h5>
                                    <small class="text-muted"><?= htmlspecialchars($product->categoria ?? 'General') ?></small>
                                </div>
                                
                                <p class="card-text text-muted small flex-grow-1">
                                    <?= htmlspecialchars(substr($product->descripcion ?? 'Sin descripción', 0, 100)) ?>
                                    <?php if (strlen($product->descripcion ?? '') > 100): ?>...<?php endif; ?>
                                </p>
                                
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <span class="h5 text-primary mb-0"><?= number_format($product->precio, 2) ?>€</span>
                                        <?php if (!empty($product->precio_original) && $product->precio_original > $product->precio): ?>
                                            <small class="text-muted text-decoration-line-through ms-2">
                                                <?= number_format($product->precio_original, 2) ?>€
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-warning">
                                        <?php 
                                        $rating = $product->rating ?? 5;
                                        for ($i = 1; $i <= 5; $i++): 
                                        ?>
                                            <i class="bi bi-star<?= $i <= $rating ? '-fill' : '' ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <?php if ($product->stock > 0): ?>
                                        <button class="btn btn-primary flex-grow-1" onclick="addToCart(<?= $product->id ?>)">
                                            <i class="bi bi-cart-plus me-1"></i>Añadir al Carrito
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-secondary flex-grow-1" disabled>
                                            <i class="bi bi-x-circle me-1"></i>Agotado
                                        </button>
                                    <?php endif; ?>
                                    <button class="btn btn-outline-primary" onclick="viewProduct(<?= $product->id ?>)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="bi bi-box me-1"></i>Stock: <?= $product->stock ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <!-- Sin Productos -->
            <div class="row">
                <div class="col-12 text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-shop text-muted" style="font-size: 4rem;"></i>
                    </div>
                    <h3 class="text-muted mb-3">No hay productos disponibles</h3>
                    <p class="text-muted mb-4">Pronto tendremos productos oficiales de la Filá Mariscales disponibles para ti.</p>
                    <a href="/prueba-php/public/contacto" class="btn btn-primary">
                        <i class="bi bi-envelope me-2"></i>Contactar para más información
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <!-- Información de Envío -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card bg-light border-0">
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3 mb-3">
                                <i class="bi bi-truck text-primary mb-2" style="font-size: 2rem;"></i>
                                <h6>Envío Gratis</h6>
                                <small class="text-muted">En pedidos superiores a 50€</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <i class="bi bi-shield-check text-success mb-2" style="font-size: 2rem;"></i>
                                <h6>Pago Seguro</h6>
                                <small class="text-muted">Protegido con SSL</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <i class="bi bi-arrow-clockwise text-warning mb-2" style="font-size: 2rem;"></i>
                                <h6>Devoluciones</h6>
                                <small class="text-muted">30 días de garantía</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <i class="bi bi-headset text-info mb-2" style="font-size: 2rem;"></i>
                                <h6>Soporte 24/7</h6>
                                <small class="text-muted">Atención personalizada</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Filtrar productos por categoría
function filterProducts(category) {
    const products = document.querySelectorAll('.product-item');
    const buttons = document.querySelectorAll('.btn-outline-primary');
    
    // Actualizar botones activos
    buttons.forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    // Filtrar productos
    products.forEach(product => {
        const productCategory = product.dataset.category.trim();
        const filterCategory = category.trim();
        
        if (category === 'all' || productCategory === filterCategory) {
            product.style.display = 'block';
        } else {
            product.style.display = 'none';
        }
    });
    
    // Mostrar mensaje si no hay productos en la categoría
    const visibleProducts = document.querySelectorAll('.product-item[style*="block"], .product-item:not([style*="none"])');
    const noProductsMessage = document.getElementById('no-products-message');
    
    if (visibleProducts.length === 0 && category !== 'all') {
        if (!noProductsMessage) {
            const message = document.createElement('div');
            message.id = 'no-products-message';
            message.className = 'col-12 text-center py-5';
            message.innerHTML = `
                <div class="mb-4">
                    <i class="bi bi-search text-muted" style="font-size: 4rem;"></i>
                </div>
                <h3 class="text-muted mb-3">No hay productos en esta categoría</h3>
                <p class="text-muted mb-4">Pronto tendremos productos de ${category} disponibles.</p>
                <button class="btn btn-primary" onclick="filterProducts('all')">
                    <i class="bi bi-grid me-2"></i>Ver todos los productos
                </button>
            `;
            document.getElementById('products-container').appendChild(message);
        }
    } else if (noProductsMessage) {
        noProductsMessage.remove();
    }
}

// Añadir al carrito
function addToCart(productId) {
    // Aquí se implementaría la lógica del carrito
    alert('Producto añadido al carrito (ID: ' + productId + ')');
}

// Ver producto
function viewProduct(productId) {
    // Aquí se implementaría la vista detallada del producto
    alert('Ver producto (ID: ' + productId + ')');
}

// Debug: Mostrar categorías disponibles (solo en desarrollo)
document.addEventListener('DOMContentLoaded', function() {
    const products = document.querySelectorAll('.product-item');
    const categories = new Set();
    
    products.forEach(product => {
        categories.add(product.dataset.category);
    });
    
    console.log('Categorías disponibles:', Array.from(categories));
});
</script>

<style>
.product-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}

.btn-outline-primary.active {
    background-color: var(--primary);
    border-color: var(--primary);
    color: white;
}
</style>