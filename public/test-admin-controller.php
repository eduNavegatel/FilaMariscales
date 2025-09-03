<?php
// Test simple del AdminController
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>";
echo "<html lang='es'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Test AdminController - Filá Mariscales</title>";
echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
echo "</head>";
echo "<body>";
echo "<div class='container mt-4'>";
echo "<h1>🧪 Test AdminController</h1>";

try {
    echo "<h3>1️⃣ Cargando Archivos</h3>";
    
    // Cargar configuración
    require_once '../src/config/config.php';
    echo "<div class='alert alert-success'>✅ config.php cargado</div>";
    
    // Cargar admin_credentials
    require_once '../src/config/admin_credentials.php';
    echo "<div class='alert alert-success'>✅ admin_credentials.php cargado</div>";
    
    // Verificar funciones
    echo "<h3>2️⃣ Verificando Funciones</h3>";
    echo "<div class='alert alert-info'>isAdminLoggedIn existe: " . (function_exists('isAdminLoggedIn') ? 'SÍ' : 'NO') . "</div>";
    echo "<div class='alert alert-info'>getAdminInfo existe: " . (function_exists('getAdminInfo') ? 'SÍ' : 'NO') . "</div>";
    
    // Cargar AdminController
    echo "<h3>3️⃣ Cargando AdminController</h3>";
    require_once '../src/controllers/AdminController.php';
    echo "<div class='alert alert-success'>✅ AdminController.php cargado</div>";
    
    // Verificar clase
    if (class_exists('AdminController')) {
        echo "<div class='alert alert-success'>✅ Clase AdminController existe</div>";
        
        // Intentar crear instancia
        try {
            $adminController = new AdminController();
            echo "<div class='alert alert-success'>✅ Instancia de AdminController creada</div>";
            
            // Verificar métodos
            if (method_exists($adminController, 'dashboard')) {
                echo "<div class='alert alert-success'>✅ Método dashboard existe</div>";
            } else {
                echo "<div class='alert alert-danger'>❌ Método dashboard NO existe</div>";
            }
            
            if (method_exists($adminController, 'usuarios')) {
                echo "<div class='alert alert-success'>✅ Método usuarios existe</div>";
            } else {
                echo "<div class='alert alert-danger'>❌ Método usuarios NO existe</div>";
            }
            
        } catch (Exception $e) {
            echo "<div class='alert alert-danger'>❌ Error al crear instancia: " . $e->getMessage() . "</div>";
            echo "<div class='alert alert-info'>📋 Stack trace:</div>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
        }
        
    } else {
        echo "<div class='alert alert-danger'>❌ Clase AdminController NO existe</div>";
    }
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>❌ Error general: " . $e->getMessage() . "</div>";
    echo "<div class='alert alert-info'>📋 Stack trace:</div>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<div class='mt-4'>";
echo "<a href='/prueba-php/public/admin/' class='btn btn-primary'>Probar Admin</a>";
echo "<a href='/prueba-php/public/admin/dashboard' class='btn btn-secondary ms-2'>Probar Dashboard</a>";
echo "<a href='/prueba-php/public/admin/usuarios' class='btn btn-info ms-2'>Probar Usuarios</a>";
echo "</div>";

echo "</div>";
echo "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>";
echo "</body>";
echo "</html>";
