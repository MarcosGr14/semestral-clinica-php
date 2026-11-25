<?php
$host = 'localhost';
$usuario = 'root';
$contrasena = '';
$base_datos = 'semestral';
$puerto = 3306;

$conn = new mysqli($host, $usuario, $contrasena, $base_datos, $puerto);

if ($conn->connect_error) {
    die('Error de conexión a la base de datos: ' . htmlspecialchars($conn->connect_error, ENT_QUOTES, 'UTF-8'));
}

if (!$conn->set_charset('utf8mb4')) {
    die('Error al establecer el conjunto de caracteres utf8mb4: ' . htmlspecialchars($conn->error, ENT_QUOTES, 'UTF-8'));
}
?>
