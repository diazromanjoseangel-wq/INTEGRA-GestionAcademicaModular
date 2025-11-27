<?php
include('../../conexion.php'); // tu conexión normal
// ==============================
// GUARDAR EVENTO SI SE ENVÍA FORMULARIO
// ==============================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre_evento'])) {
    $nombre_evento = $_POST['nombre_evento'];
    $descripcion = $_POST['descripcion'] ?? '';
    $fechas = $_POST['fechas'] ?? ''; // fechas separadas por coma
    $archivo_nombre = null;
// Manejo de archivo subido
    if (!empty($_FILES['archivo']['name'])) {
        $directorio = "../../Uploads/";
        if (!is_dir($directorio)) mkdir($directorio, 0777, true);
        $archivo_nombre = basename($_FILES['archivo']['name']);
        move_uploaded_file($_FILES['archivo']['tmp_name'], $directorio . $archivo_nombre);
    }
// Guardar en DB por cada fecha
    if (!empty($fechas) && !empty($nombre_evento)) {
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
}
// ==============================
// TRAER TODOS LOS EVENTOS EXISTENTES
// ==============================
$eventos_array = [];
$res = $conn->query("SELECT nombre_evento, fecha_creacion FROM eventos ORDER BY fecha_creacion ASC");
while($fila = $res->fetch_assoc()){
    $eventos_array[] = $fila;
}
?>
<!---------------------------------------------------------------------------------------------------------------->
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
            <div id="ContenidoGeneral">
                <div id="MainCalendario">
                <!-- Contenedor del Calendario -->
                <div id="calendario">
                    <div class="calendario-header">
                        <strong id="fecha-hoy"></strong>
                        <div style="margin-top:5px;">
                            <select id="mes-select"></select>
                            <select id="anio-select"></select>
                        </div>
                    </div>
                    <!-- Encabezado de días -->
                    <div class="grid encabezado-dias">
                        <div>do.</div>
                        <div>lu.</div>
                        <div>ma.</div>
                        <div>mi.</div>
                        <div>ju.</div>
                        <div>vi.</div>
                        <div>sa.</div>
                    </div>
                    <!-- Contenedor de los días -->
                    <div class="grid" id="calendario-grid"></div>
                    <label for="hora-select">Hora:</label>
                    <input type="time" id="hora-select" value="12:00">
                </div>
                <!-- Formulario de agendar evento -->
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
            <!-- Recuadro de información del evento -->
                <div id="info-evento">
                    <h4>Información del evento</h4>
                    <p id="info-texto">Selecciona una fecha para ver los detalles.</p>
                </div>
            </div>
        </div>
    </main>
