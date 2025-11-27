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
        <!-- LÍNEAS DECORATIVAS + BOTON SALIDA -->
            <div class="linea-azul-horizontal"></div>
            <div class="linea-verde-horizontal"></div>
            <a href="../../index.php" class="top-icon">
                <img src="../../Imagen/Salida.png">
            </a>
            <div id="Main"> 
            <!-- Contenido principal -->
            <!-- Calendario + formulario -->
                <div class="container">
                <!-- Calendario -->
                    <div class="calendario">
                        <div class="calendario-header">
                        <strong id="fecha-hoy"></strong>
                        <div style="margin-top:5px;">
                            <select id="mes-select"></select>
                            <select id="anio-select"></select>
                        </div>
                        </div>
                        <!-- Encabezados días -->
                        <div class="grid" id="calendario-grid">
                        <div class="dias">do.</div>
                        <div class="dias">lu.</div>
                        <div class="dias">ma.</div>
                        <div class="dias">mi.</div>
                        <div class="dias">ju.</div>
                        <div class="dias">vi.</div>
                        <div class="dias">sa.</div>
                        </div>
                    </div>
                <!-- Formulario -->
                    <div class="formulario">
                        <label for="evento">Evento:</label>
                        <input type="text" id="evento">
                        <label for="material">Material de Apoyo:</label>
                        <div id="material" style="border:1px solid #ccc; padding:5px; min-height:150px;">
                            <div id="adjuntos" style="margin-bottom:5px;"></div>
                            <div id="editor" contenteditable="true" style="min-height:80px; outline:none;"></div>
                        </div>
                    <!-- Contenedor para botón + imagen -->
                        <div class="acciones">
                            <button type="submit" class="agendar">AGENDAR</button>
                            <a id="imagen">
                                <img src="../../Imagen/Archivo.png" alt="Abrir archivo">
                            </a>
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

        /* Script para el calendario */
const monthNames = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
let today = new Date();
let currentMonth = today.getMonth();
let currentYear = today.getFullYear();
const fechaHoyEl = document.getElementById('fecha-hoy');
const mesSelect = document.getElementById('mes-select');
const anioSelect = document.getElementById('anio-select');
const calendarioGrid = document.getElementById('calendario-grid');

// Llenar selects
monthNames.forEach((m, i) => {
    const opt = document.createElement('option');
    opt.value = i;
    opt.textContent = m;
    mesSelect.appendChild(opt);
});
mesSelect.value = currentMonth;

for(let y = currentYear - 10; y <= currentYear + 10; y++){
    const opt = document.createElement('option');
    opt.value = y;
    opt.textContent = y;
    anioSelect.appendChild(opt);
}
anioSelect.value = currentYear;

// Renderizar calendario
function renderCalendario() {
    fechaHoyEl.textContent = today.toLocaleDateString();
    mesSelect.value = currentMonth;
    anioSelect.value = currentYear;

    // Limpiar solo los números de los días (excepto encabezados de días)
    calendarioGrid.querySelectorAll('.dia-num').forEach(d => d.remove());

    // Primer día del mes (domingo=0)
    let firstDay = new Date(currentYear, currentMonth, 1).getDay();

    // Cantidad de días del mes
    let daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

    // Espacios vacíos antes del primer día
    for(let i = 0; i < firstDay; i++){
        const empty = document.createElement('div');
        empty.classList.add('dia-num');
        calendarioGrid.appendChild(empty);
    }

    // Crear días del mes
    for(let d = 1; d <= daysInMonth; d++){
        const diaDiv = document.createElement('div');
        diaDiv.classList.add('dia-num');
        diaDiv.textContent = d;

        // Resaltar día actual
        if(d === today.getDate() && currentMonth === today.getMonth() && currentYear === today.getFullYear()){
            diaDiv.classList.add('hoy');
        }

        // Evento click para selección múltiple
        diaDiv.addEventListener('click', () => {
            diaDiv.classList.toggle('selected'); // alterna la selección
        });

        calendarioGrid.appendChild(diaDiv);
    }
}

// Listeners para actualizar calendario al cambiar mes o año
mesSelect.addEventListener('change', () => {
    currentMonth = parseInt(mesSelect.value);
    renderCalendario();
});

anioSelect.addEventListener('change', () => {
    currentYear = parseInt(anioSelect.value);
    renderCalendario();
});

// Llamada inicial
renderCalendario();



        // Eventos select
            mesSelect.addEventListener('change', e => {
            currentMonth = parseInt(e.target.value);
            renderCalendario();
            });
            anioSelect.addEventListener('change', e => {
            currentYear = parseInt(e.target.value);
            renderCalendario();
            });
            renderCalendario();
        // Referencias a los elementos
            const imagen = document.getElementById('imagen');
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.style.display = 'none';
            document.body.appendChild(fileInput);

            const adjuntos = document.getElementById('adjuntos'); // zona fija
            const editor = document.getElementById('editor');     // zona para escribir

            // Abrir explorador al dar clic en el ícono
            imagen.addEventListener('click', (e) => {
                e.preventDefault();
                fileInput.click();
            });

            // Cuando el usuario selecciona un archivo
            fileInput.addEventListener('change', () => {
                if (fileInput.files.length > 0) {
                    const archivo = fileInput.files[0];
                    const nombre = archivo.name;
                    const extension = nombre.split('.').pop().toLowerCase();

                    // Crear contenedor de item
                    const item = document.createElement('div');
                    item.style.display = "flex";
                    item.style.alignItems = "center";
                    item.style.gap = "10px";

                    let icono;

                    if (archivo.type.startsWith("image/")) {
                        icono = document.createElement("img");
                        icono.src = URL.createObjectURL(archivo);
                        icono.style.width = "25px";
                        icono.style.height = "25px";
                    } else if (extension === "pdf") {
                        icono = document.createElement("img");
                        icono.src = "../../Imagen/pdf.png";
                        icono.style.width = "25px";
                    } else if (extension === "doc" || extension === "docx") {
                        icono = document.createElement("img");
                        icono.src = "../../Imagen/doc.png";
                        icono.style.width = "25px";
                    } else if (extension === "xls" || extension === "xlsx") {
                        icono = document.createElement("img");
                        icono.src = "../../Imagen/excel.png";
                        icono.style.width = "25px";
                    } else {
                        icono = document.createElement("span");
                        icono.textContent = "📄";
                    }

                    const texto = document.createElement("span");
                    texto.textContent = nombre;

                    item.appendChild(icono);
                    item.appendChild(texto);

                    // Agregar item a la zona de adjuntos
                    adjuntos.appendChild(item);
                }
            });
        // Tiempo de inactividad: 1 minuto (60,000 ms)
            const tiempoInactividad = 5 * 60 * 1000; 
            let temporizador;
        // Redirige a la página principal
            function redirigir() {
                window.location.href = "MainAdmin.php"; // misma carpeta
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