<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>INTEGRA-GestiónAcadémicaModular</title>
    <link rel="stylesheet" href="Tutor.css">
    <link rel="icon" type="image/png" href="Imagen/LogoTecnm.png">
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
            <a href="../../index.html" class="top-icon">
                <img src="../../Imagen/Salida.png">
            </a>
        <!-- Contenido principal -->
            <div id="Main"> 
                <!-- =======================
                    BOTONES DE FILTRO
                ======================= -->
                <div class="botones-container">
                    <!-- Botón desplegable PERIODO -->
                    <div class="dropdown">
                        <button class="dropdown-btn">
                            <span>PERIODO</span>
                            <img src="../../Imagen/Flecha.png" alt="flecha" class="icono">
                        </button>
                        <div class="dropdown-content">
                            <a href="#">ENE-JUN</a>
                            <a href="#">AGO-DIC</a>
                        </div>
                    </div>
                    <!-- Botón desplegable CARRERA -->
                    <div class="dropdown">
                        <button class="dropdown-btn">
                            <span>CARRERA</span>
                            <img src="../../Imagen/Flecha.png" alt="flecha" class="icono">
                        </button>
                        <div class="dropdown-content">
                            <a href="#">ISC</a>
                            <a href="#">IDA</a>
                            <a href="#">IC</a>
                            <a href="#">IBQ</a>
                            <a href="#">IGE</a>
                            <a href="#">II</a>
                            <a href="#">IE</a>
                            <a href="#">IEM</a>
                            <a href="#">LA</a>
                            <a href="#">CP</a>
                        </div>
                    </div>
                    <!-- Botón ALUMNOS -->
                    <div class="dropdown">
                        <button class="dropdown-btn">
                            <span>ALUMNOS</span>  
                        </button>
                    </div>
                </div> <!-- /botones-container -->
            </div> <!-- /Main -->
        </div> <!-- /MainContainer -->
    </main>
<!---------------------------------------------------------------------------------------------------------------->
    <!-- --------------------------
        SCRIPT PARA OPCIÓN ACTIVA
    --------------------------- -->
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
                    if (currentPath !== "maintutor.html" && linkPath === currentPath) {
                        item.classList.add("is-active");
                    }
                // Al hacer clic en un enlace, se marca como activo y se desactiva el resto
                    item.addEventListener("click", () => {
                        menuItems.forEach(el => el.classList.remove("is-active"));
                        item.classList.add("is-active");
                    });
                });
            });
        /* ===============================
          MENÚS DESPLEGABLES (Dropdowns)
        =============================== */
        // Alterna la visibilidad de un dropdown al hacer clic en el botón
            document.querySelectorAll(".dropdown-btn").forEach(btn => {
                btn.addEventListener("click", () => {
                    let content = btn.nextElementSibling;
                // Cierra los demás dropdowns abiertos
                    document.querySelectorAll(".dropdown-content").forEach(c => {
                        if (c !== content) c.style.display = "none";
                    });
                // Muestra u oculta el actual
                    content.style.display = (content.style.display === "block") ? "none" : "block";
                });
            });
        // Cierra cualquier dropdown si se hace clic fuera de ellos
            window.addEventListener("click", e => {
                if (!e.target.closest(".dropdown")) {
                    document.querySelectorAll(".dropdown-content").forEach(c => c.style.display = "none");
                }
            });
        /* ================================
            ICONO DE FLECHA Y ANIMACIÓN
        ================================= */
            document.querySelectorAll(".dropdown-btn").forEach(btn => {
                btn.addEventListener("click", (e) => {
                    e.stopPropagation(); // evita que el clic cierre inmediatamente el menú
                    let content = btn.nextElementSibling; // el menú desplegable
                    let icon = btn.querySelector(".icono"); // la flecha del botón
                // Cierra otros dropdowns y resetea sus iconos
                    document.querySelectorAll(".dropdown-content").forEach(c => {
                        if (c !== content) {
                            c.classList.remove("show");
                            c.previousElementSibling.querySelector(".icono").classList.remove("rotate");
                        }
                    });
                // Alterna el menú actual y la rotación de la flecha
                    content.classList.toggle("show");
                    icon.classList.toggle("rotate");
                });
            });
        // Cierra todos los menús y resetea iconos si se hace clic fuera
            window.addEventListener("click", () => {
                document.querySelectorAll(".dropdown-content").forEach(c => {
                    c.classList.remove("show");
                    c.previousElementSibling.querySelector(".icono").classList.remove("rotate");
                });
            });
        /* ================================
            ACTUALIZAR TEXTO DEL BOTÓN
        ================================= */
            const dropdowns = document.querySelectorAll('.dropdown'); // todos los dropdowns
            dropdowns.forEach(dropdown => {
                const btn = dropdown.querySelector('.dropdown-btn span'); // texto dentro del botón
                const links = dropdown.querySelectorAll('.dropdown-content a'); // opciones del menú
            // Al hacer clic en una opción, actualiza el texto del botón
                links.forEach(link => {
                    link.addEventListener('click', (e) => {
                        e.preventDefault(); // evita navegación/scroll
                        btn.textContent = link.textContent; // cambia el texto por el de la opción elegida
                    });
                });
            });
        // Tiempo de inactividad: 1 minuto (60,000 ms)
            const tiempoInactividad = 5 * 60 * 1000; 
            let temporizador;
        // Redirige a la página principal
            function redirigir() {
                window.location.href = "MainTutor.html"; // misma carpeta
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