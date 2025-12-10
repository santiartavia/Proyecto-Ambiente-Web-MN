<?php
$host = "localhost";
$user = "root";
$pass = "1440";
$db   = "medi_connect";

$conexion = new mysqli($host, $user, $pass, $db);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
