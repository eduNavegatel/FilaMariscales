<?php
// Archivo de prueba en el directorio public
// Este debería funcionar sin problemas de permisos

echo "<h1>✅ Test de Permisos - Directorio Public</h1>";
echo "<p>Si puedes ver este mensaje, el directorio public está funcionando correctamente.</p>";
echo "<p><strong>Fecha y hora:</strong> " . date('Y-m-d H:i:s') . "</p>";
echo "<p><strong>Directorio actual:</strong> " . __DIR__ . "</p>";

// Probar conexión a base de datos
echo "<h2>🔍 Probando conexión a base de datos...</h2>";
try {
    require_once '../src/config/config.php';
    require_once '../src/config/Database.php';
    
    $db = new Database();
    $pdo = $db->getConnection();
    
    echo "<p style='color: green;'>✅ Conexión a base de datos exitosa</p>";
    
    // Obtener un usuario de prueba
    $stmt = $pdo->query("SELECT id, nombre, email, rol FROM users ORDER BY id LIMIT 1");
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "<p style='color: green;'>✅ Usuario encontrado: {$user['nombre']} ({$user['email']}) - Rol: {$user['rol']}</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ No se encontraron usuarios</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error de conexión: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h2>🚀 Enlaces de prueba:</h2>";
echo "<p><a href='admin/usuarios' style='color: #007bff;'>📋 Gestión de Usuarios</a></p>";
echo "<p><a href='../test-edicion-final-funcional.php' style='color: #007bff;'>✏️ Test de Edición (Directorio raíz)</a></p>";
echo "<p><em>✅ Este archivo está en el directorio public y debería funcionar sin problemas de permisos.</em></p>";
?>
