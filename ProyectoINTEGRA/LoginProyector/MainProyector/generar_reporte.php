<?php
include("../../conexion.php");
header('Content-Type: application/json');

$tipo = $_POST['tipo'] ?? '';
$response = [
    "labels" => [],
    "valores" => []
];

/* ============================================================
   FUNCIÓN PARA OBTENER RANGO DE MESES SEGÚN PERIODO
   ============================================================ */
function rangoPeriodo($periodo) {
    switch ($periodo) {
        case "enero-julio": 
            return [1, 7];
        case "verano": 
            return [7, 8];
        case "agosto-diciembre": 
            return [8, 12];
        default:
            return [1, 12];
    }
}

/* ============================================================
   1) PRÉSTAMOS POR DÍA
   ============================================================ */
if ($tipo === "por_dia") {
    $fecha = $_POST['fecha'];

    $sql = "SELECT nombre_departamento, COUNT(*) AS total
            FROM prestamo
            WHERE fecha = '$fecha'
            GROUP BY nombre_departamento";

    $res = $conn->query($sql);
    while ($row = $res->fetch_assoc()) {
        $response["labels"][] = $row["nombre_departamento"];
        $response["valores"][] = $row["total"];
    }
}

/* ============================================================
   2) PRÉSTAMOS POR MES
   ============================================================ */
if ($tipo === "por_mes") {
    $mes = $_POST['mes'];
    $anio = $_POST['anio'];

    $sql = "SELECT DAY(fecha) AS dia, COUNT(*) AS total
            FROM prestamo
            WHERE MONTH(fecha) = '$mes'
              AND YEAR(fecha) = '$anio'
            GROUP BY DAY(fecha)";

    $res = $conn->query($sql);
    while ($row = $res->fetch_assoc()) {
        $response["labels"][] = "Día " . $row["dia"];
        $response["valores"][] = $row["total"];
    }
}

/* ============================================================
   3) PRÉSTAMOS POR PERIODO
   ============================================================ */
if ($tipo === "por_periodo") {
    $periodo = $_POST['periodo'];
    $anio = $_POST['anio'];
    list($mesInicio, $mesFin) = rangoPeriodo($periodo);

    $sql = "SELECT MONTH(fecha) AS mes, COUNT(*) AS total
            FROM prestamo
            WHERE YEAR(fecha) = '$anio'
              AND MONTH(fecha) BETWEEN $mesInicio AND $mesFin
            GROUP BY MONTH(fecha)";

    $res = $conn->query($sql);
    while ($row = $res->fetch_assoc()) {
        $response["labels"][] = "Mes " . $row["mes"];
        $response["valores"][] = $row["total"];
    }
}

/* ============================================================
   4) PROYECTORES MÁS USADOS POR MES
   ============================================================ */
if ($tipo === "proyectores_mes") {
    $mes = $_POST['mes'];
    $anio = $_POST['anio'];

    $sql = "SELECT p.codigo AS proyector, COUNT(*) AS total
            FROM prestamo pr
            INNER JOIN proyector p ON pr.id_proyector = p.id_proyector
            WHERE MONTH(pr.fecha) = '$mes'
              AND YEAR(pr.fecha) = '$anio'
            GROUP BY p.codigo
            ORDER BY total DESC";

    $res = $conn->query($sql);
    while ($row = $res->fetch_assoc()) {
        $response["labels"][] = $row["proyector"];
        $response["valores"][] = $row["total"];
    }
}

/* ============================================================
   5) PRÉSTAMOS POR DOCENTE
   ============================================================ */
if ($tipo === "docente_periodo") {

    $periodo = $_POST['periodo'];
    $anio = $_POST['anio'];
    $departamento = $_POST['departamento'];

    list($mesInicio, $mesFin) = rangoPeriodo($periodo);

    $sql = "SELECT nombre_personal AS docente, COUNT(*) AS total
            FROM prestamo
            WHERE nombre_departamento = '$departamento'
              AND YEAR(fecha) = '$anio'
              AND MONTH(fecha) BETWEEN $mesInicio AND $mesFin
            GROUP BY nombre_personal
            ORDER BY total DESC";

    $res = $conn->query($sql);
    while ($row = $res->fetch_assoc()) {
        $response["labels"][] = $row["docente"];
        $response["valores"][] = $row["total"];
    }
}

