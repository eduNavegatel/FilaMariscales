<?php
/**
 * Script para agregar nuevas contraseñas al archivo de configuración
 * 
 * Uso: php agregar-contraseña.php [user_id] [password]
 * Ejemplo: php agregar-contraseña.php 32 "nueva123"
 */

if ($argc < 3) {
    echo "❌ Uso: php agregar-contraseña.php [user_id] [password]\n";
    echo "📝 Ejemplo: php agregar-contraseña.php 32 \"nueva123\"\n";
    exit(1);
}

$userId = $argv[1];
$password = $argv[2];

echo "🔐 AGREGANDO CONTRASEÑA AL ARCHIVO DE CONFIGURACIÓN\n";
echo "==================================================\n\n";

try {
    $config_file = __DIR__ . '/src/config/user_passwords.php';
    
    if (!file_exists($config_file)) {
        echo "❌ Error: No se encontró el archivo de configuración\n";
        exit(1);
    }
    
    // Leer el archivo actual
    $content = file_get_contents($config_file);
    
    // Buscar la línea donde agregar la nueva contraseña
    $lines = explode("\n", $content);
    $new_lines = [];
    $added = false;
    
    foreach ($lines as $line) {
        $new_lines[] = $line;
        
        // Buscar la línea antes de "// Agregar aquí más usuarios"
        if (strpos($line, '// Agregar aquí más usuarios') !== false) {
            // Agregar la nueva contraseña antes de esta línea
            $new_lines[] = "    {$userId} => '{$password}',           // Usuario ID {$userId}";
            $added = true;
        }
    }
    
    if (!$added) {
        // Si no se encontró la línea de comentario, agregar al final del array
        foreach ($new_lines as $i => $line) {
            if (strpos($line, '];') !== false) {
                // Insertar antes del cierre del array
                array_splice($new_lines, $i, 0, "    {$userId} => '{$password}',           // Usuario ID {$userId}");
                $added = true;
                break;
            }
        }
    }
    
    if (!$added) {
        echo "❌ Error: No se pudo agregar la contraseña al archivo\n";
        exit(1);
    }
    
    // Escribir el archivo actualizado
    $new_content = implode("\n", $new_lines);
    file_put_contents($config_file, $new_content);
    
    echo "✅ Contraseña agregada exitosamente:\n";
    echo "   Usuario ID: {$userId}\n";
    echo "   Contraseña: {$password}\n";
    echo "   Archivo: {$config_file}\n\n";
    
    echo "📝 Para verificar, ejecuta:\n";
    echo "   php test-contraseñas-completas.php\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>



