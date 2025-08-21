<?php
// Script para diagnosticar y corregir el problema de la columna rol
echo "<h1>Diagnóstico del Problema de la Columna 'rol'</h1>";

// Verificar la estructura de la tabla
echo "<h2>Verificación de la Estructura de la Tabla 'users':</h2>";

try {
    require_once 'src/config/config.php';
    require_once 'src/models/Database.php';
    
    $db = new Database();
    echo "<p>✅ Conexión a la base de datos exitosa</p>";
    
    // Verificar tabla users
    $db->query("SHOW TABLES LIKE 'users'");
    $result = $db->single();
    
    if ($result) {
        echo "<p>✅ La tabla 'users' existe</p>";
        
        // Verificar estructura de la tabla
        $db->query("DESCRIBE users");
        $columns = $db->resultSet();
        
        echo "<p><strong>Estructura actual de la tabla 'users':</strong></p>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        
        foreach ($columns as $column) {
            $highlight = ($column->Field === 'rol') ? 'background-color: #ffeb3b;' : '';
            echo "<tr style='$highlight'>";
            echo "<td><strong>{$column->Field}</strong></td>";
            echo "<td>{$column->Type}</td>";
            echo "<td>{$column->Null}</td>";
            echo "<td>{$column->Key}</td>";
            echo "<td>{$column->Default}</td>";
            echo "<td>{$column->Extra}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Verificar específicamente la columna rol
        $rolColumn = null;
        foreach ($columns as $column) {
            if ($column->Field === 'rol') {
                $rolColumn = $column;
                break;
            }
        }
        
        if ($rolColumn) {
            echo "<h3>Análisis de la Columna 'rol':</h3>";
            echo "<p><strong>Tipo actual:</strong> {$rolColumn->Type}</p>";
            echo "<p><strong>Permite NULL:</strong> {$rolColumn->Null}</p>";
            echo "<p><strong>Valor por defecto:</strong> " . ($rolColumn->Default ?? 'NULL') . "</p>";
            
            // Verificar si el tipo es apropiado para los valores que queremos insertar
            $testValues = ['user', 'socio', 'admin'];
            $maxLength = 0;
            foreach ($testValues as $value) {
                if (strlen($value) > $maxLength) {
                    $maxLength = strlen($value);
                }
            }
            
            echo "<p><strong>Valores que queremos insertar:</strong> " . implode(', ', $testValues) . "</p>";
            echo "<p><strong>Longitud máxima de nuestros valores:</strong> $maxLength caracteres</p>";
            
            // Extraer el tamaño de la columna
            if (preg_match('/varchar\((\d+)\)/', $rolColumn->Type, $matches)) {
                $currentSize = (int)$matches[1];
                echo "<p><strong>Tamaño actual de la columna:</strong> $currentSize caracteres</p>";
                
                if ($currentSize < $maxLength) {
                    echo "<p style='color: red;'><strong>❌ PROBLEMA DETECTADO:</strong> La columna 'rol' es demasiado pequeña</p>";
                    echo "<p>Necesitamos al menos $maxLength caracteres, pero la columna solo tiene $currentSize</p>";
                    
                    // Proponer la corrección
                    echo "<h3>Corrección Necesaria:</h3>";
                    echo "<p>Ejecuta esta consulta SQL para corregir el problema:</p>";
                    echo "<div style='background-color: #f5f5f5; padding: 10px; border-radius: 5px; font-family: monospace;'>";
                    echo "ALTER TABLE users MODIFY COLUMN rol VARCHAR(10) NOT NULL DEFAULT 'user';";
                    echo "</div>";
                    
                    // Ejecutar la corrección automáticamente
                    echo "<h3>¿Quieres que ejecute la corrección automáticamente?</h3>";
                    echo "<form method='post'>";
                    echo "<input type='submit' name='fix_rol' value='Sí, corregir automáticamente' style='background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;'>";
                    echo "</form>";
                    
                } else {
                    echo "<p style='color: green;'><strong>✅ La columna 'rol' tiene el tamaño correcto</strong></p>";
                }
            } else {
                echo "<p style='color: orange;'><strong>⚠️ No se pudo determinar el tamaño de la columna 'rol'</strong></p>";
            }
            
        } else {
            echo "<p style='color: red;'><strong>❌ ERROR:</strong> La columna 'rol' no existe en la tabla 'users'</p>";
        }
        
    } else {
        echo "<p>❌ La tabla 'users' NO existe</p>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error de conexión: " . $e->getMessage() . "</p>";
}

// Procesar la corrección si se solicita
if (isset($_POST['fix_rol'])) {
    echo "<h2>Ejecutando Corrección...</h2>";
    
    try {
        $db = new Database();
        
        // Ejecutar la corrección
        $db->query("ALTER TABLE users MODIFY COLUMN rol VARCHAR(10) NOT NULL DEFAULT 'user'");
        $result = $db->execute();
        
        if ($result) {
            echo "<p style='color: green;'><strong>✅ Corrección ejecutada exitosamente</strong></p>";
            echo "<p>La columna 'rol' ha sido modificada para aceptar valores de hasta 10 caracteres.</p>";
            
            // Verificar la nueva estructura
            echo "<h3>Nueva Estructura de la Columna 'rol':</h3>";
            $db->query("DESCRIBE users");
            $columns = $db->resultSet();
            
            foreach ($columns as $column) {
                if ($column->Field === 'rol') {
                    echo "<p><strong>Tipo:</strong> {$column->Type}</p>";
                    echo "<p><strong>Permite NULL:</strong> {$column->Null}</p>";
                    echo "<p><strong>Valor por defecto:</strong> " . ($column->Default ?? 'NULL') . "</p>";
                    break;
                }
            }
            
        } else {
            echo "<p style='color: red;'><strong>❌ Error al ejecutar la corrección</strong></p>";
        }
        
    } catch (Exception $e) {
        echo "<p style='color: red;'><strong>❌ Error durante la corrección:</strong> " . $e->getMessage() . "</p>";
    }
}

// Probar la inserción después de la corrección
if (isset($_POST['fix_rol']) || !isset($_POST['fix_rol'])) {
    echo "<h2>Prueba de Inserción:</h2>";
    
    try {
        $db = new Database();
        
        // Crear un usuario de prueba
        $testData = [
            'nombre' => 'Test Usuario',
            'apellidos' => 'Rol Test',
            'email' => 'test_rol_' . time() . '@example.com',
            'password' => password_hash('123456', PASSWORD_DEFAULT),
            'rol' => 'admin',
            'activo' => 1
        ];
        
        $db->query('INSERT INTO users (nombre, apellidos, email, password, rol, activo, fecha_registro, ultimo_acceso) 
                   VALUES(:nombre, :apellidos, :email, :password, :rol, :activo, NOW(), NOW())');
        
        $db->bind(':nombre', $testData['nombre']);
        $db->bind(':apellidos', $testData['apellidos']);
        $db->bind(':email', $testData['email']);
        $db->bind(':password', $testData['password']);
        $db->bind(':rol', $testData['rol']);
        $db->bind(':activo', $testData['activo']);
        
        $result = $db->execute();
        
        if ($result) {
            echo "<p style='color: green;'><strong>✅ Prueba de inserción exitosa</strong></p>";
            echo "<p>Se insertó correctamente un usuario con rol 'admin'</p>";
            
            // Limpiar el usuario de prueba
            $db->query('DELETE FROM users WHERE email = :email');
            $db->bind(':email', $testData['email']);
            $db->execute();
            echo "<p>Usuario de prueba eliminado</p>";
            
        } else {
            echo "<p style='color: red;'><strong>❌ Error en la prueba de inserción</strong></p>";
        }
        
    } catch (Exception $e) {
        echo "<p style='color: red;'><strong>❌ Error en la prueba:</strong> " . $e->getMessage() . "</p>";
    }
}

// Instrucciones para el usuario
echo "<h2>Instrucciones:</h2>";
echo "<ol>";
echo "<li><strong>Si ves un problema con el tamaño de la columna 'rol':</strong></li>";
echo "<ul>";
echo "<li>Haz clic en 'Sí, corregir automáticamente' para ejecutar la corrección</li>";
echo "<li>O ejecuta manualmente: <code>ALTER TABLE users MODIFY COLUMN rol VARCHAR(10) NOT NULL DEFAULT 'user';</code></li>";
echo "</ul>";
echo "<li><strong>Después de la corrección:</strong></li>";
echo "<ul>";
echo "<li>Prueba crear un usuario desde el formulario de administración</li>";
echo "<li>Verifica que no aparezcan más errores de truncamiento</li>";
echo "</ul>";
echo "</ol>";

echo "<h2>Enlaces de Prueba:</h2>";
echo "<p><a href='/prueba-php/public/admin/crearUsuario' target='_blank'>🔗 Formulario de Crear Usuario</a></p>";
echo "<p><a href='/prueba-php/public/admin/usuarios' target='_blank'>🔗 Lista de Usuarios</a></p>";

echo "<p><strong>Una vez corregido el problema de la columna 'rol', el formulario de crear usuario debería funcionar correctamente.</strong></p>";
?>
