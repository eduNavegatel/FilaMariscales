<?php
// Script para configurar usuarios de prueba para socios
require_once 'src/config/config.php';
require_once 'src/models/Database.php';

echo "Configurando usuarios de prueba para socios...\n";

try {
    $db = new Database();
    
    // Crear usuarios específicos de socio
    $password = password_hash('socio123', PASSWORD_DEFAULT);
    
    $usuarios = [
        ['Juan Carlos', 'Martínez López', 'juan.martinez@mariscales.com'],
        ['María Isabel', 'García Fernández', 'maria.garcia@mariscales.com'],
        ['Antonio', 'Rodríguez Sánchez', 'antonio.rodriguez@mariscales.com'],
        ['Carmen', 'López Pérez', 'carmen.lopez@mariscales.com'],
        ['Francisco', 'González Ruiz', 'francisco.gonzalez@mariscales.com']
    ];
    
    foreach ($usuarios as $usuario) {
        // Verificar si el usuario ya existe
        $db->query("SELECT COUNT(*) as count FROM users WHERE email = ?");
        $db->bind(1, $usuario[2]);
        $result = $db->single();
        
        if ($result->count == 0) {
            $db->query("INSERT INTO users (nombre, apellidos, email, password, rol, activo, fecha_registro, ultimo_acceso) VALUES (?, ?, ?, ?, 'socio', 1, NOW(), NOW())");
            $db->bind(1, $usuario[0]);
            $db->bind(2, $usuario[1]);
            $db->bind(3, $usuario[2]);
            $db->bind(4, $password);
            $db->execute();
            echo "✅ Usuario creado: {$usuario[0]} {$usuario[1]} ({$usuario[2]})\n";
        } else {
            // Actualizar el rol a socio si ya existe
            $db->query("UPDATE users SET rol = 'socio', activo = 1 WHERE email = ?");
            $db->bind(1, $usuario[2]);
            $db->execute();
            echo "🔄 Usuario actualizado: {$usuario[0]} {$usuario[1]} ({$usuario[2]})\n";
        }
    }
    
    // Crear un usuario normal para probar que no puede acceder
    $db->query("SELECT COUNT(*) as count FROM users WHERE email = 'pedro.user@mariscales.com'");
    $result = $db->single();
    
    if ($result->count == 0) {
        $db->query("INSERT INTO users (nombre, apellidos, email, password, rol, activo, fecha_registro, ultimo_acceso) VALUES ('Pedro', 'Usuario Normal', 'pedro.user@mariscales.com', ?, 'user', 1, NOW(), NOW())");
        $db->bind(1, $password);
        $db->execute();
        echo "✅ Usuario normal creado: Pedro Usuario Normal (pedro.user@mariscales.com)\n";
    }
    
    // Crear un socio inactivo para probar
    $db->query("SELECT COUNT(*) as count FROM users WHERE email = 'socio.inactivo@mariscales.com'");
    $result = $db->single();
    
    if ($result->count == 0) {
        $db->query("INSERT INTO users (nombre, apellidos, email, password, rol, activo, fecha_registro, ultimo_acceso) VALUES ('Socio', 'Inactivo', 'socio.inactivo@mariscales.com', ?, 'socio', 0, NOW(), NOW())");
        $db->bind(1, $password);
        $db->execute();
        echo "✅ Socio inactivo creado: Socio Inactivo (socio.inactivo@mariscales.com)\n";
    }
    
    echo "\n📋 Usuarios de socio disponibles:\n";
    $db->query("SELECT id, nombre, apellidos, email, activo FROM users WHERE rol = 'socio'");
    $socios = $db->resultSet();
    
    foreach ($socios as $socio) {
        $status = $socio->activo ? '✅ Activo' : '❌ Inactivo';
        echo "- {$socio->nombre} {$socio->apellidos} ({$socio->email}) - {$status}\n";
    }
    
    echo "\n🔑 Contraseña para todos los usuarios: socio123\n";
    echo "\n✅ Configuración completada.\n";
    echo "🌐 Puedes acceder al área de socios en: http://localhost/prueba-php/public/socios\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
