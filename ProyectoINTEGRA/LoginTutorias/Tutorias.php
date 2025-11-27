<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>INTEGRA-GestiónAcadémicaModular</title>
    <link rel="stylesheet" href="Tutorias.css">
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
            $contrasena = $_POST['contraseña'] ?? ''; // usa el mismo nombre del form

            if (empty($usuario) || empty($contrasena)) {
                echo "<script>
                    alert('⚠️ Faltan datos del formulario.');
                    window.location.href = 'Proyectores.php';
                </script>";
                exit;
            }

            // Consulta a la base de datos con prepared statements
            $sql = "SELECT * FROM personal WHERE usuario = ? AND contraseña = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $usuario, $contrasena);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();

                $_SESSION['usuario'] = $row['usuario'];
                $_SESSION['nombre'] = $row['nombre'] ?? $row['usuario'];
                $_SESSION['rol'] = $row['rol'];

                // Redirección según el rol
                if ($row['rol'] === 'Administrador') {
                    $redirect = '../LoginTutorias/Admin/MainAdmin.php';
                } elseif ($row['rol'] === 'Docente') {
                    $redirect = '../LoginTutorias/Tutor/MainTutor.php';
                } else {
                    echo "<script>
                            alert('🚫 No pertenece a este módulo del sistema.');
                            window.location.href = '../../index.php';
                        </script>";
                    exit;
                }

                echo "<script>
                        alert('✅ Acceso autorizado. Bienvenido, " . $row['nombre'] . "');
                        window.location.href = '$redirect';
                    </script>";
            } else {
                echo "<script>
                        alert('❌ Usuario o contraseña incorrectos');
                        window.location.href = 'Tutorias.php';
                    </script>";
            }

            $stmt->close();
            $conn->close();
        }
    ?>
<!----------------------------------------------------------------------------------------------------------------->
<!-- ENCABEZADO -->
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
<!-- CONTENEDOR PRINCIPAL (ajustado a tu CSS) -->
    <main class="contenedor">
    <!-- Caja de Login (centrada) -->
        <div class="nivel" style="justify-content: center;">
            <div class="login-box">
                <h3>BIENVENIDOS A TUTORIAS</h3>
                <p class="sub">INICIO DE SESIÓN</p>
            <!-- Botones de selección de rol -->
                <div class="buttons" style="display: flex; justify-content: center; gap: 15px; margin-bottom: 20px;">
                    <a href="Tutorias.php" class="tutor">TUTOR</a>
                    <a href="Alumnos.php" class="alumno">ALUMNO</a>
                </div>
            <!-- Formulario de login -->
                <form action="Tutorias.php" method="POST">
                    <label for="usuario">Usuario:</label>
                    <input type="text" id="usuario" name="usuario" required>
                    <label for="contraseña">Contraseña:</label>
                    <input type="password" id="contraseña" name="contraseña" required>
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