<!---------------------------------------------------------------------------------------------------------------->
    <!-- --------------------------
    SCRIPT PARA OPCIÓN ACTIVA
    -------------------------- -->
        <script>
        // =====================================
        // OPCIÓN ACTIVA DEL MENÚ
        // =====================================
            document.addEventListener("DOMContentLoaded", () => {
                const menuItems = document.querySelectorAll("#Listado a");
                const currentPath = window.location.pathname.split("/").pop();
                menuItems.forEach(item => {
                    const linkPath = item.getAttribute("href").split("/").pop();
                    if (currentPath !== "main.php" && linkPath === currentPath) {
                        item.classList.add("is-active");
                    }
                    item.addEventListener("click", () => {
                        menuItems.forEach(el => el.classList.remove("is-active"));
                        item.classList.add("is-active");
                    });
                });
            });
        // =====================================
        // CONVERTIR EVENTOS PHP A JS
        // =====================================
            const eventos = <?php echo json_encode($eventos_array); ?>;
        // =====================================
        // VARIABLES GLOBALES
        // =====================================
            let today = new Date();
            let currentMonth = today.getMonth();
            let currentYear = today.getFullYear();
            let diasSeleccionados = new Set();
            document.addEventListener('DOMContentLoaded', () => {
                const monthNames = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio",
                                    "Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
            // Referencias
                const fechaHoyEl = document.getElementById('fecha-hoy');
                const mesSelect = document.getElementById('mes-select');
                const anioSelect = document.getElementById('anio-select');
                const calendarioGrid = document.getElementById('calendario-grid');
                const horaSelect = document.getElementById('hora-select');
                const info = document.getElementById('info-texto');
                const inputFechas = document.getElementById('fechas');
            // ===========================
            // LLENAR SELECTS
            // ===========================
                monthNames.forEach((m, i) => {
                    const opt = document.createElement('option');
                    opt.value = i;
                    opt.textContent = m;
                    mesSelect.appendChild(opt);
                });
                mesSelect.value = currentMonth;
                for (let y = currentYear - 10; y <= currentYear + 10; y++) {
                    const opt = document.createElement('option');
                    opt.value = y;
                    opt.textContent = y;
                    anioSelect.appendChild(opt);
                }
                anioSelect.value = currentYear;
            // ===========================
            // ACTUALIZAR INPUT OCULTO
            // ===========================
                function actualizarInputFechas() {
                    inputFechas.value = Array.from(diasSeleccionados)
                        .map(fecha => `${fecha}T${horaSelect.value}`)
                        .join(',');
                }
                horaSelect.addEventListener('change', actualizarInputFechas);
            // ===========================
            // FUNCIÓN PRINCIPAL: CALENDARIO
            // ===========================
                function renderCalendario() {
                    fechaHoyEl.textContent = today.toLocaleDateString();
                    calendarioGrid.innerHTML = "";
                    const firstDay = new Date(currentYear, currentMonth, 1).getDay();
                    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
                // Casillas vacías antes del día 1
                    for (let i = 0; i < firstDay; i++) {
                        const empty = document.createElement('div');
                        empty.classList.add('dia-num');
                        empty.style.visibility = 'hidden';
                        calendarioGrid.appendChild(empty);
                    }
                // Crear los días
                    for (let d = 1; d <= daysInMonth; d++) {
                        const diaDiv = document.createElement('div');
                        diaDiv.classList.add('dia-num');
                        diaDiv.textContent = d;
                    // Fecha YYYY-MM-DD
                        const fechaCompleta = `${currentYear}-${String(currentMonth+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
                        diaDiv.dataset.fecha = fechaCompleta;
                    // Día actual
                        if (
                            d === today.getDate() &&
                            currentMonth === today.getMonth() &&
                            currentYear === today.getFullYear()
                        ) {
                            diaDiv.classList.add('hoy');
                        }
                    // Día seleccionado previamente
                        if (diasSeleccionados.has(fechaCompleta)) {
                            diaDiv.classList.add('selected');
                        }
                    // CLICK EN EL DÍA
                        diaDiv.addEventListener('click', () => {
                        // Alterna selección
                            diaDiv.classList.toggle('selected');
                            if (diaDiv.classList.contains('selected')) {
                                diasSeleccionados.add(fechaCompleta);
                            } else {
                                diasSeleccionados.delete(fechaCompleta);
                            }
                        // Actualizar input hidden
                            actualizarInputFechas();
                        // Mostrar eventos del día
                            const eventosDia = eventos.filter(ev => ev.fecha_creacion.startsWith(fechaCompleta));
                            if (eventosDia.length > 0) {
                                info.innerHTML = eventosDia.map(ev =>
                                    `<p>📅 ${ev.nombre_evento} - ${ev.fecha_creacion}</p>`
                                ).join('');
                            } else {
                                info.textContent = 'No hay eventos agendados para esta fecha.';
                            }
                        });
                        calendarioGrid.appendChild(diaDiv);
                    }
                }
            // ===========================
            // CAMBIOS DE MES/AÑO
            // ===========================
                mesSelect.addEventListener('change', () => {
                    currentMonth = parseInt(mesSelect.value);
                    renderCalendario();
                });
                anioSelect.addEventListener('change', () => {
                    currentYear = parseInt(anioSelect.value);
                    renderCalendario();
                });
            // ===========================
            // PRIMER RENDER
            // ===========================
                renderCalendario();
            });
        // =====================================
        // TEMPORIZADOR INACTIVIDAD
        // =====================================
            const tiempoInactividad = 5 * 60 * 1000;
            let temporizador;
            function redirigir() {
                window.location.href = "Main.php";
            }
            function reiniciarTemporizador() {
                clearTimeout(temporizador);
                temporizador = setTimeout(redirigir, tiempoInactividad);
            }
            window.onload = reiniciarTemporizador;
            document.onmousemove = reiniciarTemporizador;
            document.onkeypress = reiniciarTemporizador;
            document.onclick = reiniciarTemporizador;
            document.onscroll = reiniciarTemporizador;
            document.ontouchstart = reiniciarTemporizador;
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