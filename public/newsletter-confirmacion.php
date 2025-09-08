<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Suscripción - Filá Mariscales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            min-height: 100vh; 
            display: flex;
            align-items: center;
        }
        .confirmation-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #dc143c 0%, #8b0000 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .content {
            padding: 2rem;
        }
        .success-icon {
            font-size: 4rem;
            color: #28a745;
            margin-bottom: 1rem;
        }
        .btn-primary {
            background: linear-gradient(45deg, #dc143c, #8b0000);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(45deg, #8b0000, #dc143c);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220,20,60,0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="confirmation-card">
                    <div class="header">
                        <h1 class="mb-0">
                            <i class="bi bi-envelope-heart me-3"></i>
                            ¡Suscripción Exitosa!
                        </h1>
                    </div>
                    <div class="content text-center">
                        <div class="success-icon">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        
                        <h2 class="text-primary mb-3">¡Bienvenido a nuestro boletín!</h2>
                        
                        <p class="lead mb-4">
                            Te has suscrito exitosamente al boletín de noticias de la 
                            <strong>Filá Mariscales de Caballeros Templarios</strong>.
                        </p>
                        
                        <div class="alert alert-info mb-4">
                            <h5><i class="bi bi-info-circle me-2"></i>¿Qué recibirás?</h5>
                            <ul class="list-unstyled mb-0">
                                <li><i class="bi bi-check text-success me-2"></i>Las últimas noticias y eventos</li>
                                <li><i class="bi bi-check text-success me-2"></i>Información sobre actividades</li>
                                <li><i class="bi bi-check text-success me-2"></i>Noticias de la tradición templaria</li>
                                <li><i class="bi bi-check text-success me-2"></i>Actualizaciones de las Fiestas</li>
                            </ul>
                        </div>
                        
                        <div class="alert alert-warning mb-4">
                            <i class="bi bi-envelope me-2"></i>
                            <strong>Importante:</strong> Revisa tu bandeja de entrada (y carpeta de spam) 
                            para confirmar tu suscripción.
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                            <a href="/prueba-php/public/" class="btn btn-primary">
                                <i class="bi bi-house me-2"></i>
                                Volver al Inicio
                            </a>
                            <a href="/prueba-php/public/noticias" class="btn btn-outline-primary">
                                <i class="bi bi-newspaper me-2"></i>
                                Ver Noticias
                            </a>
                        </div>
                        
                        <hr class="my-4">
                        
                        <p class="text-muted small">
                            <i class="bi bi-shield-check me-1"></i>
                            Tu email está protegido y solo será usado para enviarte información de la filá.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
