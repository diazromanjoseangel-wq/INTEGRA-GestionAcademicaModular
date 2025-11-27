<?php
$host = "127.0.0.1";
$usuario = "root";
$contrasena = "";
$base_datos = "proyecto_integra";
$puerto = 3306;
$conn = new mysqli($host, $usuario, $contrasena, $base_datos, $puerto);
if ($conn->connect_error) {
    die("<script>alert('❌ Error de conexión: " . $conn->connect_error . "');</script>");
}
?>