<?php
// Panel simple de mensajes que funciona directamente
require_once '../../src/config/config.php';

// Verificar si es admin
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: /prueba-php/public/admin/login');
    exit;
}

// Obtener mensajes
$messagesDir = 'uploads/messages/';
$messagesList = [];

if (is_dir($messagesDir)) {
    $messageFiles = glob($messagesDir . '*.{txt,json,html}', GLOB_BRACE);
    
    foreach ($messageFiles as $file) {
        $messagesList[] = [
            'filename' => basename($file),
            'size' => filesize($file),
            'modified' => date('Y-m-d H:i:s', filemtime($file)),
            'path' => $file,
            'content' => file_get_contents($file)
        ];
    }
    
    // Ordenar por fecha de modificación (más recientes primero)
    usort($messagesList, function($a, $b) {
        return strtotime($b['modified']) - strtotime($a['modified']);
    });
}

$messagesCount = count($messagesList);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Mensajes - Filá Mariscales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .card { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #dc143c 0%, #8b0000 100%); color: white; }
        .stat-card { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card header">
                    <div class="card-body text-center">
                        <h1 class="mb-0">
                            <i class="bi bi-envelope-heart me-3"></i>
                            Gestión de Mensajes
                        </h1>
                        <p class="mb-0">Filá Mariscales de Caballeros Templarios</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Estadísticas -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body text-center">
                        <i class="bi bi-envelope fs-1 mb-3"></i>
                        <h3><?php echo $messagesCount; ?></h3>
                        <p class="mb-0">Total Mensajes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body text-center">
                        <i class="bi bi-person-lines-fill fs-1 mb-3"></i>
                        <h3><?php echo count(array_filter($messagesList, function($msg) { return strpos($msg['filename'], 'contacto') !== false; })); ?></h3>
                        <p class="mb-0">Mensajes de Contacto</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body text-center">
                        <i class="bi bi-newspaper fs-1 mb-3"></i>
                        <h3><?php echo count(array_filter($messagesList, function($msg) { return strpos($msg['filename'], 'newsletter') !== false; })); ?></h3>
                        <p class="mb-0">Suscripciones</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body text-center">
                        <i class="bi bi-clock fs-1 mb-3"></i>
                        <h3><?php echo count(array_filter($messagesList, function($msg) { return (time() - strtotime($msg['modified'])) < 86400; })); ?></h3>
                        <p class="mb-0">Últimas 24h</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Lista de mensajes -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-list-ul me-2"></i>
                            Lista de Mensajes
                        </h5>
                        <div>
                            <a href="/prueba-php/public/admin" class="btn btn-secondary btn-sm">
                                <i class="bi bi-arrow-left me-1"></i>
                                Volver al Panel
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($messagesList)): ?>
                            <div class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                <h5 class="text-muted mt-3">No hay mensajes</h5>
                                <p class="text-muted">Los mensajes aparecerán aquí cuando los usuarios envíen formularios.</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Tipo</th>
                                            <th>Archivo</th>
                                            <th>Tamaño</th>
                                            <th>Fecha</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($messagesList as $message): ?>
                                            <tr>
                                                <td>
                                                    <?php
                                                    $filename = $message['filename'];
                                                    if (strpos($filename, 'contacto') !== false) {
                                                        echo '<span class="badge bg-danger"><i class="bi bi-person-lines-fill me-1"></i>Contacto</span>';
                                                    } elseif (strpos($filename, 'newsletter') !== false) {
                                                        echo '<span class="badge bg-success"><i class="bi bi-newspaper me-1"></i>Newsletter</span>';
                                                    } elseif (strpos($filename, 'email') !== false) {
                                                        echo '<span class="badge bg-info"><i class="bi bi-envelope me-1"></i>Email</span>';
                                                    } else {
                                                        echo '<span class="badge bg-secondary"><i class="bi bi-file-text me-1"></i>Otro</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <code><?php echo htmlspecialchars($message['filename']); ?></code>
                                                </td>
                                                <td>
                                                    <?php echo formatBytes($message['size']); ?>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        <?php echo date('d/m/Y H:i', strtotime($message['modified'])); ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-outline-primary" onclick="showMessage('<?php echo htmlspecialchars($message['filename']); ?>', `<?php echo htmlspecialchars($message['content']); ?>`)">
                                                            <i class="bi bi-eye"></i>
                                                        </button>
                                                        <a href="/prueba-php/public/admin/mensajes/download/<?php echo urlencode($message['filename']); ?>" class="btn btn-outline-success">
                                                            <i class="bi bi-download"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal para mostrar mensaje -->
    <div class="modal fade" id="messageModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-envelope me-2"></i>
                        Ver Mensaje
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="messageContent">
                        <!-- Contenido del mensaje se insertará aquí -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showMessage(filename, content) {
            // Formatear contenido según el tipo de archivo
            let formattedContent = content;
            
            if (filename.endsWith('.json')) {
                try {
                    const jsonData = JSON.parse(content);
                    formattedContent = JSON.stringify(jsonData, null, 2);
                } catch (e) {
                    formattedContent = content;
                }
            }
            
            document.getElementById('messageContent').innerHTML = `
                <div class="mb-3">
                    <h6><strong>Archivo:</strong> ${filename}</h6>
                    <small class="text-muted">Tipo: ${filename.endsWith('.json') ? 'JSON' : 'Texto'}</small>
                </div>
                <div class="border rounded p-3" style="background-color: #f8f9fa; max-height: 400px; overflow-y: auto;">
                    <pre style="white-space: pre-wrap; font-family: 'Courier New', monospace; font-size: 0.9em; margin: 0;">${formattedContent}</pre>
                </div>
            `;
            
            const modal = new bootstrap.Modal(document.getElementById('messageModal'));
            modal.show();
        }
    </script>
</body>
</html>

<?php
function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB');
    
    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }
    
    return round($bytes, $precision) . ' ' . $units[$i];
}
?>
