<?php
// Dashboard de emergencia - sin dependencias complejas
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Limpiar cualquier output previo
ob_clean();

// Cargar solo lo esencial
require_once '../src/config/config.php';
require_once '../src/config/Database.php';

// Obtener estadísticas básicas
$userCount = 0;
$eventCount = 0;
$productCount = 0;

try {
    $db = new Database();
    
    // Contar usuarios
    $db->query("SELECT COUNT(*) as count FROM users");
    $result = $db->single();
    $userCount = $result['count'] ?? 0;
    
    // Contar eventos
    $db->query("SELECT COUNT(*) as count FROM eventos");
    $result = $db->single();
    $eventCount = $result['count'] ?? 0;
    
    // Contar productos
    $db->query("SELECT COUNT(*) as total FROM products");
    $result = $db->single();
    $productCount = $result['total'] ?? 0;
    
} catch (Exception $e) {
    // Usar valores por defecto si hay error
    $userCount = 0;
    $eventCount = 0;
    $productCount = 0;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-4 text-primary">
                    <i class="fas fa-tachometer-alt me-2"></i>Panel de Administración
                </h1>
                
                <!-- Estadísticas principales -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card text-white bg-danger h-100 shadow">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="card-title mb-1">Usuarios Registrados</h5>
                                        <h2 class="mb-0"><?= $userCount ?></h2>
                                        <small class="opacity-75">Miembros</small>
                                    </div>
                                    <i class="fas fa-users fa-2x opacity-50"></i>
                                </div>
                                <a href="/prueba-php/public/admin/usuarios" class="btn btn-light btn-sm mt-auto">
                                    <i class="fas fa-users me-1"></i>Gestionar usuarios
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="card text-white bg-success h-100 shadow">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="card-title mb-1">Eventos Activos</h5>
                                        <h2 class="mb-0"><?= $eventCount ?></h2>
                                        <small class="opacity-75">Próximos</small>
                                    </div>
                                    <i class="fas fa-calendar fa-2x opacity-50"></i>
                                </div>
                                <a href="/prueba-php/public/admin/eventos" class="btn btn-light btn-sm mt-auto">
                                    <i class="fas fa-calendar me-1"></i>Gestionar eventos
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="card text-white bg-info h-100 shadow">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="card-title mb-1">Galería</h5>
                                        <h2 class="mb-0">0</h2>
                                        <small class="opacity-75">Archivos multimedia</small>
                                    </div>
                                    <i class="fas fa-images fa-2x opacity-50"></i>
                                </div>
                                <a href="/prueba-php/public/admin/galeria" class="btn btn-light btn-sm mt-auto">
                                    <i class="fas fa-images me-1"></i>Gestionar galería
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="card text-white bg-warning h-100 shadow">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="card-title mb-1">Acciones Rápidas</h5>
                                        <h2 class="mb-0">+</h2>
                                        <small class="opacity-75">Herramientas</small>
                                    </div>
                                    <i class="fas fa-plus fa-2x opacity-50"></i>
                                </div>
                                <div class="mt-auto">
                                    <a href="/prueba-php/public/admin/crearUsuario" class="text-white d-block small mb-1">Nuevo usuario</a>
                                    <a href="/prueba-php/public/admin/nuevoEvento" class="text-white d-block small mb-1">Nuevo evento</a>
                                    <a href="/prueba-php/public/admin/galeria" class="text-white d-block small">Subir archivos</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Estadísticas secundarias -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card text-white h-100 shadow" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="card-title mb-1">🛒 Tienda Online</h5>
                                        <h2 class="mb-0"><?= $productCount ?></h2>
                                        <small class="opacity-75">Productos</small>
                                    </div>
                                    <i class="fas fa-shopping-cart fa-2x opacity-50"></i>
                                </div>
                                <div class="mt-auto">
                                    <a href="/prueba-php/public/admin/productos" class="btn btn-light btn-sm">
                                        <i class="fas fa-shopping-cart me-1"></i>Gestionar Tienda
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="card text-white bg-dark h-100 shadow">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="card-title mb-1">Noticias</h5>
                                        <h2 class="mb-0">0</h2>
                                        <small class="opacity-75">Artículos</small>
                                    </div>
                                    <i class="fas fa-newspaper fa-2x opacity-50"></i>
                                </div>
                                <a href="/prueba-php/public/admin/noticias" class="btn btn-light btn-sm mt-auto">
                                    <i class="fas fa-newspaper me-1"></i>Gestionar noticias
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="card text-white bg-danger h-100 shadow">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="card-title mb-1">Mensajes</h5>
                                        <h2 class="mb-0">0</h2>
                                        <small class="opacity-75">Contacto</small>
                                    </div>
                                    <i class="fas fa-envelope fa-2x opacity-50"></i>
                                </div>
                                <a href="/prueba-php/public/admin/mensajes" class="btn btn-light btn-sm mt-auto">
                                    <i class="fas fa-envelope me-1"></i>Ver mensajes
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Información del sistema -->
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Estado del Sistema
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Estadísticas de la Base de Datos:</h6>
                                        <ul class="list-unstyled">
                                            <li><i class="fas fa-users text-danger me-2"></i>Usuarios: <strong><?= $userCount ?></strong></li>
                                            <li><i class="fas fa-calendar text-success me-2"></i>Eventos: <strong><?= $eventCount ?></strong></li>
                                            <li><i class="fas fa-shopping-cart text-info me-2"></i>Productos: <strong><?= $productCount ?></strong></li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Enlaces de Prueba:</h6>
                                        <div class="d-flex flex-wrap gap-2">
                                            <a href="/prueba-php/public/admin/productos" class="btn btn-outline-primary btn-sm">Gestión de Productos</a>
                                            <a href="/prueba-php/public/admin/nuevo-producto" class="btn btn-outline-success btn-sm">Nuevo Producto</a>
                                            <a href="/prueba-php/public/test-tienda-simple.php" class="btn btn-outline-info btn-sm">Test de Tienda</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
