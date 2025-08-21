<?php
// Script de prueba para verificar las consultas de la página de socios
echo "<h1>Prueba de Consultas - Página de Socios</h1>";

try {
    require_once 'src/config/config.php';
    require_once 'src/models/Database.php';
    
    $db = new Database();
    echo "<p>✅ Conexión a la base de datos exitosa</p>";
    
    // Test 1: Contar socios activos
    echo "<h2>1. Contando socios activos:</h2>";
    $db->query("SELECT COUNT(*) as count FROM users WHERE rol = 'socio' AND estado = 'activo'");
    $result = $db->single();
    $socios_activos = $result ? $result->count : 0;
    echo "<p>Socios activos encontrados: <strong>{$socios_activos}</strong></p>";
    
    // Test 2: Calcular años de historia
    echo "<h2>2. Calculando años de historia:</h2>";
    $anio_fundacion = 1985;
    $anio_actual = date('Y');
    $anios_historia = $anio_actual - $anio_fundacion;
    echo "<p>Años de historia: <strong>{$anios_historia}</strong> (desde {$anio_fundacion})</p>";
    
    // Test 3: Contar eventos del año actual
    echo "<h2>3. Contando eventos del año actual:</h2>";
    $db->query("SELECT COUNT(*) as count FROM eventos WHERE YEAR(fecha_inicio) = :anio AND estado = 'activo'");
    $db->bind(':anio', $anio_actual);
    $result = $db->single();
    $eventos_anuales = $result ? $result->count : 0;
    echo "<p>Eventos del año {$anio_actual}: <strong>{$eventos_anuales}</strong></p>";
    
    // Test 4: Obtener primer socio activo
    echo "<h2>4. Obteniendo primer socio activo:</h2>";
    $db->query("SELECT * FROM users WHERE rol = 'socio' AND estado = 'activo' ORDER BY fecha_registro ASC LIMIT 1");
    $socio_ejemplo = $db->single();
    
    if ($socio_ejemplo) {
        echo "<p>✅ Socio encontrado:</p>";
        echo "<ul>";
        echo "<li><strong>ID:</strong> {$socio_ejemplo->id}</li>";
        echo "<li><strong>Nombre:</strong> {$socio_ejemplo->nombre} {$socio_ejemplo->apellidos}</li>";
        echo "<li><strong>Email:</strong> {$socio_ejemplo->email}</li>";
        echo "<li><strong>Rol:</strong> {$socio_ejemplo->rol}</li>";
        echo "<li><strong>Estado:</strong> {$socio_ejemplo->estado}</li>";
        echo "<li><strong>Fecha registro:</strong> {$socio_ejemplo->fecha_registro}</li>";
        echo "</ul>";
        
        // Generar número de socio
        $numero_socio = 'MS-' . date('Y') . '-' . str_pad($socio_ejemplo->id, 3, '0', STR_PAD_LEFT);
        echo "<p><strong>Número de socio generado:</strong> {$numero_socio}</p>";
    } else {
        echo "<p>⚠️ No se encontraron socios activos</p>";
    }
    
    // Test 5: Obtener eventos futuros
    echo "<h2>5. Obteniendo eventos futuros:</h2>";
    $db->query("SELECT * FROM eventos WHERE fecha_inicio >= NOW() AND estado = 'activo' ORDER BY fecha_inicio ASC LIMIT 5");
    $eventos_db = $db->resultSet();
    
    if ($eventos_db) {
        echo "<p>✅ Eventos futuros encontrados:</p>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Título</th><th>Fecha Inicio</th><th>Ubicación</th><th>Estado</th></tr>";
        
        foreach ($eventos_db as $evento) {
            echo "<tr>";
            echo "<td>{$evento->id}</td>";
            echo "<td>" . htmlspecialchars($evento->titulo) . "</td>";
            echo "<td>{$evento->fecha_inicio}</td>";
            echo "<td>" . htmlspecialchars($evento->ubicacion ?: 'No especificado') . "</td>";
            echo "<td>{$evento->estado}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>⚠️ No se encontraron eventos futuros</p>";
    }
    
    // Test 6: Verificar estructura de tabla users
    echo "<h2>6. Verificando estructura de tabla users:</h2>";
    $db->query("DESCRIBE users");
    $columns = $db->resultSet();
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Llave</th><th>Por defecto</th></tr>";
    
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>{$column->Field}</td>";
        echo "<td>{$column->Type}</td>";
        echo "<td>{$column->Null}</td>";
        echo "<td>{$column->Key}</td>";
        echo "<td>{$column->Default}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Resumen final
    echo "<h2>📊 Resumen de Estadísticas:</h2>";
    echo "<div style='background-color: #f8f9fa; padding: 20px; border-radius: 10px;'>";
    echo "<h3>Estadísticas que se mostrarán en la página:</h3>";
    echo "<ul>";
    echo "<li><strong>Socios Activos:</strong> {$socios_activos}+</li>";
    echo "<li><strong>Años de Historia:</strong> {$anios_historia}</li>";
    echo "<li><strong>Eventos Anuales:</strong> {$eventos_anuales}+</li>";
    echo "</ul>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}
?>
