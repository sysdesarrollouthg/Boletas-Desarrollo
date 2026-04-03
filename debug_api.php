<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "### DIAGNOSTICO DE ENTORNO ###<br>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Ruta Real: " . __FILE__ . "<br>";

$config_file = "config/app.php";
if (file_exists($config_file)) {
    echo "✅ Archivo de configuración encontrado.<br>";
    include_once $config_file;
    try {
        $dsn = "mysql:host=".SERVER.";dbname=".DB.";charset=utf8";
        $pdo = new PDO($dsn, USER, PASS);
        echo "✅ CONEXIÓN A BASE DE DATOS EXITOSA.";
    } catch (PDOException $e) {
        echo "❌ ERROR DE BASE DE DATOS: " . $e->getMessage();
    }
} else {
    echo "❌ ERROR: No se encuentra el archivo en: " . realpath($config_file);
}
