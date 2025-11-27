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
            <div id="Main">
            <!-- Contenido principal --> 
                <div class="form-container">
                <!-- ===========================
                FORMULARIO DE ENTREGA DE REPORTE
                ============================ -->
                    <form>
                    <!-- Campo: Título -->
                        <div class="form-group">
                            <label for="titulo">Titulo</label>
                            <input type="text" id="titulo" name="titulo" class="input-text">
                        </div>
                    <!-- Campo: Descripción -->
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea id="descripcion" name="descripcion" class="input-textarea"></textarea>
                        </div>
                    <!-- Campo: Subir archivo -->
                        <div class="form-group">
                            <label for="archivo">Seleccionar un Archivo</label>
                            <div class="file-upload">
                            <!-- Input real (oculto) -->
                                <input type="file" id="archivo" name="archivo" class="hidden-file">
                            <!-- Campo de texto que muestra el nombre del archivo -->
                                <input type="text" id="archivo-texto" class="input-file-display" placeholder="Ningún archivo seleccionado" readonly>
                            <!-- Botón/ícono que abre el explorador de archivos -->
                                <label for="archivo" class="file-label">
                                    <img src="../../Imagen/Archivo.png" alt="Subir archivo" class="file-icon">
                                </label>
                            </div>
                        </div>
                    <!-- Botón de envío -->
                        <div class="form-actions">
                            <button type="submit" class="btn-submit">ENTREGAR REPORTE</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
<!---------------------------------------------------------------------------------------------------------------->
    <script>
        /* ===========================
            ACTIVACIÓN DEL MENÚ
Marca como activa la opción según la página actual
        ============================ */
            document.addEventListener("DOMContentLoaded", () => {
                const menuItems = document.querySelectorAll("#Listado a"); // Todos los enlaces del menú
                const currentPath = window.location.pathname.split("/").pop(); // Nombre del archivo actual
                menuItems.forEach(item => {
                    const linkPath = item.getAttribute("href").split("/").pop();
                // Si estamos en una página distinta a main.html y coincide con el link → se activa
                    if (currentPath !== "main.php" && linkPath === currentPath) {
                        item.classList.add("is-active");
                    }
                // Al hacer clic, actualiza visualmente el menú
                    item.addEventListener("click", () => {
                        menuItems.forEach(el => el.classList.remove("is-active"));
                        item.classList.add("is-active");
                    });
                });
            });
        /* ===========================
        ACTUALIZAR NOMBRE DE ARCHIVO
        Muestra el nombre del archivo seleccionado en el input de texto
        ============================ */
            document.getElementById('archivo').addEventListener('change', function() {
                const fileInput = this;
                const textInput = document.getElementById('archivo-texto');
                if (fileInput.files.length > 0) {
                // Si hay archivo → mostrar su nombre
                    textInput.value = fileInput.files[0].name;
                } else {
                // Si no hay archivo → mensaje por defecto
                    textInput.value = "Ningún archivo seleccionado";
                }
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