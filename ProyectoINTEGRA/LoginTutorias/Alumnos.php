<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Integra - Inicio de Sesión</title>
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
            $correo = $_POST['correo_institucional'] ?? '';
            $numero_control = $_POST['numero_control'] ?? '';

            if (empty($correo) || empty($numero_control)) {
                echo "<script>
                    alert('⚠️ Faltan datos del formulario.');
                    window.location.href = 'Proyectores.php';
                </script>";
                exit;
            }

            // Consulta a la base de datos
            $sql = "SELECT * FROM alumnos WHERE correo_institucional = ? AND numero_control = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $correo, $numero_control);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();

                // Guardar variables de sesión
                $_SESSION['correo_institucional'] = $row['correo_institucional'];
                $_SESSION['nombre'] = $row['nombre'] ?? 'Alumno';
                $_SESSION['numero_control'] = $row['numero_control'];
                $_SESSION['id_usuario'] = $row['id_usuario'];

                echo "<script>
                        alert('✅ Acceso autorizado. Bienvenido, " . $row['nombre'] . "');
                        window.location.href = 'Alumno/MainAlumno.php';
                    </script>";
            } else {
                echo "<script>
                        alert('❌ Correo o número de control incorrectos.');
                        window.location.href = '../../index.php';
                    </script>";
            }

            $stmt->close();
            $conn->close();
        }
        ?>
<!----------------------------------------------------------------------------------------------------------------->
<!-- ENCABEZADO -->
    <header class="header-verde">
        <h1>
            <span class="bienvenidos">BIENVENIDOS A</span>
            <span class="integra">INTEGRA</span>
            <span class="sub">"GESTIÓN ACADÉMICA MODULAR"</span>
        </h1>
    </header>
<!-- LÍNEA DECORATIVA -->
    <div class="linea-azul"></div>
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
                <form action="Alumnos.php" method="POST">
                    <label for="correo_institucional">Correo institucional:</label>
                    <input type="email" id="correo_institucional" name="correo_institucional" required>
                    <label for="numero_control">Número de control:</label>
                    <input type="password" id="numero_control" name="numero_control" required>
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