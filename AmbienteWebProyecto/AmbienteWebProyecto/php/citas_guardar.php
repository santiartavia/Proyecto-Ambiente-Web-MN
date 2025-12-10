<?php
require "conexion.php";

$paciente = $_POST["paciente"];
$doctor   = $_POST["doctor"];
$fecha    = $_POST["fecha"];
$hora     = $_POST["hora"];
$motivo   = $_POST["motivo"];

$sql = "INSERT INTO citas (paciente, doctor, fecha, hora, motivo)
        VALUES ('$paciente','$doctor','$fecha','$hora','$motivo')";

echo ($conexion->query($sql)) ? "ok" : "error";
?>
