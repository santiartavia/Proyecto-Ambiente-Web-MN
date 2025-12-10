<?php
require "conexion.php";

$res = $conexion->query("SELECT * FROM usuarios");
$rows = [];

while($r = $res->fetch_assoc()){
    $rows[] = $r;
}

echo json_encode($rows);
?>
