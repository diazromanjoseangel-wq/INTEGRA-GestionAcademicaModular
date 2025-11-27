<?php
include("../../conexion.php");
header('Content-Type: application/json');

// Validar método
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "mensaje" => "Método no permitido"]);
    exit;
}

// Validar ID
if (!isset($_POST['id_prestamo'])) {
    echo json_encode(["success" => false, "mensaje" => "ID del préstamo no recibido"]);
    exit;
}

$id_prestamo = intval($_POST['id_prestamo']);
$comentario = isset($_POST['comentario']) ? $_POST['comentario'] : "";

date_default_timezone_set('America/Mexico_City');
$hora_actual = date('H:i:s');

/* ============================================================
   1. ACTUALIZAR PRÉSTAMO (hora_real_entrada, estatus, observaciones)
   ============================================================ */
$query1 = "
    UPDATE prestamo
    SET 
        hora_real_entrada = ?,
        solicitud_estatus = 'devuelto',
        observaciones = ?
    WHERE id_prestamo = ?
";

$stmt1 = $conn->prepare($query1);

if (!$stmt1) {
    echo json_encode(["success" => false, "mensaje" => "Error en consulta 1: " . $conn->error]);
    exit;
}

$stmt1->bind_param("ssi", $hora_actual, $comentario, $id_prestamo);
$stmt1->execute();

/* ============================================================
   2. OBTENER ID DEL PROYECTOR ASOCIADO
   ============================================================ */
$query2 = "SELECT id_proyector FROM prestamo WHERE id_prestamo = ?";
$stmt2 = $conn->prepare($query2);

if (!$stmt2) {
    echo json_encode(["success" => false, "mensaje" => "Error en consulta 2: " . $conn->error]);
    exit;
}

$stmt2->bind_param("i", $id_prestamo);
$stmt2->execute();
$resultado = $stmt2->get_result()->fetch_assoc();

if (!$resultado) {
    echo json_encode(["success" => false, "mensaje" => "Préstamo no encontrado"]);
    exit;
}

$id_proyector = $resultado['id_proyector'];

/* ============================================================
   3. CAMBIAR ESTATUS DEL PROYECTOR → Disponible
   ============================================================ */
$query3 = "UPDATE proyector SET estatus = 'Disponible' WHERE id_proyector = ?";
$stmt3 = $conn->prepare($query3);

if (!$stmt3) {
    echo json_encode(["success" => false, "mensaje" => "Error en consulta 3: " . $conn->error]);
    exit;
}

$stmt3->bind_param("i", $id_proyector);
$stmt3->execute();

/* ============================================================
   FINAL
   ============================================================ */
echo json_encode([
    "success" => true,
    "mensaje" => "Proyector devuelto correctamente y comentario guardado"
]);
exit;

?>
