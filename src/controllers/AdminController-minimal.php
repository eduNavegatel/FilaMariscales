<?php
// AdminController MÍNIMO para identificar el problema
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cargar solo lo esencial
$adminCredPath = __DIR__ . '/../config/admin_credentials.php';
if (file_exists($adminCredPath)) {
    require_once $adminCredPath;
    error_log("AdminController-minimal: admin_credentials.php cargado");
}

// Clase mínima
class AdminController {
    
    public function __construct() {
        error_log("AdminController-minimal::__construct() iniciando");
        
        // Verificación mínima de sesión
        if (function_exists('isAdminLoggedIn')) {
            if (!isAdminLoggedIn()) {
                error_log("AdminController-minimal: Usuario NO autenticado");
                header('Location: /prueba-php/public/admin/login');
                exit;
            }
            error_log("AdminController-minimal: Usuario autenticado correctamente");
        } else {
            error_log("AdminController-minimal: ⚠️ isAdminLoggedIn no disponible");
        }
        
        error_log("AdminController-minimal::__construct() completado");
    }
    
    public function index() {
        $this->redirect('/admin/dashboard');
    }
    
    public function dashboard() {
        error_log("AdminController-minimal::dashboard() llamado");
        
        // Dashboard mínimo
        echo "<!DOCTYPE html>";
        echo "<html lang='es'>";
        echo "<head>";
        echo "<meta charset='UTF-8'>";
        echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
        echo "<title>Dashboard Mínimo - Admin</title>";
        echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
        echo "</head>";
        echo "<body>";
        echo "<div class='container mt-4'>";
        echo "<h1>🎯 Dashboard Mínimo Funcionando</h1>";
        echo "<div class='alert alert-success'>✅ AdminController funcionando correctamente</div>";
        echo "<p>Si ves esto, el problema está en el AdminController original, no en la lógica básica.</p>";
        echo "<div class='mt-4'>";
        echo "<a href='/prueba-php/public/admin/usuarios' class='btn btn-primary'>Probar Usuarios</a>";
        echo "<a href='/prueba-php/public/admin/socios' class='btn btn-secondary ms-2'>Probar Socios</a>";
        echo "</div>";
        echo "</div>";
        echo "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>";
        echo "</body>";
        echo "</html>";
        
        error_log("AdminController-minimal::dashboard() completado");
    }
    
    public function usuarios() {
        error_log("AdminController-minimal::usuarios() llamado");
        echo "<h1>👥 Usuarios - Funcionando</h1>";
        echo "<p>✅ Método usuarios funcionando correctamente</p>";
    }
    
    public function socios() {
        error_log("AdminController-minimal::socios() llamado");
        echo "<h1>🤝 Socios - Funcionando</h1>";
        echo "<p>✅ Método socios funcionando correctamente</p>";
    }
    
    private function redirect($url) {
        header('Location: ' . $url);
        exit;
    }
}
