<?php
include("../../conexion.php");
header('Content-Type: application/json');
$sql = "SELECT DISTINCT nombre_departamento 
        FROM prestamo 
        ORDER BY nombre_departamento ASC";
$result = $conn->query($sql);
$departamentos = [];
while ($row = $result->fetch_assoc()) {
    $departamentos[] = $row['nombre_departamento'];
}
echo json_encode($departamentos);
?>