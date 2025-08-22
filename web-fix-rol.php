<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corrección de Columna Rol</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        .info { color: blue; }
        .code { background: #f5f5f5; padding: 10px; border-radius: 5px; font-family: monospace; }
        .btn { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; margin: 5px; }
        .btn:hover { background: #0056b3; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
    </style>
</head>
<body>
    <h1>🔧 Corrección de la Columna 'rol' en la Base de Datos</h1>
    
    <?php
    if (isset($_POST['fix_rol'])) {
        try {
            require_once 'src/config/config.php';
            require_once 'src/models/Database.php';
            
            $db = new Database();
            echo "<p class='success'>✅ Conexión a la base de datos exitosa</p>";
            
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
                echo "<h2>📋 Estado Actual de la Columna 'rol':</h2>";
                echo "<ul>";
                echo "<li><strong>Tipo:</strong> {$rolColumn->Type}</li>";
                echo "<li><strong>Permite NULL:</strong> {$rolColumn->Null}</li>";
                echo "<li><strong>Valor por defecto:</strong> " . ($rolColumn->Default ?? 'NULL') . "</li>";
                echo "</ul>";
                
                // Corregir la columna rol
                $fixQuery = "ALTER TABLE users MODIFY COLUMN rol ENUM('user', 'socio', 'admin') NOT NULL DEFAULT 'user'";
                echo "<h3>🔧 Aplicando Corrección...</h3>";
                echo "<p>Ejecutando: <code class='code'>$fixQuery</code></p>";
                
                $db->query($fixQuery);
                $result = $db->execute();
                
                if ($result) {
                    echo "<p class='success'><strong>✅ Corrección aplicada exitosamente</strong></p>";
                    
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
                    
                    // Probar inserción con rol 'socio'
                    echo "<h3>🧪 Probando Inserción con Rol 'socio':</h3>";
                    
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
                        echo "<p class='success'>✅ Inserción con rol 'socio' exitosa</p>";
                        
                        // Limpiar usuario de prueba
                        $db->query('DELETE FROM users WHERE email = :email');
                        $db->bind(':email', $testEmail);
                        $db->execute();
                        echo "<p class='info'>Usuario de prueba eliminado</p>";
                        
                        echo "<h3 class='success'>🎉 ¡Corrección Completada!</h3>";
                        echo "<p>Ahora puedes usar el rol 'socio' en el panel de administración.</p>";
                        
                    } else {
                        echo "<p class='error'>❌ Error en inserción con rol 'socio'</p>";
                    }
                    
                } else {
                    echo "<p class='error'><strong>❌ Error al aplicar la corrección</strong></p>";
                }
            } else {
                echo "<p class='error'><strong>❌ ERROR:</strong> La columna 'rol' no existe en la tabla 'users'</p>";
                echo "<p>Creando la columna...</p>";
                
                $createQuery = "ALTER TABLE users ADD COLUMN rol ENUM('user', 'socio', 'admin') NOT NULL DEFAULT 'user' AFTER password";
                $db->query($createQuery);
                $result = $db->execute();
                
                if ($result) {
                    echo "<p class='success'><strong>✅ Columna 'rol' creada exitosamente</strong></p>";
                } else {
                    echo "<p class='error'><strong>❌ Error al crear la columna</strong></p>";
                }
            }
            
        } catch (Exception $e) {
            echo "<p class='error'><strong>❌ Error:</strong> " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p>Este script corregirá la columna 'rol' en la tabla 'users' para permitir el valor 'socio'.</p>";
        echo "<p class='warning'><strong>⚠️ Advertencia:</strong> Esta operación modificará la estructura de la base de datos.</p>";
        
        echo "<form method='post'>";
        echo "<button type='submit' name='fix_rol' class='btn btn-danger'>🔧 Corregir Columna Rol</button>";
        echo "</form>";
    }
    ?>
    
    <hr>
    <h2>🔗 Enlaces:</h2>
    <a href="/prueba-php/public/admin/usuarios" class="btn" target="_blank">🚀 Ir a Gestión de Usuarios</a>
    <a href="/prueba-php/public/admin/dashboard" class="btn" target="_blank">🏠 Ir al Dashboard</a>
    
    <p><em>Script completado. Verifica que todos los roles funcionen correctamente.</em></p>
</body>
</html>

