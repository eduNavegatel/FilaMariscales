<?php
// Script de prueba para verificar el estado de los eventos
echo "<h1>Prueba de Eventos</h1>";

// Verificar si existe la carpeta de eventos
$eventosDir = 'uploads/eventos/';
echo "<h2>Estado de la carpeta de eventos:</h2>";
if (is_dir($eventosDir)) {
    echo "<p>✅ La carpeta de eventos existe: $eventosDir</p>";
    $files = glob($eventosDir . '**/*', GLOB_BRACE);
    if (!empty($files)) {
        echo "<p>📁 Archivos encontrados en eventos:</p>";
        foreach ($files as $file) {
            if (is_file($file)) {
                echo "<div style='margin: 10px;'>";
                echo "<strong>Archivo:</strong> " . basename($file) . "<br>";
                echo "<strong>Ruta:</strong> " . $file . "<br>";
                echo "<strong>URL:</strong> /" . $file . "<br>";
                echo "<img src='/" . $file . "' style='max-width: 200px; max-height: 150px; border: 1px solid #ccc;'><br><br>";
                echo "</div>";
            }
        }
    } else {
        echo "<p>📭 No hay archivos en la carpeta de eventos</p>";
    }
} else {
    echo "<p>❌ La carpeta de eventos NO existe: $eventosDir</p>";
    echo "<p>Creando la carpeta...</p>";
    if (mkdir($eventosDir, 0755, true)) {
        echo "<p>✅ Carpeta creada exitosamente</p>";
    } else {
        echo "<p>❌ Error al crear la carpeta</p>";
    }
}

// Verificar la base de datos de eventos
echo "<h2>Estado de la base de datos de eventos:</h2>";

