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
            <!-- Aquí irá el contenido dinámico o principal -->
            </div><!-- /Main -->
        </div><!-- /MainContainer -->
    </main>
<!---------------------------------------------------------------------------------------------------------------->
<!-- ---------------------------
    SCRIPT PARA OPCIÓN ACTIVA
------------------------------->
        <script>
    /* Script para activar visualmente el menú de navegación */
        document.addEventListener("DOMContentLoaded", () => {  
        // Espera a que el DOM esté completamente cargado
            const menuItems = document.querySelectorAll("#Listado a");
        // Selecciona todos los enlaces dentro del contenedor con id="Listado"
            const currentPath = window.location.pathname.split("/").pop(); 
        // Obtiene el nombre del archivo actual desde la URL (ejemplo: "reportes.html")
            menuItems.forEach(item => {
                const linkPath = item.getAttribute("href").split("/").pop();
            // Obtiene el nombre del archivo desde el href de cada enlace
            // Condición:
            // Si la página actual NO es "main.html" 
            // y el href del enlace coincide con la página actual, se marca como activo
                if (currentPath !== "main.php" && linkPath === currentPath) {
                    item.classList.add("is-active");
                }
            // Evento al hacer clic en un enlace del menú
                item.addEventListener("click", () => {
                // Primero elimina la clase activa de todos los enlaces
                    menuItems.forEach(el => el.classList.remove("is-active"));
                // Luego aplica la clase activa SOLO al enlace clicado
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