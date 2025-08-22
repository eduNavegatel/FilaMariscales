<?php
// Script para verificar y corregir la columna rol en la base de datos
echo "<h1>🔧 Corrección de la Columna 'rol' en la Base de Datos</h1>";

try {
    require_once 'src/config/config.php';
    require_once 'src/models/Database.php';
    
    $db = new Database();
    echo "<p>✅ Conexión a la base de datos exitosa</p>";
    
    // Verificar la estructura actual de la tabla users
    echo "<h2>📋 Estructura Actual de la Tabla 'users':</h2>";
    
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
        echo "<p><strong>Columna 'rol' encontrada:</strong></p>";
        echo "<ul>";
        echo "<li><strong>Tipo:</strong> {$rolColumn->Type}</li>";
        echo "<li><strong>Permite NULL:</strong> {$rolColumn->Null}</li>";
        echo "<li><strong>Valor por defecto:</strong> " . ($rolColumn->Default ?? 'NULL') . "</li>";
        echo "</ul>";
        
        // Verificar si el tipo es correcto
        $needsFix = false;
        $currentType = strtolower($rolColumn->Type);
        
        if (strpos($currentType, 'enum') !== false) {
            // Es un ENUM, verificar si incluye 'socio'
            if (strpos($currentType, 'socio') === false) {
                echo "<p style='color: red;'><strong>❌ PROBLEMA:</strong> El ENUM no incluye 'socio'</p>";
                $needsFix = true;
            } else {
                echo "<p style='color: green;'><strong>✅ El ENUM incluye 'socio'</strong></p>";
            }
        } elseif (strpos($currentType, 'varchar') !== false) {
            // Es un VARCHAR, verificar el tamaño
            if (preg_match('/varchar\((\d+)\)/', $currentType, $matches)) {
                $size = (int)$matches[1];
                if ($size < 10) {
                    echo "<p style='color: red;'><strong>❌ PROBLEMA:</strong> VARCHAR demasiado pequeño ($size caracteres)</p>";
                    $needsFix = true;
                } else {
                    echo "<p style='color: green;'><strong>✅ VARCHAR tiene tamaño correcto ($size caracteres)</strong></p>";
                }
            }
        } else {
            echo "<p style='color: orange;'><strong>⚠️ Tipo de columna inesperado: $currentType</strong></p>";
            $needsFix = true;
        }
        
        if ($needsFix) {
            echo "<h3>🔧 Aplicando Corrección...</h3>";
            
            // Corregir la columna rol
            $fixQuery = "ALTER TABLE users MODIFY COLUMN rol ENUM('user', 'socio', 'admin') NOT NULL DEFAULT 'user'";
            echo "<p>Ejecutando: <code>$fixQuery</code></p>";
            
            $db->query($fixQuery);
            $result = $db->execute();
            
            if ($result) {
                echo "<p style='color: green;'><strong>✅ Corrección aplicada exitosamente</strong></p>";
                
                // Verificar la nueva estructura
                echo "<h3>📋 Nueva Estructura:</h3>";
                $db->query("DESCRIBE users");
                $newColumns = $db->resultSet();
                
                foreach ($newColumns as $column) {
                    if ($column->Field === 'rol') {
                        echo "<ul>";
                        echo "<li><strong>Tipo:</strong> {$column->Type}</li>";
                        echo "<li><strong>Permite NULL:</strong> {$column->Null}</li>";
                        echo "<li><strong>Valor por defecto:</strong> " . ($column->Default ?? 'NULL') . "</li>";
                        echo "</ul>";
                        break;
                    }
                }
            } else {
                echo "<p style='color: red;'><strong>❌ Error al aplicar la corrección</strong></p>";
            }
        } else {
            echo "<p style='color: green;'><strong>✅ La columna 'rol' ya está correctamente configurada</strong></p>";
        }
        
    } else {
        echo "<p style='color: red;'><strong>❌ ERROR:</strong> La columna 'rol' no existe en la tabla 'users'</p>";
        echo "<p>Creando la columna...</p>";
        
        $createQuery = "ALTER TABLE users ADD COLUMN rol ENUM('user', 'socio', 'admin') NOT NULL DEFAULT 'user' AFTER password";
        $db->query($createQuery);
        $result = $db->execute();
        
        if ($result) {
            echo "<p style='color: green;'><strong>✅ Columna 'rol' creada exitosamente</strong></p>";
        } else {
            echo "<p style='color: red;'><strong>❌ Error al crear la columna</strong></p>";
        }
    }
    
    // Probar la inserción con diferentes roles
    echo "<h2>🧪 Prueba de Funcionamiento:</h2>";
    
    $testRoles = ['user', 'socio', 'admin'];
    $successCount = 0;
    
    foreach ($testRoles as $rol) {
        try {
            // Crear usuario de prueba
            $testEmail = 'test_' . $rol . '_' . time() . '@example.com';
            
            $db->query('INSERT INTO users (nombre, apellidos, email, password, rol, activo, fecha_registro) 
                       VALUES(:nombre, :apellidos, :email, :password, :rol, :activo, NOW())');
            
            $db->bind(':nombre', 'Test Usuario');
            $db->bind(':apellidos', 'Rol ' . ucfirst($rol));
            $db->bind(':email', $testEmail);
            $db->bind(':password', password_hash('123456', PASSWORD_DEFAULT));
            $db->bind(':rol', $rol);
            $db->bind(':activo', 1);
            
            $result = $db->execute();
            
            if ($result) {
                echo "<p style='color: green;'>✅ Rol '$rol' - Inserción exitosa</p>";
                $successCount++;
                
                // Limpiar usuario de prueba
                $db->query('DELETE FROM users WHERE email = :email');
                $db->bind(':email', $testEmail);
                $db->execute();
            } else {
                echo "<p style='color: red;'>❌ Rol '$rol' - Error en inserción</p>";
            }
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Rol '$rol' - Error: " . $e->getMessage() . "</p>";
        }
    }
    
    if ($successCount === count($testRoles)) {
        echo "<p style='color: green;'><strong>🎉 ¡Todos los roles funcionan correctamente!</strong></p>";
    } else {
        echo "<p style='color: red;'><strong>⚠️ Algunos roles no funcionan correctamente</strong></p>";
    }
    
    // Mostrar usuarios existentes
    echo "<h2>👥 Usuarios Existentes:</h2>";
    
    $db->query('SELECT id, nombre, email, rol, activo FROM users ORDER BY id LIMIT 10');
    $users = $db->resultSet();
    
    if (count($users) > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Activo</th></tr>";
        
        foreach ($users as $user) {
            $rolBadge = match($user->rol) {
                'admin' => '<span style="background: #dc3545; color: white; padding: 2px 6px; border-radius: 3px;">Admin</span>',
                'socio' => '<span style="background: #007bff; color: white; padding: 2px 6px; border-radius: 3px;">Socio</span>',
                'user' => '<span style="background: #6c757d; color: white; padding: 2px 6px; border-radius: 3px;">Usuario</span>',
                default => '<span style="background: #ffc107; color: black; padding: 2px 6px; border-radius: 3px;">' . htmlspecialchars($user->rol) . '</span>'
            };
            
            echo "<tr>";
            echo "<td>{$user->id}</td>";
            echo "<td>" . htmlspecialchars($user->nombre) . "</td>";
            echo "<td>" . htmlspecialchars($user->email) . "</td>";
            echo "<td>$rolBadge</td>";
            echo "<td>" . ($user->activo ? '✅' : '❌') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No hay usuarios en la base de datos</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'><strong>❌ Error:</strong> " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h2>🔗 Enlaces:</h2>";
echo "<p><a href='/prueba-php/public/admin/usuarios' target='_blank'>🚀 Ir a Gestión de Usuarios</a></p>";
echo "<p><a href='/prueba-php/public/admin/dashboard' target='_blank'>🏠 Ir al Dashboard</a></p>";

echo "<p><em>Script completado. Verifica que todos los roles funcionen correctamente.</em></p>";
?>

