<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Galería - Filá Mariscales</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body { background-color: #f8f9fa; }
        .card { box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .btn-group .btn { margin-right: 2px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/prueba-php/public/admin/dashboard">
                <i class="fas fa-shield-alt me-2"></i>Panel de Administración
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="/prueba-php/public/admin/dashboard">Dashboard</a>
                <a class="nav-link" href="/prueba-php/public/admin/usuarios">Usuarios</a>
                <a class="nav-link active" href="/prueba-php/public/admin/galeria">Galería</a>
                <a class="nav-link" href="/prueba-php/public/admin/logout">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Gestión de Galería</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i class="fas fa-upload me-2"></i>Subir a Galería
                </button>
                <button type="button" class="btn btn-success ms-2" data-bs-toggle="modal" data-bs-target="#carouselUploadModal">
                    <i class="fas fa-images me-2"></i>Subir al Carrusel
                </button>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs mb-4" id="galleryTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="gallery-tab" data-bs-toggle="tab" data-bs-target="#gallery" type="button" role="tab" aria-controls="gallery" aria-selected="true">
                    <i class="fas fa-images me-2"></i>Galería General
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="carousel-tab" data-bs-toggle="tab" data-bs-target="#carousel" type="button" role="tab" aria-controls="carousel" aria-selected="false">
                    <i class="fas fa-sliders-h me-2"></i>Carrusel Principal
                </button>
            </li>
        </ul>

        <!-- Upload Modal -->
        <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadModalLabel">Subir Archivos a la Galería</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="/prueba-php/public/admin/subirMedia" method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="media" class="form-label">Seleccionar archivos</label>
                                <input type="file" class="form-control" id="media" name="media[]" multiple accept="image/*,video/*" required>
                                <div class="form-text">
                                    Formatos permitidos: JPG, PNG, GIF, MP4, AVI, MOV. Tamaño máximo: 50MB por archivo.
                                </div>
                            </div>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Tipos de archivo soportados:</strong><br>
                                • Imágenes: JPG, PNG, GIF<br>
                                • Videos: MP4, AVI, MOV<br>
                                • Tamaño máximo: 50MB por archivo
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload me-2"></i>Subir Archivos
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Carousel Upload Modal -->
        <div class="modal fade" id="carouselUploadModal" tabindex="-1" aria-labelledby="carouselUploadModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="carouselUploadModalLabel">Subir Imágenes al Carrusel</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="/prueba-php/public/admin/subirCarousel" method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="carouselImages" class="form-label">Seleccionar imágenes para el carrusel</label>
                                <input type="file" class="form-control" id="carouselImages" name="carouselImages[]" multiple accept="image/*" required>
                                <div class="form-text">
                                    Solo imágenes: JPG, PNG, GIF. Tamaño máximo: 10MB por imagen. Resolución recomendada: 1920x1080px.
                                </div>
                            </div>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Importante:</strong><br>
                                • Solo se permiten imágenes (no videos)<br>
                                • Las imágenes se mostrarán en el carrusel principal del sitio<br>
                                • Se recomienda usar imágenes de alta calidad y formato horizontal<br>
                                • Tamaño máximo: 10MB por imagen
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-upload me-2"></i>Subir al Carrusel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="tab-content" id="galleryTabContent">
            <!-- Gallery Tab -->
            <div class="tab-pane fade show active" id="gallery" role="tabpanel" aria-labelledby="gallery-tab">
                <!-- Gallery Grid -->
        <div class="row">
            <?php if (!empty($data['mediaFiles'])): ?>
                <?php foreach ($data['mediaFiles'] as $file): ?>
                    <div class="col-md-4 col-lg-3 mb-4">
                        <div class="card h-100">
                            <div class="card-body p-2">
                                <?php if (strpos($file['type'], 'image/') === 0): ?>
                                    <!-- Image Preview -->
                                    <img src="<?= $file['url'] ?>" 
                                         class="card-img-top" 
                                         alt="<?= htmlspecialchars($file['name']) ?>"
                                         style="height: 200px; object-fit: cover;">
                                <?php else: ?>
                                    <!-- Video Preview -->
                                    <div class="bg-dark text-white d-flex align-items-center justify-content-center" 
                                         style="height: 200px;">
                                        <i class="fas fa-video fa-3x"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="card-body p-2">
                                    <h6 class="card-title text-truncate" title="<?= htmlspecialchars($file['name']) ?>">
                                        <?= htmlspecialchars($file['name']) ?>
                                    </h6>
                                    <p class="card-text small text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        <?= date('d/m/Y H:i', strtotime($file['date'])) ?>
                                    </p>
                                    <p class="card-text small text-muted">
                                        <i class="fas fa-file me-1"></i>
                                        <?= number_format($file['size'] / 1024 / 1024, 2) ?> MB
                                    </p>
                                </div>
                                
                                <div class="card-footer p-2">
                                    <div class="btn-group w-100" role="group">
                                        <a href="<?= $file['url'] ?>" 
                                           class="btn btn-sm btn-outline-primary" 
                                           target="_blank"
                                           title="Ver archivo">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger" 
                                                onclick="confirmDelete('<?= $file['name'] ?>')"
                                                title="Eliminar archivo">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-images fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">No hay archivos en la galería</h4>
                        <p class="text-muted">Sube tu primer archivo para comenzar a llenar la galería.</p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                            <i class="fas fa-upload me-2"></i>Subir Archivos
                        </button>
                    </div>
                </div>
            <?php endif; ?>
            </div>
        </div>

        <!-- Carousel Tab -->
        <div class="tab-pane fade" id="carousel" role="tabpanel" aria-labelledby="carousel-tab">
            <div class="row">
                <?php if (!empty($data['carouselFiles'])): ?>
                    <?php foreach ($data['carouselFiles'] as $file): ?>
                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="card h-100">
                                <div class="card-body p-2">
                                    <!-- Carousel Image Preview -->
                                    <img src="<?= $file['url'] ?>" 
                                         class="card-img-top" 
                                         alt="<?= htmlspecialchars($file['name']) ?>"
                                         style="height: 200px; object-fit: cover;">
                                    
                                    <div class="card-body p-2">
                                        <h6 class="card-title text-truncate" title="<?= htmlspecialchars($file['name']) ?>">
                                            <?= htmlspecialchars($file['name']) ?>
                                        </h6>
                                        <p class="card-text small text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            <?= date('d/m/Y H:i', strtotime($file['date'])) ?>
                                        </p>
                                        <p class="card-text small text-muted">
                                            <i class="fas fa-file me-1"></i>
                                            <?= number_format($file['size'] / 1024 / 1024, 2) ?> MB
                                        </p>
                                        <span class="badge bg-success">Carrusel</span>
                                    </div>
                                    
                                    <div class="card-footer p-2">
                                        <div class="btn-group w-100" role="group">
                                            <a href="<?= $file['url'] ?>" 
                                               class="btn btn-sm btn-outline-primary" 
                                               target="_blank"
                                               title="Ver imagen">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-warning" 
                                                    onclick="confirmCarouselDelete('<?= $file['name'] ?>')"
                                                    title="Eliminar del carrusel">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-sliders-h fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">No hay imágenes en el carrusel</h4>
                            <p class="text-muted">Sube imágenes para que aparezcan en el carrusel principal del sitio.</p>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#carouselUploadModal">
                                <i class="fas fa-upload me-2"></i>Subir al Carrusel
                            </button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>¿Estás seguro de que quieres eliminar este archivo?</p>
                        <p class="text-danger"><strong>Esta acción no se puede deshacer.</strong></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <a href="#" id="confirmDeleteBtn" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>Eliminar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    function confirmDelete(fileName) {
        document.getElementById('confirmDeleteBtn').href = '/prueba-php/public/admin/eliminarMedia/' + encodeURIComponent(fileName);
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
    
    function confirmCarouselDelete(fileName) {
        document.getElementById('confirmDeleteBtn').href = '/prueba-php/public/admin/eliminarCarousel/' + encodeURIComponent(fileName);
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
    </script>
</body>
</html>
