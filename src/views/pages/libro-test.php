<?php
echo "Página de prueba funcionando correctamente";
echo "<br>";
echo "Ruta actual: " . __DIR__;
echo "<br>";
echo "Archivo config existe: " . (file_exists(__DIR__ . '/../../../src/config/config.php') ? 'Sí' : 'No');
echo "<br>";
echo "Archivo FlipbookHelper existe: " . (file_exists(__DIR__ . '/../../../src/helpers/FlipbookHelper.php') ? 'Sí' : 'No');
echo "<br>";
echo "Ruta config: " . __DIR__ . '/../../../src/config/config.php';
echo "<br>";
echo "Ruta helper: " . __DIR__ . '/../../../src/helpers/FlipbookHelper.php';
?>
