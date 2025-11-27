<?php
    include('../../conexion.php'); // Ajusta la ruta según tu estructura
$mensaje = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre_evento = $_POST['nombre_evento'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $fechas = $_POST['fechas'] ?? ''; // será un string con fechas y horas seleccionadas separadas por comas
        $archivo_nombre = null;
        // Manejo de archivo si se sube
        if (!empty($_FILES['archivo']['name'])) {
            $directorio = "../../Uploads/";
            if (!is_dir($directorio)) mkdir($directorio, 0777, true);
            $archivo_nombre = basename($_FILES['archivo']['name']);
            move_uploaded_file($_FILES['archivo']['tmp_name'], $directorio . $archivo_nombre);
        }
        // Insertar en la tabla eventos por cada fecha seleccionada
        $fecha_array = explode(',', $fechas);
        $stmt = $conn->prepare("INSERT INTO eventos (fecha_creacion, nombre_evento, descripcion, archivo) VALUES (?, ?, ?, ?)");
        foreach ($fecha_array as $fecha_hora) {
            $fecha_hora = trim($fecha_hora);
            if ($fecha_hora !== '') {
                $stmt->bind_param("ssss", $fecha_hora, $nombre_evento, $descripcion, $archivo_nombre);
                $stmt->execute();
            }
        }
    }
?>
<!---------------------------------------------------------------------------------------------------------------->
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
<main class="contenedor">
    <!-- CONTENEDOR DEL MENÚ LATERAL -->
    <div id="Listado">
        <a href="Usuarios.php">
            <img src="../../Imagen/UsuarioByN.png">
            <span class="menu-text">Usuarios</span>
        </a>
        <a href="Calendario.php">
            <img src="../../Imagen/CalendarioByN.png">
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
<!-- LÍNEAS DECORATIVAS + BOTON SALIDA -->
    <div class="linea-azul-horizontal"></div>
    <div class="linea-verde-horizontal"></div>
    <a href="../../index.php" class="top-icon">
        <img src="../../Imagen/Salida.png">
    </a>
    <!-- CONTENEDOR PRINCIPAL DEL CONTENIDO -->

        <!-- Botón de salida -->
        <!-- Contenedor del calendario y formulario -->
        <div id="MainContainer">
            <!-- CALENDARIO -->
            <div id="calendario">
                <div class="calendario-header">
                    <strong id="fecha-hoy"></strong>
                    <div style="margin-top:5px;">
                        <select id="mes-select"></select>
                        <select id="anio-select"></select>
                    </div>
                </div>
                <div class="grid" id="calendario-grid">
                    <div class="dias">do.</div>
                    <div class="dias">lu.</div>
                    <div class="dias">ma.</div>
                    <div class="dias">mi.</div>
                    <div class="dias">ju.</div>
                    <div class="dias">vi.</div>
                    <div class="dias">sa.</div>
                </div>
                <label for="hora-select">Hora:</label>
                <input type="time" id="hora-select" value="12:00">
            </div>

            <!-- FORMULARIO -->
            <div id="Formulario"> 
                <form method="POST" enctype="multipart/form-data" id="form-evento">
                    <label for="evento">Evento:</label>
                    <input type="text" id="evento" name="nombre_evento" required>
                    <label for="material">Material de Apoyo:</label>
                    <div id="material">
                        <div id="editor" contenteditable="true"></div>
                    </div>
                    <label for="archivo">Archivo (PDF o Word):</label>
                    <input type="file" id="archivo" name="archivo" accept=".pdf,.doc,.docx">
                    <input type="hidden" id="fechas" name="fechas">
                    <button type="submit" class="agendar">AGENDAR</button>
                </form>
            </div>
        </div>
</main>

<!---------------------------------------------------------------------------------------------------------------->
<script>
// Variables y selects
const monthNames = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
let today = new Date();
let currentMonth = today.getMonth();
let currentYear = today.getFullYear();

const fechaHoyEl = document.getElementById('fecha-hoy');
const mesSelect = document.getElementById('mes-select');
const anioSelect = document.getElementById('anio-select');
const calendarioGrid = document.getElementById('calendario-grid');
const horaSelect = document.getElementById('hora-select');

let diasSeleccionados = new Set(); // 👈 solo guardamos días seleccionados

// Llena los selects
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

// Renderiza calendario
function renderCalendario() {
    fechaHoyEl.textContent = today.toLocaleDateString();
    mesSelect.value = currentMonth;
    anioSelect.value = currentYear;

    calendarioGrid.querySelectorAll('.dia-num').forEach(d => d.remove());

    let firstDay = new Date(currentYear, currentMonth, 1).getDay();
    let daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

    // Casillas vacías
    for(let i = 0; i < firstDay; i++){
        const empty = document.createElement('div');
        empty.classList.add('dia-num');
        calendarioGrid.appendChild(empty);
    }

    for(let d = 1; d <= daysInMonth; d++){
        const diaDiv = document.createElement('div');
        diaDiv.classList.add('dia-num');
        diaDiv.textContent = d;

        if(d === today.getDate() && currentMonth === today.getMonth() && currentYear === today.getFullYear()){
            diaDiv.classList.add('hoy');
        }

        if(diasSeleccionados.has(d)){
            diaDiv.classList.add('selected');
        }

        diaDiv.addEventListener('click', () => {
            diaDiv.classList.toggle('selected');

            if(diaDiv.classList.contains('selected')){
                diasSeleccionados.add(d);
            } else {
                diasSeleccionados.delete(d);
            }
        });

        calendarioGrid.appendChild(diaDiv);
    }
}

// Cambios de mes/año
mesSelect.addEventListener('change', () => {
    currentMonth = parseInt(mesSelect.value);
    renderCalendario();
});
anioSelect.addEventListener('change', () => {
    currentYear = parseInt(anioSelect.value);
    renderCalendario();
});

renderCalendario();

// -------------------
// Formulario
// -------------------
const editor = document.getElementById('editor');
const formEvento = document.getElementById('form-evento');

formEvento.addEventListener('submit', (e)=>{
    // Copiamos contenido del editor
    const inputDesc = document.createElement('input');
    inputDesc.type = 'hidden';
    inputDesc.name = 'descripcion';
    inputDesc.value = editor.innerHTML;
    e.target.appendChild(inputDesc);

    // Generamos las fechas con la hora actual del input
    const hora = horaSelect.value || "12:00";
    const fechas = Array.from(diasSeleccionados).map(d => {
        return `${currentYear}-${String(currentMonth+1).padStart(2,'0')}-${String(d).padStart(2,'0')} ${hora}:00`;
    });

    const inputFechas = document.createElement('input');
    inputFechas.type = 'hidden';
    inputFechas.name = 'fechas';
    inputFechas.value = fechas.join(',');
    e.target.appendChild(inputFechas);
});

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