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
                    <div class="pj-contenedor">
                        <div class="pj-botones">
                            <button class="pj-btn pj-verde">
                                PROYECTOR   
                                <a href="Proyectores.php" class="pj-icono">▲</a>
                            </button>
                            <button class="pj-btn pj-azul">AGREGAR</button>
                            <button class="pj-btn pj-rojo">MODIFICAR</button>
                        </div>
                        <form class="pj-formulario">
                            <!-- Nombre del docente -->
                            <label for="pj-docente">Nombre del Docente:</label>
                            <input type="text" id="pj-docente" name="nombre_docente" class="pj-largo">
                            <!-- Usuario -->
                            <div class="fila-usuario">
                                <div class="columna">
                                    <label for="pj-usuario">Usuario:</label>
                                    <input type="text" id="pj-usuario" name="usuario" class="pj-corto">
                                </div>
                            <!-- Contraseña -->
                                <div class="columna">
                                    <label for="pj-contraseña">Contraseña:</label>
                                    <input type="password" id="pj-contraseña" name="contraseña" class="pj-corto">
                                </div>
                            </div>
                            <!-- Departamento -->
                            <label for="pj-departamento">Departamento:</label>
                            <select id="pj-departamento" name="id_departamento" class="pj-largo">
                                <option value="">-- Seleccione el departamento correspondiente --</option>
                                <option value="CoordinacionDeMetodosyMedios">Coordinacion De Metodos y Medios</option>
                                <option value="DepertamentoDeQuimicayBioquimica">Depertamento De Quimica y Bioquimica</option>
                                <option value="DepartamentoDeCienciasBasicas">Departamento De Ciencias Basicas</option>
                                <option value="DepartamentoDeMetalMecanica">Departamento De MetalMecanica</option>
                                <option value="DepartamentoDeSistemasyComputacion">Departamento De Sistemas y Computacion</option>
                                <option value="DepartamentoDeCienciasdelaTierra">Departamento De Ciencias de la Tierra</option>
                                <option value="DepartamentoDeElectricayElectronica">Departamento De Electrica y Electronica</option>
                                <option value="DepartamentoEconomicoAdministrativo">Departamento Economico Administrativo</option>
                            </select>
                        </form>
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
        // Tiempo de inactividad: 1 minuto (60,000 ms)
            const tiempoInactividad = 5 * 60 * 1000; 
            let temporizador;
        // Redirige a la página principal
            function redirigir() {
                window.location.href = "Main.php"; // misma carpeta
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