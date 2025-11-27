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
                <a href="Reportes.php">
                    <img src="../../Imagen/ReporteByN.png">
                    <span class="menu-text">Reportes</span>
                </a>
                <a href="Actividades.php">
                    <img src="../../Imagen/ActividadesByN.png">
                    <span class="menu-text">Actividades</span>
                </a>
                <a href="Soporte.php">
                    <img src="../../Imagen/SoporteByN.png">
                    <span class="menu-text">Soporte</span>
                </a>
            </div>
            <div class="linea-azul-horizontal"></div>
            <div class="linea-verde-horizontal"></div>
            <div id="MainContainer">
                <div id="Main"> 
                <!-- Contenido principal -->
                    <section class="page_404">
                        <div class="container">
                            <div class="row"> 
                                <div class="col-sm-12">
                                    <div class="col-sm-10 col-sm-offset-1 text-center">
                                        <div class="four_zero_four_bg">
                                            <!-- GIF como imagen -->
                                            <img src="https://cdn.dribbble.com/users/285475/screenshots/2083086/dribbble_1.gif" alt="gif 404">                                          
                                            <!-- Texto debajo -->
                                            <div class="contant_box_404">
                                                <p>Por favor seleccione la opción a desear.!!</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
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

                // Si no estamos en main.php y el link coincide con la página actual
                if (currentPath !== "maintutor.php" && linkPath === currentPath) {
                    item.classList.add("is-active");
                }

                // Manejo de clic para actualizar visualmente
                item.addEventListener("click", () => {
                    menuItems.forEach(el => el.classList.remove("is-active"));
                    item.classList.add("is-active");
                });
            });
        });
        </script>
    </body>
</html>