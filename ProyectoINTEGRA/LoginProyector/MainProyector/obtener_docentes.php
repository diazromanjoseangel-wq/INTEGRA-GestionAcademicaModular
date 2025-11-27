<?php
include("../../conexion.php");
header('Content-Type: application/json');
$sql = "SELECT DISTINCT nombre_personal 
        FROM prestamo 
        ORDER BY nombre_personal ASC";
$result = $conn->query($sql);
$docentes = [];
while ($row = $result->fetch_assoc()) {
    $docentes[] = $row['nombre_personal'];
}
echo json_encode($docentes);
?>