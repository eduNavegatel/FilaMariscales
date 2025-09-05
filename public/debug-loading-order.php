<?php
// Debug del orden de carga de archivos
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>";
echo "<html lang='es'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Debug Orden de Carga - Filá Mariscales</title>";
echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
echo "</head>";
echo "<body>";
echo "<div class='container mt-4'>";
echo "<h1>🔍 Debug Orden de Carga de Archivos</h1>";

try {
    echo "<h3>1️⃣ Estado Inicial</h3>";
    echo "<div class='alert alert-info'>Estado de la sesión: " . (session_status() === PHP_SESSION_ACTIVE ? 'Activa' : 'Inactiva') . "</div>";
    
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
        echo "<div class='alert alert-warning'>⚠️ Sesión iniciada manualmente</div>";
    }
    
    echo "<div class='alert alert-info'>Datos de sesión: " . print_r($_SESSION, true) . "</div>";
    
    // Verificar funciones ANTES de cargar archivos
    echo "<h3>2️⃣ Funciones ANTES de Cargar Archivos</h3>";
    echo "<div class='alert alert-info'>isAdminLoggedIn existe: " . (function_exists('isAdminLoggedIn') ? 'SÍ' : 'NO') . "</div>";
    echo "<div class='alert alert-info'>getAdminInfo existe: " . (function_exists('getAdminInfo') ? 'SÍ' : 'NO') . "</div>";
    
    // Cargar archivos uno por uno y verificar
    echo "<h3>3️⃣ Cargando Archivos Paso a Paso</h3>";
    
    // Paso 1: Configuración
    echo "<h4>Paso 1: Configuración</h4>";
    $configPath = '../src/config/config.php';
    if (file_exists($configPath)) {
        echo "<div class='alert alert-success'>✅ config.php existe</div>";
        require_once $configPath;
        echo "<div class='alert alert-success'>✅ config.php cargado</div>";
    } else {
        echo "<div class='alert alert-danger'>❌ config.php NO existe</div>";
    }
    
    // Paso 2: Verificar funciones después de config
    echo "<h4>Después de config.php:</h4>";
    echo "<div class='alert alert-info'>isAdminLoggedIn existe: " . (function_exists('isAdminLoggedIn') ? 'SÍ' : 'NO') . "</div>";
    echo "<div class='alert alert-info'>getAdminInfo existe: " . (function_exists('getAdminInfo') ? 'SÍ' : 'NO') . "</div>";
    
    // Paso 3: Admin Credentials
    echo "<h4>Paso 2: Admin Credentials</h4>";
    $adminCredPath = '../src/config/admin_credentials.php';
    if (file_exists($adminCredPath)) {
        echo "<div class='alert alert-success'>✅ admin_credentials.php existe</div>";
        
        // Leer contenido del archivo
        $fileContent = file_get_contents($adminCredPath);
        echo "<div class='alert alert-info'>Tamaño del archivo: " . strlen($fileContent) . " caracteres</div>";
        
        // Verificar si contiene las funciones
        if (strpos($fileContent, 'function isAdminLoggedIn') !== false) {
            echo "<div class='alert alert-success'>✅ Función isAdminLoggedIn encontrada en el archivo</div>";
        } else {
            echo "<div class='alert alert-danger'>❌ Función isAdminLoggedIn NO encontrada en el archivo</div>";
        }
        
        if (strpos($fileContent, 'function getAdminInfo') !== false) {
            echo "<div class='alert alert-success'>✅ Función getAdminInfo encontrada en el archivo</div>";
        } else {
            echo "<div class='alert alert-danger'>❌ Función getAdminInfo NO encontrada en el archivo</div>";
        }
        
        // Cargar el archivo
        require_once $adminCredPath;
        echo "<div class='alert alert-success'>✅ admin_credentials.php cargado</div>";
        
    } else {
        echo "<div class='alert alert-danger'>❌ admin_credentials.php NO existe</div>";
    }
    
    // Paso 4: Verificar funciones después de admin_credentials
    echo "<h4>Después de admin_credentials.php:</h4>";
    echo "<div class='alert alert-info'>isAdminLoggedIn existe: " . (function_exists('isAdminLoggedIn') ? 'SÍ' : 'NO') . "</div>";
    echo "<div class='alert alert-info'>getAdminInfo existe: " . (function_exists('getAdminInfo') ? 'SÍ' : 'NO') . "</div>";
    
    // Paso 5: Probar las funciones si existen
    if (function_exists('isAdminLoggedIn')) {
        echo "<h4>Probando Función isAdminLoggedIn:</h4>";
        try {
            $result = isAdminLoggedIn();
            echo "<div class='alert alert-success'>✅ isAdminLoggedIn() ejecutada: " . ($result ? 'TRUE' : 'FALSE') . "</div>";
        } catch (Exception $e) {
            echo "<div class='alert alert-danger'>❌ Error en isAdminLoggedIn(): " . $e->getMessage() . "</div>";
        }
    }
    
    if (function_exists('getAdminInfo')) {
        echo "<h4>Probando Función getAdminInfo:</h4>";
        try {
            $result = getAdminInfo();
            echo "<div class='alert alert-success'>✅ getAdminInfo() ejecutada: " . print_r($result, true) . "</div>";
        } catch (Exception $e) {
            echo "<div class='alert alert-danger'>❌ Error en getAdminInfo(): " . $e->getMessage() . "</div>";
        }
    }
    
    // Paso 6: Intentar cargar AdminController
    echo "<h3>4️⃣ Cargando AdminController</h3>";
    $adminControllerPath = '../src/controllers/AdminController.php';
    if (file_exists($adminControllerPath)) {
        echo "<div class='alert alert-success'>✅ AdminController.php existe</div>";
        
        // Verificar funciones ANTES de cargar AdminController
        echo "<h4>Funciones ANTES de cargar AdminController:</h4>";
        echo "<div class='alert alert-info'>isAdminLoggedIn existe: " . (function_exists('isAdminLoggedIn') ? 'SÍ' : 'NO') . "</div>";
        echo "<div class='alert alert-info'>getAdminInfo existe: " . (function_exists('getAdminInfo') ? 'SÍ' : 'NO') . "</div>";
        
        try {
            require_once $adminControllerPath;
            echo "<div class='alert alert-success'>✅ AdminController cargado</div>";
            
            // Verificar funciones DESPUÉS de cargar AdminController
            echo "<h4>Funciones DESPUÉS de cargar AdminController:</h4>";
            echo "<div class='alert alert-info'>isAdminLoggedIn existe: " . (function_exists('isAdminLoggedIn') ? 'SÍ' : 'NO') . "</div>";
            echo "<div class='alert alert-info'>getAdminInfo existe: " . (function_exists('getAdminInfo') ? 'SÍ' : 'NO') . "</div>";
            
            if (class_exists('AdminController')) {
                echo "<div class='alert alert-success'>✅ Clase AdminController existe</div>";
                
                // Intentar crear instancia
                try {
                    $adminController = new AdminController();
                    echo "<div class='alert alert-success'>✅ Instancia de AdminController creada</div>";
                    
                    // Probar método dashboard
                    if (method_exists($adminController, 'dashboard')) {
                        echo "<div class='alert alert-success'>✅ Método dashboard existe</div>";
                        
                        // Intentar llamar al método
                        try {
                            ob_start();
                            $adminController->dashboard();
                            $output = ob_get_clean();
                            echo "<div class='alert alert-success'>✅ Método dashboard ejecutado</div>";
                            echo "<div class='alert alert-info'>Output length: " . strlen($output) . " caracteres</div>";
                        } catch (Exception $e) {
                            echo "<div class='alert alert-danger'>❌ Error ejecutando dashboard: " . $e->getMessage() . "</div>";
                        }
                        
                    } else {
                        echo "<div class='alert alert-danger'>❌ Método dashboard NO existe</div>";
                    }
                    
                } catch (Exception $e) {
                    echo "<div class='alert alert-danger'>❌ Error creando instancia: " . $e->getMessage() . "</div>";
                }
                
            } else {
                echo "<div class='alert alert-danger'>❌ Clase AdminController NO existe</div>";
            }
            
        } catch (Exception $e) {
            echo "<div class='alert alert-danger'>❌ Error cargando AdminController: " . $e->getMessage() . "</div>";
            echo "<div class='alert alert-info'>📋 Stack trace:</div>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
        }
        
    } else {
        echo "<div class='alert alert-danger'>❌ AdminController.php NO existe</div>";
    }
    
    // Información final
    echo "<h3>5️⃣ Estado Final</h3>";
    echo "<div class='alert alert-info'>Funciones disponibles al final:</div>";
    echo "<div class='alert alert-info'>isAdminLoggedIn: " . (function_exists('isAdminLoggedIn') ? 'SÍ' : 'NO') . "</div>";
    echo "<div class='alert alert-info'>getAdminInfo: " . (function_exists('getAdminInfo') ? 'SÍ' : 'NO') . "</div>";
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>❌ Error general: " . $e->getMessage() . "</div>";
    echo "<div class='alert alert-info'>📋 Stack trace:</div>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<div class='mt-4'>";
echo "<a href='/prueba-php/public/admin/login' class='btn btn-primary'>Ir al Login</a>";
echo "<a href='/prueba-php/public/admin/dashboard' class='btn btn-secondary ms-2'>Probar Dashboard</a>";
echo "<a href='/prueba-php/public/debug-dashboard.php' class='btn btn-info ms-2'>Debug Dashboard</a>";
echo "</div>";

echo "</div>";
echo "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>";
echo "</body>";
echo "</html>";
