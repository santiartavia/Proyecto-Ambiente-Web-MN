<?php
require "conexion.php";

$result = $conexion->query("SELECT * FROM citas ORDER BY fecha ASC");
$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row;
}

echo json_encode($data);
?>
