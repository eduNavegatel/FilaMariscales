<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?> - Filá Mariscales</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome - Múltiples CDNs como respaldo -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" onerror="this.onerror=null;this.href='https://use.fontawesome.com/releases/v6.0.0/css/all.css';">
    <link href="https://use.fontawesome.com/releases/v6.0.0/css/all.css" rel="stylesheet" onerror="this.onerror=null;this.href='https://maxcdn.bootstrapcdn.com/font-awesome/6.0.0/css/font-awesome.min.css';">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/6.0.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- Admin CSS -->
    <link href="/prueba-php/public/assets/css/admin.css" rel="stylesheet">
    <!-- Font Awesome Fallback CSS -->
    <link href="/prueba-php/public/assets/css/fontawesome-fallback.css" rel="stylesheet">
    
    <style>
        body { 
            background-color: #f8f9fa; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card { 
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); 
            border: none;
            border-radius: 8px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        .news-item {
            border-left: 4px solid #007bff;
            padding-left: 15px;
            margin-bottom: 15px;
        }
        .news-meta {
            font-size: 0.85rem;
            color: #6c757d;
        }
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #6c757d;
        }
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0">
                            <i class="fas fa-newspaper me-2"></i>
                            Gestión de Noticias
                        </h1>
                        <p class="text-muted mb-0">Administra las noticias y publicaciones de la Filá</p>
                    </div>
                    <div>
                        <a href="<?= URL_ROOT ?>/admin/dashboard" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Volver al Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title mb-1">Total Noticias</h5>
                                <h2 class="mb-0"><?= $data['newsCount'] ?></h2>
                                <small class="opacity-75">Publicaciones</small>
                            </div>
                            <i class="fas fa-newspaper fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title mb-1">Archivos</h5>
                                <h2 class="mb-0"><?= count($data['newsList']) ?></h2>
                                <small class="opacity-75">En sistema</small>
                            </div>
                            <i class="fas fa-file-alt fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title mb-1">Última Actualización</h5>
                                <h6 class="mb-0">
                                    <?= !empty($data['newsList']) ? date('d/m/Y', strtotime($data['newsList'][0]['modified'])) : 'N/A' ?>
                                </h6>
                                <small class="opacity-75">Fecha</small>
                            </div>
                            <i class="fas fa-clock fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title mb-1">Acciones</h5>
                                <h6 class="mb-0">Gestionar</h6>
                                <small class="opacity-75">Contenido</small>
                            </div>
                            <i class="fas fa-cogs fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- News List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>
                            Lista de Noticias
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($data['newsList'])): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th><i class="fas fa-file me-1"></i>Archivo</th>
                                            <th><i class="fas fa-weight me-1"></i>Tamaño</th>
                                            <th><i class="fas fa-calendar me-1"></i>Última Modificación</th>
                                            <th><i class="fas fa-cogs me-1"></i>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($data['newsList'] as $news): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-file-alt text-danger me-2"></i>
                                                        <strong><?= htmlspecialchars($news['filename']) ?></strong>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">
                                                        <?= number_format($news['size'] / 1024, 2) ?> KB
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="text-muted">
                                                        <i class="fas fa-clock me-1"></i>
                                                        <?= $news['modified'] ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <button type="button" class="btn btn-outline-primary" 
                                                                onclick="viewNews('<?= htmlspecialchars($news['filename']) ?>')"
                                                                title="Ver contenido">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-success" 
                                                                onclick="editNews('<?= htmlspecialchars($news['filename']) ?>')"
                                                                title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-danger" 
                                                                onclick="deleteNews('<?= htmlspecialchars($news['filename']) ?>')"
                                                                title="Eliminar">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-newspaper"></i>
                                <h4>No hay noticias disponibles</h4>
                                <p>No se encontraron archivos de noticias en el sistema.</p>
                                <button class="btn btn-primary" onclick="createNews()">
                                    <i class="fas fa-plus me-1"></i>Crear Primera Noticia
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-bolt me-2"></i>
                            Acciones Rápidas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <button class="btn btn-primary w-100" onclick="createNews()">
                                    <i class="fas fa-plus me-1"></i>Nueva Noticia
                                </button>
                            </div>
                            <div class="col-md-3 mb-2">
                                <button class="btn btn-success w-100" onclick="importNews()">
                                    <i class="fas fa-upload me-1"></i>Importar
                                </button>
                            </div>
                            <div class="col-md-3 mb-2">
                                <button class="btn btn-info w-100" onclick="exportNews()">
                                    <i class="fas fa-download me-1"></i>Exportar
                                </button>
                            </div>
                            <div class="col-md-3 mb-2">
                                <button class="btn btn-warning w-100" onclick="refreshNews()">
                                    <i class="fas fa-sync me-1"></i>Actualizar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // News management functions
        function viewNews(filename) {
            alert('Función de visualización para: ' + filename + '\n\nEsta funcionalidad se implementará próximamente.');
        }
        
        function editNews(filename) {
            alert('Función de edición para: ' + filename + '\n\nEsta funcionalidad se implementará próximamente.');
        }
        
        function deleteNews(filename) {
            if (confirm('¿Estás seguro de que quieres eliminar la noticia "' + filename + '"?')) {
                alert('Función de eliminación para: ' + filename + '\n\nEsta funcionalidad se implementará próximamente.');
            }
        }
        
        function createNews() {
            alert('Función de creación de noticias\n\nEsta funcionalidad se implementará próximamente.');
        }
        
        function importNews() {
            alert('Función de importación de noticias\n\nEsta funcionalidad se implementará próximamente.');
        }
        
        function exportNews() {
            alert('Función de exportación de noticias\n\nEsta funcionalidad se implementará próximamente.');
        }
        
        function refreshNews() {
            location.reload();
        }
        
        // Verificar si Font Awesome se cargó correctamente
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                var testIcon = document.createElement('i');
                testIcon.className = 'fas fa-test';
                testIcon.style.position = 'absolute';
                testIcon.style.left = '-9999px';
                testIcon.style.visibility = 'hidden';
                document.body.appendChild(testIcon);
                
                var computedStyle = window.getComputedStyle(testIcon);
                var fontFamily = computedStyle.getPropertyValue('font-family');
                
                document.body.removeChild(testIcon);
                
                if (!fontFamily.includes('Font Awesome') && !fontFamily.includes('FontAwesome')) {
                    console.warn('Font Awesome no se cargó correctamente. Intentando cargar desde CDN alternativo...');
                    
                    var link = document.createElement('link');
                    link.rel = 'stylesheet';
                    link.href = 'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.0.0/css/all.min.css';
                    document.head.appendChild(link);
                } else {
                    console.log('Font Awesome cargado correctamente');
                }
            }, 1000);
        });
    </script>
</body>
</html>

