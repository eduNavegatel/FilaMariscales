<?php
// Test simple de base de datos
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔍 Test Simple de Base de Datos</h1>";

// Paso 1: Verificar que PHP funciona
echo "<h2>1. Verificando PHP...</h2>";
echo "<p>✅ PHP está funcionando</p>";
echo "<p>Versión: " . phpversion() . "</p>";

// Paso 2: Verificar que podemos incluir archivos
echo "<h2>2. Verificando inclusión de archivos...</h2>";
try {
    if (file_exists('../src/config/config.php')) {
        echo "<p>✅ Archivo config.php existe</p>";
    } else {
        echo "<p>❌ Archivo config.php NO existe</p>";
        echo "<p>Ruta buscada: " . realpath('../src/config/config.php') . "</p>";
    }
    
    if (file_exists('../src/config/Database.php')) {
        echo "<p>✅ Archivo Database.php existe</p>";
    } else {
        echo "<p>❌ Archivo Database.php NO existe</p>";
        echo "<p>Ruta buscada: " . realpath('../src/config/Database.php') . "</p>";
    }
} catch (Exception $e) {
    echo "<p>❌ Error al verificar archivos: " . $e->getMessage() . "</p>";
}

// Paso 3: Verificar conexión a base de datos
echo "<h2>3. Verificando conexión a base de datos...</h2>";
try {
    // Intentar conexión directa con PDO
    $host = 'localhost';
    $dbname = 'mariscales_db';
    $username = 'root';
    $password = '';
    
    echo "<p>Intentando conectar a: {$host}/{$dbname}</p>";
    
    $pdo = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color: green;'>✅ Conexión directa a base de datos exitosa</p>";
    
    // Probar consulta simple
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<p>✅ Consulta exitosa: {$result['total']} usuarios encontrados</p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Error de conexión PDO: " . $e->getMessage() . "</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error general: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><em>Test completado. Revisa los mensajes para identificar el problema.</em></p>";
?>
