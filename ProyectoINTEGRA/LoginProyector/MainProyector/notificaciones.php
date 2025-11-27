<?php
include("../../conexion.php");
date_default_timezone_set('America/Mexico_City');

$sql = "SELECT nombre_personal, id_proyector 
        FROM prestamo 
        WHERE TIME_FORMAT(hora_entrada, '%H:%i') = TIME_FORMAT(NOW(), '%H:%i')
        AND solicitud_estatus = 'en uso'";

$res = $conn->query($sql);

$notificaciones = [];

while ($row = $res->fetch_assoc()) {
    $notificaciones[] = [
        "docente" => $row['nombre_personal'],
        "proyector" => $row['id_proyector']
    ];
}

header("Content-Type: application/json");
echo json_encode($notificaciones);
