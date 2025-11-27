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
            <div id="MainContainer">
                <a href="../../index.php" class="top-icon">
                    <img src="../../Imagen/Salida.png">
                </a>
                <div id="Main"> 
                    <!-- Contenido principal -->
                    <div class="botones-container">
                    <!-- Botón PERIODO -->
                        <div class="dropdown">
                            <button class="dropdown-btn">
                                <span>PERIODO</span>
                                <img src="../../Imagen/Flecha.png"  alt="flecha" class="icono">
                            </button>
                            <div class="dropdown-content">
                                <a href="#">ENE-JUN</a>
                                <a href="#">AGO-DIC</a>
                            </div>
                        </div>
                    <!-- Botón CARRERA -->
                        <div class="dropdown">
                            <button class="dropdown-btn">
                                <span>CARRERA</span>
                                <img src="../../Imagen/Flecha.png"  alt="flecha" class="icono">
                            </button>
                            <div class="dropdown-content">
                                <a href="#">ISC</a>
                                <a href="#">IC</a>
                                <a href="#">IBQ</a>
                                <a href="#">IGE</a>
                                <a href="#">IIA</a>
                                <a href="#">IM</a>
                                <a href="#">LA</a>
                                <a href="#">CP</a>
                            </div>
                        </div>
                    <!-- Botón ALUMNOS (ejemplo sin opciones por ahora) -->
                        <div class="dropdown">
                            <button class="dropdown-btn">
                                <span>ALUMNOS</span>  
                            </button>
                        </div>
                    </div>
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

                document.querySelectorAll(".dropdown-btn").forEach(btn => {
            btn.addEventListener("click", () => {
                let content = btn.nextElementSibling;
                // Cierra otros dropdowns
                document.querySelectorAll(".dropdown-content").forEach(c => {
                    if(c !== content) c.style.display = "none";
                });
                // Alterna el actual
                content.style.display = (content.style.display === "block") ? "none" : "block";
            });
        });
        // Cierra el menú si se hace click fuera
        window.addEventListener("click", e => {
            if (!e.target.closest(".dropdown")) {
                document.querySelectorAll(".dropdown-content").forEach(c => c.style.display = "none");
            }
        });
        document.querySelectorAll(".dropdown-btn").forEach(btn => {
            btn.addEventListener("click", (e) => {
                e.stopPropagation(); // evita que se cierre inmediatamente
                let content = btn.nextElementSibling;
                let icon = btn.querySelector(".icono");
                // Cierra todos los demás
                document.querySelectorAll(".dropdown-content").forEach(c => {
                    if (c !== content) {
                        c.classList.remove("show");
                        c.previousElementSibling.querySelector(".icono").classList.remove("rotate");
                    }
                });
                // Alterna el actual
                content.classList.toggle("show");
                icon.classList.toggle("rotate");
            });
        });
        // Cierra si se hace click fuera
        window.addEventListener("click", () => {
            document.querySelectorAll(".dropdown-content").forEach(c => {
                c.classList.remove("show");
                c.previousElementSibling.querySelector(".icono").classList.remove("rotate");
            });
        });
        // Seleccionamos todos los dropdowns
        const dropdowns = document.querySelectorAll('.dropdown');
        dropdowns.forEach(dropdown => {
            const btn = dropdown.querySelector('.dropdown-btn span'); // el span del botón
            const links = dropdown.querySelectorAll('.dropdown-content a'); // las opciones
            links.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault(); // evitamos que haga scroll o navegue
                    btn.textContent = link.textContent; // actualizamos el texto del botón
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