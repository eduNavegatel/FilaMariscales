<?php
// Script para verificar la estructura de la base de datos y el problema de la página de socios
echo "<h1>🔍 Verificación de Estructura de Base de Datos - Página de Socios</h1>";

try {
    require_once 'src/config/config.php';
    require_once 'src/models/Database.php';
    
    $db = new Database();
    echo "<p>✅ Conexión a la base de datos exitosa</p>";
    
    // Verificar estructura de la tabla users
    echo "<h2>📋 Estructura de la Tabla 'users':</h2>";
    $db->query("DESCRIBE users");
    $columns = $db->resultSet();
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>NULL</th><th>Clave</th><th>Por Defecto</th><th>Extra</th></tr>";
    
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>{$column->Field}</td>";
        echo "<td>{$column->Type}</td>";
        echo "<td>{$column->Null}</td>";
        echo "<td>{$column->Key}</td>";
        echo "<td>" . ($column->Default ?? 'NULL') . "</td>";
        echo "<td>{$column->Extra}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Verificar estructura de la tabla eventos
    echo "<h2>📋 Estructura de la Tabla 'eventos':</h2>";
    $db->query("DESCRIBE eventos");
    $eventColumns = $db->resultSet();
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>NULL</th><th>Clave</th><th>Por Defecto</th><th>Extra</th></tr>";
    
    foreach ($eventColumns as $column) {
        echo "<tr>";
        echo "<td>{$column->Field}</td>";
        echo "<td>{$column->Type}</td>";
        echo "<td>{$column->Null}</td>";
        echo "<td>{$column->Key}</td>";
        echo "<td>" . ($column->Default ?? 'NULL') . "</td>";
        echo "<td>{$column->Extra}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Probar consultas que usa la página de socios
    echo "<h2>🧪 Prueba de Consultas de la Página de Socios:</h2>";
    
    // Consulta 1: Contar socios activos
    echo "<h3>1. Contar socios activos:</h3>";
    
    // Probar con 'estado'
    try {
        $db->query("SELECT COUNT(*) as count FROM users WHERE rol = 'socio' AND estado = 'activo'");
        $result = $db->single();
        echo "<p>✅ Con 'estado': " . ($result ? $result->count : 'Error') . "</p>";
    } catch (Exception $e) {
        echo "<p>❌ Error con 'estado': " . $e->getMessage() . "</p>";
    }
    
    // Probar con 'activo'
    try {
        $db->query("SELECT COUNT(*) as count FROM users WHERE rol = 'socio' AND activo = 1");
        $result = $db->single();
        echo "<p>✅ Con 'activo': " . ($result ? $result->count : 'Error') . "</p>";
    } catch (Exception $e) {
        echo "<p>❌ Error con 'activo': " . $e->getMessage() . "</p>";
    }
    
    // Consulta 2: Obtener primer socio activo
    echo "<h3>2. Obtener primer socio activo:</h3>";
    
    // Probar con 'estado'
    try {
        $db->query("SELECT * FROM users WHERE rol = 'socio' AND estado = 'activo' ORDER BY fecha_registro ASC LIMIT 1");
        $result = $db->single();
        if ($result) {
            echo "<p>✅ Con 'estado': Usuario encontrado - {$result->nombre} {$result->apellidos}</p>";
        } else {
            echo "<p>⚠️ Con 'estado': No se encontraron usuarios</p>";
        }
    } catch (Exception $e) {
        echo "<p>❌ Error con 'estado': " . $e->getMessage() . "</p>";
    }
    
    // Probar con 'activo'
    try {
        $db->query("SELECT * FROM users WHERE rol = 'socio' AND activo = 1 ORDER BY fecha_registro ASC LIMIT 1");
        $result = $db->single();
        if ($result) {
            echo "<p>✅ Con 'activo': Usuario encontrado - {$result->nombre} {$result->apellidos}</p>";
        } else {
            echo "<p>⚠️ Con 'activo': No se encontraron usuarios</p>";
        }
    } catch (Exception $e) {
        echo "<p>❌ Error con 'activo': " . $e->getMessage() . "</p>";
    }
    
    // Consulta 3: Contar eventos del año actual
    echo "<h3>3. Contar eventos del año actual:</h3>";
    
    try {
        $db->query("SELECT COUNT(*) as count FROM eventos WHERE YEAR(fecha_inicio) = :anio AND estado = 'activo'");
        $db->bind(':anio', date('Y'));
        $result = $db->single();
        echo "<p>✅ Eventos del año actual: " . ($result ? $result->count : 'Error') . "</p>";
    } catch (Exception $e) {
        echo "<p>❌ Error contando eventos: " . $e->getMessage() . "</p>";
    }
    
    // Consulta 4: Obtener eventos futuros
    echo "<h3>4. Obtener eventos futuros:</h3>";
    
    try {
        $db->query("SELECT * FROM eventos WHERE fecha_inicio >= NOW() AND estado = 'activo' ORDER BY fecha_inicio ASC LIMIT 5");
        $eventos = $db->resultSet();
        echo "<p>✅ Eventos futuros encontrados: " . count($eventos) . "</p>";
        
        if (!empty($eventos)) {
            echo "<ul>";
            foreach ($eventos as $evento) {
                echo "<li>{$evento->titulo} - " . date('d/m/Y H:i', strtotime($evento->fecha_inicio)) . "</li>";
            }
            echo "</ul>";
        }
    } catch (Exception $e) {
        echo "<p>❌ Error obteniendo eventos: " . $e->getMessage() . "</p>";
    }
    
    // Verificar datos de ejemplo
    echo "<h2>📊 Datos de Ejemplo en la Base de Datos:</h2>";
    
    // Usuarios con rol 'socio'
    $db->query("SELECT id, nombre, apellidos, email, rol, activo, fecha_registro FROM users WHERE rol = 'socio' LIMIT 5");
    $socios = $db->resultSet();
    
    echo "<h3>Usuarios con rol 'socio':</h3>";
    if (!empty($socios)) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Activo</th><th>Fecha Registro</th></tr>";
        
        foreach ($socios as $socio) {
            echo "<tr>";
            echo "<td>{$socio->id}</td>";
            echo "<td>{$socio->nombre} {$socio->apellidos}</td>";
            echo "<td>{$socio->email}</td>";
            echo "<td>{$socio->rol}</td>";
            echo "<td>" . ($socio->activo ? 'Sí' : 'No') . "</td>";
            echo "<td>" . date('d/m/Y', strtotime($socio->fecha_registro)) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>⚠️ No hay usuarios con rol 'socio'</p>";
    }
    
    // Eventos
    $db->query("SELECT id, titulo, fecha_inicio, estado FROM eventos LIMIT 5");
    $eventos = $db->resultSet();
    
    echo "<h3>Eventos:</h3>";
    if (!empty($eventos)) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Título</th><th>Fecha Inicio</th><th>Estado</th></tr>";
        
        foreach ($eventos as $evento) {
            echo "<tr>";
            echo "<td>{$evento->id}</td>";
            echo "<td>{$evento->titulo}</td>";
            echo "<td>" . date('d/m/Y H:i', strtotime($evento->fecha_inicio)) . "</td>";
            echo "<td>{$evento->estado}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>⚠️ No hay eventos</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h2>🔍 Análisis del Problema:</h2>";
echo "<p>La página de socios se muestra en blanco probablemente debido a:</p>";
echo "<ul>";
echo "<li>Inconsistencia entre nombres de columnas en el código y la base de datos</li>";
echo "<li>Errores en las consultas SQL que impiden obtener datos</li>";
echo "<li>Falta de manejo de errores en el código PHP</li>";
echo "<li>Problemas de permisos o conexión a la base de datos</li>";
echo "</ul>";

echo "<h2>🔗 Enlaces:</h2>";
echo "<p><a href='/prueba-php/public/socios' target='_blank'>🚀 Ir a Página de Socios</a></p>";
echo "<p><a href='/prueba-php/public/' target='_blank'>🏠 Ir al Inicio</a></p>";
echo "<p><em>Script completado.</em></p>";
?>
