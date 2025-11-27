<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>INTEGRA-GestiónAcadémicaModular</title>
    <link rel="stylesheet" href="Tutor.css">
    <link rel="icon" type="image/png" href="../../Imagen/LogoTecnm.png">
</head>
<!---------------------------------------------------------------------------------------------------------------->
    <body>
    <!-- ===================================
            ENCABEZADO DE LA PÁGINA
    ==================================== -->
        <header>
        <!-- Logo del instituto a la izquierda -->
            <img src="../../Imagen/LogoTec.png" class="logo-izq">
        <!-- Títulos principales -->
            <div class="titulo">
                <h2>INSTITUTO TECNOLÓGICO DE TUXTEPEC</h2>
                <h3>GESTIÓN ACADÉMICA MODULAR (INTEGRA)</h3>
            </div>
        <!-- Logo de TecNM a la derecha -->
            <img src="../../Imagen/LogoTecnm.png" class="logo-der">
        </header>
    <!-- LÍNEAS DECORATIVAS -->
        <div class="linea-azul"></div>
        <div class="linea-verde"></div>
<!---------------------------------------------------------------------------------------------------------------->
<!-- ==========================
     CONTENEDOR PRINCIPAL
========================== -->
    <main class="contenedor">
    <!-- =======================
            MENÚ LATERAL
    ======================= -->
        <div id="Listado">
        <!-- Enlace he icono a Usuarios -->
            <a href="Usuarios.php">
                <img src="../../Imagen/UsuarioByN.png">
                <span class="menu-text">Usuarios</span>
            </a>
        <!-- Enlace he icono a Calendario -->
            <a href="Calendario.php">
                <img src="../../Imagen/CalendarioByN.png">
                <span class="menu-text">Calendario</span>
            </a>
        <!-- Enlace he icono a Reportes -->
            <a href="Reportes.php">
                <img src="../../Imagen/ReporteByN.png">
                <span class="menu-text">Reportes</span>
            </a>
        <!-- Enlace he icono a Actividades -->
            <a href="Actividades.php">
                <img src="../../Imagen/ActividadesByN.png">
                <span class="menu-text">Actividades</span>
            </a>
        <!-- Enlace he icono a Soporte -->
            <a href="Soporte.php">
                <img src="../../Imagen/SoporteByN.png">
                <span class="menu-text">Soporte</span>
            </a>
        </div>
    <!-- =======================
        LÍNEAS DECORATIVAS
    ======================= -->
        <div class="linea-azul-horizontal"></div>
        <div class="linea-verde-horizontal"></div>
    <!-- =======================
        CONTENEDOR PRINCIPAL
    ======================= -->
            <div id="MainContainer">
            <!-- Botón de salida (enlace al index) -->
                <a href="../../index.php" class="top-icon">
                    <img src="../../Imagen/Salida.png">
                </a>
            <!-- Contenido principal -->
                <div id="Main"> 
                    <h1>En caso de cualquier inconveniente comunicarse a los siguientes números</h1>
                        <p>Ing. Jose Angel Díaz Roman<br>287 881 4502 ó 287 146 0800</p>
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
        /* ===============================
                ACTIVAR MENÚ LATERAL
        =============================== */
            document.addEventListener("DOMContentLoaded", () => {
                const menuItems = document.querySelectorAll("#Listado a"); // todos los enlaces del menú lateral
                const currentPath = window.location.pathname.split("/").pop(); // obtiene el nombre del archivo actual
                menuItems.forEach(item => {
                    const linkPath = item.getAttribute("href").split("/").pop(); // nombre del archivo del enlace
                // Marca como activo si coincide con la página actual
                    if (currentPath !== "maintutor.php" && linkPath === currentPath) {
                        item.classList.add("is-active");
                    }
                // Al hacer clic en un enlace, se marca como activo y se desactiva el resto
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
                window.location.href = "MainTutor.php"; // misma carpeta
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