// Intentar conectar a la base de datos
try {
    require_once 'src/config/config.php';
    require_once 'src/models/Database.php';
    
    $db = new Database();
    echo "<p>✅ Conexión a la base de datos exitosa</p>";
    
    // Verificar si existe la tabla eventos
    $db->query("SHOW TABLES LIKE 'eventos'");
    $result = $db->single();
    
    if ($result) {
        echo "<p>✅ La tabla 'eventos' existe</p>";
        
        // Obtener eventos de la base de datos
        $db->query("SELECT * FROM eventos ORDER BY fecha DESC LIMIT 10");
        $eventos = $db->resultSet();
        
        if (!empty($eventos)) {
            echo "<p>📋 Eventos encontrados en la base de datos:</p>";
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>ID</th><th>Título</th><th>Fecha</th><th>Hora</th><th>Lugar</th><th>Imagen</th><th>Público</th></tr>";
            
            foreach ($eventos as $evento) {
                echo "<tr>";
                echo "<td>" . $evento->id . "</td>";
                echo "<td>" . htmlspecialchars($evento->titulo) . "</td>";
                echo "<td>" . $evento->fecha . "</td>";
                echo "<td>" . $evento->hora . "</td>";
                echo "<td>" . htmlspecialchars($evento->lugar ?? 'No especificado') . "</td>";
                echo "<td>";
                if (!empty($evento->imagen_url)) {
                    echo "<img src='/" . $evento->imagen_url . "' style='max-width: 100px; max-height: 60px;'>";
                    echo "<br><small>" . $evento->imagen_url . "</small>";
                } else {
                    echo "Sin imagen";
                }
                echo "</td>";
                echo "<td>" . ($evento->es_publico ? 'Sí' : 'No') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>📭 No hay eventos en la base de datos</p>";
        }
        
        // Verificar estructura de la tabla
        $db->query("DESCRIBE eventos");
        $columns = $db->resultSet();
        
        echo "<h3>Estructura de la tabla eventos:</h3>";
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Llave</th><th>Por defecto</th></tr>";
        
        foreach ($columns as $column) {
            echo "<tr>";
            echo "<td>" . $column->Field . "</td>";
            echo "<td>" . $column->Type . "</td>";
            echo "<td>" . $column->Null . "</td>";
            echo "<td>" . $column->Key . "</td>";
            echo "<td>" . $column->Default . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
    } else {
        echo "<p>❌ La tabla 'eventos' NO existe</p>";
        echo "<p>Creando la tabla...</p>";
        
        $sql = "CREATE TABLE eventos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            titulo VARCHAR(255) NOT NULL,
            descripcion TEXT,
            fecha DATE NOT NULL,
            hora TIME NOT NULL,
            lugar VARCHAR(255),
            imagen_url VARCHAR(500),
            es_publico TINYINT(1) DEFAULT 1,
            usuario_id INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        
        if ($db->query($sql) && $db->execute()) {
            echo "<p>✅ Tabla 'eventos' creada exitosamente</p>";
        } else {
            echo "<p>❌ Error al crear la tabla</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error de conexión a la base de datos: " . $e->getMessage() . "</p>";
}

// Verificar el controlador de eventos
echo "<h2>Estado del controlador de eventos:</h2>";

if (file_exists('src/controllers/admin/EventController.php')) {
    echo "<p>✅ El controlador EventController existe</p>";
    
    // Verificar métodos importantes
    $controllerContent = file_get_contents('src/controllers/admin/EventController.php');
    
    if (strpos($controllerContent, 'uploadImage') !== false) {
        echo "<p>✅ El método uploadImage existe</p>";
    } else {
        echo "<p>❌ El método uploadImage NO existe</p>";
    }
    
    if (strpos($controllerContent, 'store') !== false) {
        echo "<p>✅ El método store existe</p>";
    } else {
        echo "<p>❌ El método store NO existe</p>";
    }
    
    if (strpos($controllerContent, 'update') !== false) {
        echo "<p>✅ El método update existe</p>";
    } else {
        echo "<p>❌ El método update NO existe</p>";
    }
    
} else {
    echo "<p>❌ El controlador EventController NO existe</p>";
}

// Verificar las vistas de eventos
echo "<h2>Estado de las vistas de eventos:</h2>";

$vistas = [
    'src/views/admin/eventos/index.php' => 'Vista de listado de eventos',
    'src/views/admin/eventos/editar.php' => 'Vista de edición de eventos',
    'src/views/pages/calendario.php' => 'Vista pública del calendario'
];

foreach ($vistas as $ruta => $descripcion) {
    if (file_exists($ruta)) {
        echo "<p>✅ $descripcion existe</p>";
    } else {
        echo "<p>❌ $descripcion NO existe</p>";
    }
}

// Verificar rutas de administración
echo "<h2>Estado de las rutas de administración:</h2>";

if (file_exists('public/index.php')) {
    $indexContent = file_get_contents('public/index.php');
    
    if (strpos($indexContent, 'AdminController') !== false) {
        echo "<p>✅ AdminController está incluido en index.php</p>";
    } else {
        echo "<p>❌ AdminController NO está incluido en index.php</p>";
    }
} else {
    echo "<p>❌ index.php NO existe</p>";
}

echo "<h2>Enlaces de prueba:</h2>";
echo "<p><a href='/prueba-php/public/admin/eventos'>Administración - Eventos</a></p>";
echo "<p><a href='/prueba-php/public/pages/calendario'>Página de Calendario</a></p>";
echo "<p><a href='/prueba-php/public/admin/nuevoEvento'>Crear Nuevo Evento</a></p>";

echo "<h2>Recomendaciones:</h2>";
echo "<ul>";
echo "<li>Si la carpeta de eventos no existe, se creará automáticamente</li>";
echo "<li>Si la tabla eventos no existe, se creará automáticamente</li>";
echo "<li>Verifica que las rutas de administración funcionen correctamente</li>";
echo "<li>Prueba crear un evento con imagen desde la administración</li>";
echo "<li>Verifica que las imágenes se muestren en el calendario público</li>";
echo "</ul>";
?>
