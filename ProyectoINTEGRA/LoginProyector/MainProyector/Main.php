<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>INTEGRA-GestiónAcadémicaModular</title>
    <link rel="stylesheet" href="Main.css">
    <link rel="icon" type="image/png" href="../../Imagen/LogoTecnm.png">
</head>
<!---------------------------------------------------------------------------------------------------------------->
    <body>
    <!-- ENCABEZADO -->
        <header>
            <h1>
                <img src="../../Imagen/LogoTecWhite.png">
                <span class="bienvenidos">BIENVENIDOS A</span>
                <span class="integra">INTEGRA</span>
                <span class="sub">"GESTIÓN ACADÉMICA MODULAR"</span>
            </h1>
        </header>
    <!-- LÍNEA DECORATIVA -->
        <div class="linea-verde"></div>
<!---------------------------------------------------------------------------------------------------------------->
    <!-- CONTENEDOR PRINCIPAL -->
        <main class="contenedor">
            <div id="Listado">
                <a href="Solicitud.php">
                    <img src="../../Imagen/SolicitudByN.png">
                    <span class="menu-text">Solicitud de Prestamos</span>
                </a>
                <a href="Prestamo.php">
                    <img src="../../Imagen/PrestamoDelDia.png">
                    <span class="menu-text">Prestamo</span>
                </a>
                <a href="Calendario.php">
                    <img src="../../Imagen/CalendarioByN.png">
                    <span class="menu-text">Calendario</span>
                </a>
                <a href="Proyectores.php">
                    <img src="../../Imagen/ProyectorByN.png">
                    <span class="menu-text">Proyectores</span>
                </a>
                <a href="Historial.php">
                    <img src="../../Imagen/HistorialByN.png">
                    <span class="menu-text">Historial</span>
                </a>
                <a href="Reporte.php">
                    <img src="../../Imagen/Reporte.png">
                    <span class="menu-text">Reporte</span>
                </a>
                <div class="linea-azulhorizontal"></div>
            </div>
            <div id="MainContainer">
                <div id="Opciones">
                    <a href="">
                        <img src="../../Imagen/Notificacion.png">
                    </a>
                    <a href="../../index.php">
                        <img src="../../Imagen/Salida.png">
                    </a>
                    <div class="linea-azulvertical"></div>
                </div>
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
            // Si no estamos en main.html y el link coincide con la página actual
                if (currentPath !== "main.php" && linkPath === currentPath) {
                    item.classList.add("is-active");
                }
            // Manejo de clic para actualizar visualmente
                item.addEventListener("click", () => {
                    menuItems.forEach(el => el.classList.remove("is-active"));
                    item.classList.add("is-active");
                });
            });
        });
    /*Script para activar la notificacion*/
function mostrarNotificacion(texto) {
    const noti = document.createElement('div');
    noti.style.position = 'fixed';
    noti.style.right = '20px';
    noti.style.top = '20px';
    noti.style.background = '#333';
    noti.style.color = 'white';
    noti.style.padding = '15px 20px';
    noti.style.borderRadius = '8px';
    noti.style.boxShadow = '0 0 10px rgba(0,0,0,0.3)';
    noti.style.zIndex = '99999';
    noti.style.fontFamily = 'Arial';
    noti.style.opacity = '1';
    noti.style.transition = 'opacity 0.5s ease';
    noti.textContent = texto;

    document.body.appendChild(noti);

    setTimeout(() => {
        noti.style.opacity = '0';
        setTimeout(() => noti.remove(), 500);
    }, 5000);
}

function revisarEntrega() {
    fetch("notificaciones.php")
        .then(r => r.json())
        .then(data => {
            data.forEach(noti => {
                mostrarNotificacion(
                    `⏰ El docente ${noti.docente} está a punto de entregar el proyector ${noti.proyector}.`
                );
            });
        })
        .catch(e => console.error("❌ ERROR EN FETCH:", e));
}

// Ejecutar automáticamente cada 30 segundos
setInterval(revisarEntrega, 30000);

// Ejecutar una vez al cargar la página
document.addEventListener("DOMContentLoaded", revisarEntrega);
        </script>
    </body>
</html>