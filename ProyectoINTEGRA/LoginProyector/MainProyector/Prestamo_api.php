<?php
include("../../conexion.php");
header('Content-Type: application/json');

// Validar fecha
if (!isset($_GET['fecha_prestamos'])) {
    echo json_encode([]);
    exit;
}

$fecha = $_GET['fecha_prestamos'];

// Consulta de préstamos del día
$query = "
    SELECT 
        p.id_prestamo,
        p.id_proyector,
        p.nombre_personal,
        p.accesorio_HDMI,
        p.accesorio_VGA,
        p.accesorio_EXT,
        p.identificacion,
        p.hora_salida,
        p.hora_entrada,
        p.hora_real_entrada,
        p.nombre_departamento,
        p.solicitud_estatus,
        p.observaciones,
        pr.estatus AS estatus_proyector
    FROM prestamo p
    LEFT JOIN proyector pr ON p.id_proyector = pr.id_proyector
    WHERE p.fecha = ?
      AND p.solicitud_estatus = 'en uso'
    ORDER BY p.hora_salida ASC
";
$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode([
        "error" => "Error en la preparación: " . $conn->error
    ]);
    exit;
}

$stmt->bind_param("s", $fecha);
$stmt->execute();

$resultado = $stmt->get_result();
$prestamos = [];

while ($fila = $resultado->fetch_assoc()) {
    $prestamos[] = $fila;
}

echo json_encode($prestamos);
exit;
?>
