<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>INTEGRA-GestiónAcadémicaModular</title>
    <link rel="stylesheet" href="Proyector.css">
    <link rel="icon" type="image/png" href="../Imagen/LogoTecnm.png">
</head>
<!----------------------------------------------------------------------------------------------------------------->
<body>
<!-- PHP -->
    <?php
        session_start();
        include('../conexion.php');
    // Solo procesar si se envió el formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = $_POST['usuario'] ?? '';
            $contraseña = $_POST['contraseña'] ?? '';
            if (empty($usuario) || empty($contraseña)) {
                echo "<script>
                    alert('⚠ Faltan datos del formulario.');
                    window.location.href = 'Proyectores.php';
                </script>";
                exit;
            }
        // Aquí va tu consulta a la base de datos
            $sql = "SELECT * FROM personal WHERE usuario = ? AND contraseña = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $usuario, $contraseña);
            $stmt->execute();
            $result = $stmt->get_result();
                if ($result->num_rows === 1) {
                    $row = $result->fetch_assoc();
                    $_SESSION['usuario'] = $row['usuario'];
                    $_SESSION['nombre'] = $row['nombre'] ?? $row['usuario'];
                    echo "<script>
                            alert('✅ Acceso autorizado. Bienvenido, " . $row['nombre'] . "');
                            window.location.href = 'MainProyector/Main.php';
                        </script>";
                } else {
                    echo "<script>
                            alert('❌ Usuario o contraseña incorrectos');
                            window.location.href = '../../index.php';
                        </script>";
                }
            $stmt->close();
            $conn->close();
        }
    ?>
<!----------------------------------------------------------------------------------------------------------------->
<!-- ENCABEZADO BIENVENIDA -->
    <header>
        <h1>
            <span class="bienvenidos">BIENVENIDOS A</span>
            <span class="integra">INTEGRA</span>
            <span class="sub">"GESTIÓN ACADÉMICA MODULAR"</span>
        </h1>
    </header>
<!-- LÍNEA DECORATIVA -->
    <div class="linea-verde"></div>
<!----------------------------------------------------------------------------------------------------------------->
<!-- CONTENIDO PRINCIPAL -->
    <main class="contenedor">
    <!-- Caja de Login (centrada) -->
        <div class="nivel" style="justify-content: center;">
            <div class="login-box">
                <h3>INICIO DE SESIÓN</h3>
                <p class="sub">PRÉSTAMO DE PROYECTORES</p>
            <!-- Formulario de login -->
                <form action="Proyectores.php" method="POST">
                    <label for="usuario">Usuario:</label>
                    <input type="text" id="usuario" name="usuario" required>
                    <label for="contrasena">Contraseña:</label>
                    <input type="contraseña" id="contraseña" name="contraseña" required>
                    <button type="submit" class="login">INGRESAR</button>
                </form>
            </div>
        </div>
    <!-- Icono de salida -->
        <a href="../index.php" class="salida">
            <img src="../Imagen/Salida.png" alt="Salir">
        </a>
    </main>
</body>
</html>