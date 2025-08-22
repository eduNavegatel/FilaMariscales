<?php
// Script simple para corregir la columna rol
require_once 'src/config/config.php';
require_once 'src/models/Database.php';

try {
    $db = new Database();
    echo "Conectando a la base de datos...\n";
    
    // Verificar la estructura actual
    $db->query("DESCRIBE users");
    $columns = $db->resultSet();
    
    $rolColumn = null;
    foreach ($columns as $column) {
        if ($column->Field === 'rol') {
            $rolColumn = $column;
            break;
        }
    }
    
    if ($rolColumn) {
        echo "Columna 'rol' encontrada. Tipo actual: {$rolColumn->Type}\n";
        
        // Corregir la columna rol
        $fixQuery = "ALTER TABLE users MODIFY COLUMN rol ENUM('user', 'socio', 'admin') NOT NULL DEFAULT 'user'";
        echo "Ejecutando: $fixQuery\n";
        
        $db->query($fixQuery);
        $result = $db->execute();
        
        if ($result) {
            echo "✅ Corrección aplicada exitosamente\n";
            
            // Verificar la nueva estructura
            $db->query("DESCRIBE users");
            $newColumns = $db->resultSet();
            
            foreach ($newColumns as $column) {
                if ($column->Field === 'rol') {
                    echo "Nuevo tipo: {$column->Type}\n";
                    break;
                }
            }
        } else {
            echo "❌ Error al aplicar la corrección\n";
        }
    } else {
        echo "❌ La columna 'rol' no existe\n";
        
        // Crear la columna
        $createQuery = "ALTER TABLE users ADD COLUMN rol ENUM('user', 'socio', 'admin') NOT NULL DEFAULT 'user' AFTER password";
        echo "Creando columna: $createQuery\n";
        
        $db->query($createQuery);
        $result = $db->execute();
        
        if ($result) {
            echo "✅ Columna 'rol' creada exitosamente\n";
        } else {
            echo "❌ Error al crear la columna\n";
        }
    }
    
    // Probar inserción
    echo "\nProbando inserción con rol 'socio'...\n";
    
    $testEmail = 'test_socio_' . time() . '@example.com';
    
    $db->query('INSERT INTO users (nombre, apellidos, email, password, rol, activo, fecha_registro) 
               VALUES(:nombre, :apellidos, :email, :password, :rol, :activo, NOW())');
    
    $db->bind(':nombre', 'Test Usuario');
    $db->bind(':apellidos', 'Rol Socio');
    $db->bind(':email', $testEmail);
    $db->bind(':password', password_hash('123456', PASSWORD_DEFAULT));
    $db->bind(':rol', 'socio');
    $db->bind(':activo', 1);
    
    $result = $db->execute();
    
    if ($result) {
        echo "✅ Inserción con rol 'socio' exitosa\n";
        
        // Limpiar
        $db->query('DELETE FROM users WHERE email = :email');
        $db->bind(':email', $testEmail);
        $db->execute();
        echo "Usuario de prueba eliminado\n";
    } else {
        echo "❌ Error en inserción con rol 'socio'\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\nScript completado.\n";
?>

