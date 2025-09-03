<?php
// Archivo de debug para identificar problemas con el dashboard
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>";
echo "<html lang='es'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Debug Dashboard - Filá Mariscales</title>";
echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
echo "</head>";
echo "<body>";
echo "<div class='container mt-4'>";
echo "<h1>🐛 Debug Dashboard</h1>";

try {
    // Cargar configuración
    echo "<h3>1️⃣ Cargando Configuración</h3>";
    require_once '../src/config/config.php';
    echo "<div class='alert alert-success'>✅ Configuración cargada</div>";
    
    // Verificar sesión
    echo "<h3>2️⃣ Verificando Sesión</h3>";
    echo "<div class='alert alert-info'>Estado de la sesión: " . (session_status() === PHP_SESSION_ACTIVE ? 'Activa' : 'Inactiva') . "</div>";
    
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
        echo "<div class='alert alert-warning'>⚠️ Sesión iniciada manualmente</div>";
    }
    
    echo "<div class='alert alert-info'>ID de sesión: " . session_id() . "</div>";
    echo "<div class='alert alert-info'>Datos de sesión: " . print_r($_SESSION, true) . "</div>";
    
    // Verificar funciones de administrador
    echo "<h3>3️⃣ Verificando Funciones de Administrador</h3>";
    
    if (function_exists('isAdminLoggedIn')) {
        echo "<div class='alert alert-success'>✅ Función isAdminLoggedIn existe</div>";
        $adminLoggedIn = isAdminLoggedIn();
        echo "<div class='alert alert-info'>Resultado isAdminLoggedIn(): " . ($adminLoggedIn ? 'TRUE' : 'FALSE') . "</div>";
    } else {
        echo "<div class='alert alert-danger'>❌ Función isAdminLoggedIn NO existe</div>";
    }
    
    if (function_exists('getAdminInfo')) {
        echo "<div class='alert alert-success'>✅ Función getAdminInfo existe</div>";
        $adminInfo = getAdminInfo();
        echo "<div class='alert alert-info'>Resultado getAdminInfo(): " . print_r($adminInfo, true) . "</div>";
    } else {
        echo "<div class='alert alert-danger'>❌ Función getAdminInfo NO existe</div>";
    }
    
    // Verificar archivos del AdminController
    echo "<h3>4️⃣ Verificando AdminController</h3>";
    
    $adminControllerPath = '../src/controllers/AdminController.php';
    if (file_exists($adminControllerPath)) {
        echo "<div class='alert alert-success'>✅ AdminController.php existe</div>";
        
        // Intentar cargar el AdminController
        try {
            require_once $adminControllerPath;
            echo "<div class='alert alert-success'>✅ AdminController cargado correctamente</div>";
            
            // Verificar si la clase existe
            if (class_exists('AdminController')) {
                echo "<div class='alert alert-success'>✅ Clase AdminController existe</div>";
                
                // Intentar crear una instancia
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
                }
                
            } else {
                echo "<div class='alert alert-danger'>❌ Clase AdminController NO existe</div>";
            }
            
        } catch (Exception $e) {
            echo "<div class='alert alert-danger'>❌ Error al cargar AdminController: " . $e->getMessage() . "</div>";
        }
        
    } else {
        echo "<div class='alert alert-danger'>❌ AdminController.php NO existe en: " . $adminControllerPath . "</div>";
    }
    
    // Verificar modelos
    echo "<h3>5️⃣ Verificando Modelos</h3>";
    
    $userModelPath = '../src/models/User.php';
    if (file_exists($userModelPath)) {
        echo "<div class='alert alert-success'>✅ User.php existe</div>";
        
        try {
            require_once $userModelPath;
            echo "<div class='alert alert-success'>✅ User.php cargado</div>";
            
            if (class_exists('User')) {
                echo "<div class='alert alert-success'>✅ Clase User existe</div>";
                
                try {
                    $userModel = new User();
                    echo "<div class='alert alert-success'>✅ Instancia de User creada</div>";
                    
                    // Probar método
                    $userCount = $userModel->getUserCount();
                    echo "<div class='alert alert-info'>Conteo de usuarios: " . $userCount . "</div>";
                    
                } catch (Exception $e) {
                    echo "<div class='alert alert-danger'>❌ Error al crear instancia de User: " . $e->getMessage() . "</div>";
                }
                
            } else {
                echo "<div class='alert alert-danger'>❌ Clase User NO existe</div>";
            }
            
        } catch (Exception $e) {
            echo "<div class='alert alert-danger'>❌ Error al cargar User.php: " . $e->getMessage() . "</div>";
        }
        
    } else {
        echo "<div class='alert alert-danger'>❌ User.php NO existe</div>";
    }
    
    // Verificar base de datos
    echo "<h3>6️⃣ Verificando Base de Datos</h3>";
    
    $databasePath = '../src/models/Database.php';
    if (file_exists($databasePath)) {
        echo "<div class='alert alert-success'>✅ Database.php existe</div>";
        
        try {
            require_once $databasePath;
            echo "<div class='alert alert-success'>✅ Database.php cargado</div>";
            
            if (class_exists('Database')) {
                echo "<div class='alert alert-success'>✅ Clase Database existe</div>";
                
                try {
                    $db = new Database();
                    echo "<div class='alert alert-success'>✅ Conexión a base de datos establecida</div>";
                    
                    // Probar consulta simple
                    $db->query("SELECT COUNT(*) as count FROM users");
                    $result = $db->single();
                    echo "<div class='alert alert-info'>Test de base de datos: " . $result->count . " usuarios</div>";
                    
                } catch (Exception $e) {
                    echo "<div class='alert alert-danger'>❌ Error de base de datos: " . $e->getMessage() . "</div>";
                }
                
            } else {
                echo "<div class='alert alert-danger'>❌ Clase Database NO existe</div>";
            }
            
        } catch (Exception $e) {
            echo "<div class='alert alert-danger'>❌ Error al cargar Database.php: " . $e->getMessage() . "</div>";
        }
        
    } else {
        echo "<div class='alert alert-danger'>❌ Database.php NO existe</div>";
    }
    
    // Verificar vistas
    echo "<h3>7️⃣ Verificando Vistas</h3>";
    
    $dashboardViewPath = '../src/views/admin/dashboard.php';
    if (file_exists($dashboardViewPath)) {
        echo "<div class='alert alert-success'>✅ Vista dashboard.php existe</div>";
        
        // Verificar contenido del archivo
        $viewContent = file_get_contents($dashboardViewPath);
        if (strlen($viewContent) > 0) {
            echo "<div class='alert alert-success'>✅ Vista dashboard.php tiene contenido (" . strlen($viewContent) . " caracteres)</div>";
        } else {
            echo "<div class='alert alert-danger'>❌ Vista dashboard.php está vacía</div>";
        }
        
    } else {
        echo "<div class='alert alert-danger'>❌ Vista dashboard.php NO existe en: " . $dashboardViewPath . "</div>";
    }
    
    $usersViewPath = '../src/views/admin/users/index.php';
    if (file_exists($usersViewPath)) {
        echo "<div class='alert alert-success'>✅ Vista users/index.php existe</div>";
    } else {
        echo "<div class='alert alert-danger'>❌ Vista users/index.php NO existe</div>";
    }
    
    // Verificar layout
    $adminLayoutPath = '../src/views/layouts/admin.php';
    if (file_exists($adminLayoutPath)) {
        echo "<div class='alert alert-success'>✅ Layout admin.php existe</div>";
    } else {
        echo "<div class='alert alert-danger'>❌ Layout admin.php NO existe</div>";
    }
    
    // Información del sistema
    echo "<h3>8️⃣ Información del Sistema</h3>";
    echo "<div class='card'>";
    echo "<div class='card-body'>";
    echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";
    echo "<p><strong>Server Software:</strong> " . ($_SERVER['SERVER_SOFTWARE'] ?? 'No disponible') . "</p>";
    echo "<p><strong>Document Root:</strong> " . ($_SERVER['DOCUMENT_ROOT'] ?? 'No disponible') . "</p>";
    echo "<p><strong>Script Name:</strong> " . ($_SERVER['SCRIPT_NAME'] ?? 'No disponible') . "</p>";
    echo "<p><strong>Current Working Directory:</strong> " . getcwd() . "</p>";
    echo "</div>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>❌ Error general: " . $e->getMessage() . "</div>";
    echo "<div class='alert alert-info'>📋 Stack trace:</div>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<div class='mt-4'>";
echo "<a href='/prueba-php/public/admin/login' class='btn btn-primary'>Ir al Login</a>";
echo "<a href='/prueba-php/public/admin/dashboard' class='btn btn-secondary ms-2'>Probar Dashboard</a>";
echo "<a href='/prueba-php/public/test-db-users.php' class='btn btn-info ms-2'>Test Base de Datos</a>";
echo "</div>";

echo "</div>";
echo "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>";
echo "</body>";
echo "</html>";
