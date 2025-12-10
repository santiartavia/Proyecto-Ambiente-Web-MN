<?php
require "conexion.php";

$nombre = $_POST["nombre"];
$correo = $_POST["correo"];
$rol    = $_POST["rol"];

$sql = "INSERT INTO usuarios (nombre, correo, rol)
        VALUES ('$nombre','$correo','$rol')";

echo ($conexion->query($sql)) ? "ok" : "error";
?>
