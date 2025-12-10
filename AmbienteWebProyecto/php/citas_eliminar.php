<?php
require "conexion.php";

$id = $_POST["id"];
$conexion->query("DELETE FROM citas WHERE id = $id");

echo "ok";
?>
