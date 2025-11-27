<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>INTEGRA-GestiónAcadémicaModular</title>
    <link rel="stylesheet" href="Admin.css">
    <link rel="icon" type="image/png" href="../../Imagen/LogoTecnm.png">
</head>
<!---------------------------------------------------------------------------------------------------------------->
    <body>
    <!-- ENCABEZADO -->
        <header>
            <img src="../../Imagen/LogoTec.png" class="logo-izq">
            <div class="titulo">
                <h2>INSTITUTO TECNOLÓGICO DE TUXTEPEC</h2>
                <h3>GESTIÓN ACADÉMICA MODULAR (INTEGRA)</h3>
            </div>
            <img src="../../Imagen/LogoTecnm.png" class="logo-der">
        </header>
    <!-- LÍNEA DECORATIVA -->
        <div class="linea-azul"></div>
        <div class="linea-verde"></div>
<!---------------------------------------------------------------------------------------------------------------->
    <!-- CONTENEDOR PRINCIPAL -->
        <main class="contenedor">
            <div id="Listado">
                <a href="Usuarios.php">
                    <img src="../../Imagen/UsuarioByN.png">
                    <span class="menu-text">Usuarios</span>
                </a>
                <a href="Calendario.php">
                    <img src="../../Imagen/CalendarioByN.png    ">
                    <span class="menu-text">Calendario</span>
                </a>
                <a href="Actividades.php">
                    <img src="../../Imagen/ActividadesByN.png">
                    <span class="menu-text">Actividades</span>
                </a>
                <a href="Actualizacion.php">
                    <img src="../../Imagen/ActualizacionByN.png">
                    <span class="menu-text">Actualización</span>
                </a>
                <a href="Curso.php">
                    <img src="../../Imagen/Curso.png">
                    <span class="menu-text">Curso</span>
                </a>
                <a href="Soporte.php">
                    <img src="../../Imagen/SoporteByN.png">
                    <span class="menu-text">Soporte</span>
                </a>
            </div>
            <div class="linea-azul-horizontal"></div>
            <div class="linea-verde-horizontal"></div>
            <a href="../../index.php" class="top-icon">
                <img src="../../Imagen/Salida.png">
            </a>
            <div id="MainContainer">
                <div id="Main"> 
                    <!-- Contenido principal -->
                    <h1>En caso de cualquier inconveniente comunicarse a los siguientes números</h1>
                        <p>Ing. Jose Angel Díaz Roman<br>287 881 4502</p>
                        <p>Ing. Joaquin Hernandez Mora<br>287 105 0834</p>
                        <p>Ing. Axel Salomon Morales<br>287 164 3796</p>
                </div>
            </div>
        </main>
<!---------------------------------------------------------------------------------------------------------------->
    <!-- --------------------------
    SCRIPT PARA OPCIÓN ACTIVA
    -------------------------- -->
        <script>
        /*Script para activar el menú*/
            document.addEventListener("DOMContentLoaded", () => {
            const menuItems = document.querySelectorAll("#Listado a");
            const currentPath = window.location.pathname.split("/").pop(); // nombre de archivo actual

            menuItems.forEach(item => {
                const linkPath = item.getAttribute("href").split("/").pop();

                // Si no estamos en main.html y el link coincide con la página actual
                if (currentPath !== "main.html" && linkPath === currentPath) {
                    item.classList.add("is-active");
                }

                // Manejo de clic para actualizar visualmente
                item.addEventListener("click", () => {
                    menuItems.forEach(el => el.classList.remove("is-active"));
                    item.classList.add("is-active");
                });
            });
        });
        // Tiempo de inactividad: 1 minuto (60,000 ms)
            const tiempoInactividad = 5 * 60 * 1000; 
            let temporizador;
        // Redirige a la página principal
            function redirigir() {
                window.location.href = "MainAdmin.html"; // misma carpeta
            }
        // Reinicia el temporizador cada vez que el usuario hace algo
            function reiniciarTemporizador() {
                clearTimeout(temporizador);
                temporizador = setTimeout(redirigir, tiempoInactividad);
            }
        // Detecta actividad del usuario
            window.onload = reiniciarTemporizador;
            document.onmousemove = reiniciarTemporizador;
            document.onkeypress = reiniciarTemporizador;
            document.onclick = reiniciarTemporizador;
            document.onscroll = reiniciarTemporizador;
            document.ontouchstart = reiniciarTemporizador;
        </script>
    </body>
</html>