/* ============================================================
   6) PRÉSTAMOS POR DEPARTAMENTO
   ============================================================ */
if ($tipo === "por_departamento") {
    $dep = $_POST['departamento'];

    $sql = "SELECT fecha, COUNT(*) AS total
            FROM prestamo
            WHERE nombre_departamento = '$dep'
            GROUP BY fecha";

    $res = $conn->query($sql);
    while ($row = $res->fetch_assoc()) {
        $response["labels"][] = $row["fecha"];
        $response["valores"][] = $row["total"];
    }
}

/* ============================================================
   7) ESTADO DE PROYECTORES POR PERIODO
   ============================================================ */
if ($tipo === "estado_periodo") {

    $periodo = $_POST['periodo'];
    $anio = $_POST['anio'];
    list($mesInicio, $mesFin) = rangoPeriodo($periodo);

    $sql = "SELECT p.estado, COUNT(*) AS total
            FROM prestamo pr
            INNER JOIN proyector p ON pr.id_proyector = p.id_proyector
            WHERE YEAR(pr.fecha) = '$anio'
              AND MONTH(pr.fecha) BETWEEN $mesInicio AND $mesFin
            GROUP BY p.estado";

    $res = $conn->query($sql);
    while ($row = $res->fetch_assoc()) {
        $response["labels"][] = ucfirst($row["estado"]);
        $response["valores"][] = $row["total"];
    }
}

/* ============================================================
   8) TIEMPO PROMEDIO (DÍA / MES / PERIODO)
   ============================================================ */

/* CONVERSIÓN A HORAS DECIMALES — NECESARIO PARA CHART.JS */
function convertirSegundosAHoras($seg) {
    return round($seg / 3600, 2);
}

/* --- POR DÍA --- */
if ($tipo === "promedio_dia") {
    $fecha = $_POST['fecha'];

    $sql = "SELECT AVG(TIMESTAMPDIFF(SECOND, hora_salida, hora_real_entrada)) AS promedio_seg
            FROM prestamo
            WHERE fecha = '$fecha'
              AND hora_real_entrada IS NOT NULL";

    $res = $conn->query($sql);
    $row = $res->fetch_assoc();

    $horas = convertirSegundosAHoras($row["promedio_seg"] ?? 0);

    $response["labels"] = ["Promedio (hrs)"];
    $response["valores"] = [$horas];
}

/* --- POR MES --- */
if ($tipo === "promedio_mes") {
    $mes = $_POST['mes'];
    $anio = $_POST['anio'];

    $sql = "SELECT AVG(TIMESTAMPDIFF(SECOND, hora_salida, hora_real_entrada)) AS promedio_seg
            FROM prestamo
            WHERE MONTH(fecha) = '$mes'
              AND YEAR(fecha) = '$anio'
              AND hora_real_entrada IS NOT NULL";

    $res = $conn->query($sql);
    $row = $res->fetch_assoc();

    $horas = convertirSegundosAHoras($row["promedio_seg"] ?? 0);

    $response["labels"] = ["Promedio (hrs)"];
    $response["valores"] = [$horas];
}

/* --- POR PERIODO --- */
if ($tipo === "promedio_periodo") {
    $periodo = $_POST['periodo'];
    $anio = $_POST['anio'];
    list($mesInicio, $mesFin) = rangoPeriodo($periodo);

    $sql = "SELECT AVG(TIMESTAMPDIFF(SECOND, hora_salida, hora_real_entrada)) AS promedio_seg
            FROM prestamo
            WHERE YEAR(fecha) = '$anio'
              AND MONTH(fecha) BETWEEN $mesInicio AND $mesFin
              AND hora_real_entrada IS NOT NULL";

    $res = $conn->query($sql);
    $row = $res->fetch_assoc();

    $horas = convertirSegundosAHoras($row["promedio_seg"] ?? 0);

    $response["labels"] = ["Promedio (hrs)"];
    $response["valores"] = [$horas];
}
/* ============================================================ */
echo json_encode($response);
?>