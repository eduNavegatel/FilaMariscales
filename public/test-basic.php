<?php
// Archivo de prueba básico sin dependencias
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>✅ Test Básico - Sin Dependencias</h1>";
echo "<p>Fecha y hora: " . date('Y-m-d H:i:s') . "</p>";
echo "<p>Directorio: " . __DIR__ . "</p>";
echo "<p>PHP version: " . phpversion() . "</p>";

// Probar funciones básicas
echo "<h2>🔍 Funciones básicas:</h2>";
echo "<p>✅ echo funcionando</p>";
echo "<p>✅ date() funcionando</p>";
echo "<p>✅ __DIR__ funcionando</p>";

echo "<hr>";
echo "<p>🎉 Archivo básico funcionando correctamente</p>";
?>
