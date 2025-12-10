<?php
require "conexion.php";

$nombre  = $_POST["nombre"];
$correo  = $_POST["correo"];
$mensaje = $_POST["mensaje"];

$sql = "INSERT INTO soporte (nombre, correo, mensaje)
        VALUES ('$nombre','$correo','$mensaje')";

echo ($conexion->query($sql)) ? "ok" : "error";
?>
