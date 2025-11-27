<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>INTEGRA-GestiónAcadémicaModular</title>
    <link rel="stylesheet" href="MainAlumno.css">
    <link rel="icon" type="image/png" href="../../Imagen/LogoTecnm.png">
</head>
<!---------------------------------------------------------------------------------------------------------------------->
    <body>
       <?php
        session_start();
        include('../../conexion.php'); // verifica la ruta correcta

        // Verificar que el alumno haya iniciado sesión
        if (!isset($_SESSION['correo_institucional'])) {
            header('Location: ../../index.php');
            exit;
        }

        // Variables de sesión
        $nombre = $_SESSION['nombre'];
        $numero_control = $_SESSION['numero_control'];
        $id_usuario = $_SESSION['id_usuario']; // ya debería venir del login

        // Consulta para obtener nombre del personal
        $stmt = $conn->prepare("SELECT nombre_personal FROM personal WHERE id_usuario = ?");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($fila = $resultado->fetch_assoc()) {
            $nombre_personal = $fila['nombre_personal']; // usar el nombre correcto de la columna
        } else {
            $nombre_personal = "No asignado";
        }

        $stmt->close();
        $conn->close();
        ?>
<!---------------------------------------------------------------------------------------------------------------------->
        <header>
            <img src="../../Imagen/LogoTec.png" class="logo-izq">
            <div class="titulo">
                <h2>INSTITUTO TECNOLÓGICO DE TUXTEPEC</h2>
                <h3>GESTIÓN ACADÉMICA MODULAR (INTEGRA)</h3>
            </div>
            <img src="../../Imagen/LogoTecnm.png" class="logo-der">
        </header>
    <div class="sub-header azul"></div>
    <div class="sub-header verde"></div>
<!---------------------------------------------------------------------------------------------------------------------->        
<!-- BARRAS HORIZONTALES -->
    <div class="barra-verde"></div>
    <div class="barra-azul"></div>
        <div class="container">
            <div class="info">
                <p><?php echo htmlspecialchars($nombre); ?></p>
                <p><?php echo htmlspecialchars($numero_control); ?></p>
            </div>
            <p>Docente: <?php echo htmlspecialchars($nombre_personal); ?></p>
            <div class="progress">
                <p>Barra de Progreso:</p>
                <div class="progress-bar"></div>
                <div class="progress-bar"></div>
                <div class="progress-bar"></div>
                <div class="progress-bar"></div>
                <div class="progress-bar"></div>
                <div class="progress-bar"></div>
            </div>
            <h4>Historial por Periodo</h4>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Calificación</th>
                            <th>Observaciones</th>
                            <th>Estatus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>--/--/----</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Sidebar -->
            <div class="sidebar">
                <a href="../../index.php" class="top-icon">
                    <img src="../../Imagen/Salida.png">
                </a>
                <div class="bottom-icons">
                <!-- Ícono que abre el explorador de archivos -->
                    <a id="openFile">
                        <img src="../../Imagen/Archivo.png" alt="Abrir archivo">
                    </a>

                <!-- Input de archivos oculto -->
                    <input type="file" id="fileInput" style="display: none;">

                    <a>
                <!-- Ícono que abre el calendario -->
                        <img src="../../Imagen/Calendario.png" id="calendarBtn">
                    </a>
                    <a href="">
                        <img src="../../Imagen/Notificacion.png">
                    </a>
                </div>
            </div>
        <!-- Modal del calendario -->
            <div class="calendar-modal" id="calendarModal">
                <div class="calendar-header">
                    <button id="prevMonth">&#8249;</button>
                    <span id="monthYear"></span>
                    <button id="nextMonth">&#8250;</button>
                    <button id="closeCalendar" class="close-btn">✖</button>
                </div>
                <div class="calendar-body" id="calendarBody"></div>
            </div>
        </div>
<!---------------------------------------------------------------------------------------------------------------------->
    <!-- 👇 Siempre el script aquí, antes del cierre del body -->
        <script>
            /*Explorador de Archivos*/
            // Referencias a los elementos
            const openFile = document.getElementById('openFile');
            const fileInput = document.getElementById('fileInput');
            // Cuando le damos clic al icono, abrimos el explorador de archivos
            openFile.addEventListener('click', (e) => {
                e.preventDefault(); // Evita comportamiento por defecto del <a>
                fileInput.click();  // Simula clic en el input oculto
            });
            // (Opcional) Detectar qué archivo seleccionó el usuario
            fileInput.addEventListener('change', () => {
                if (fileInput.files.length > 0) {
                    alert("Archivo seleccionado: " + fileInput.files[0].name);
                }
            });
        /*Calendario*/
            document.getElementById('closeCalendar').addEventListener('click', ()=>{
                calendarModal.style.display = 'none';
            });
            const calendarBtn = document.getElementById('calendarBtn');
            const calendarModal = document.getElementById('calendarModal');
            let today = new Date();
            let currentMonth = today.getMonth();
            let currentYear = today.getFullYear();
            const monthNames = ["Enero","Febrero","Marzo","Abril","Mayo","Junio",
                                "Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
            calendarBtn.addEventListener('click', ()=>{
                if (calendarModal.style.display === 'block') {
                    calendarModal.style.display = 'none';
                } else {
                    calendarModal.style.display = 'block';
                    renderCalendar(currentMonth, currentYear);
                }
            });
            document.getElementById('prevMonth').addEventListener('click', ()=>{
                currentMonth--;
                if(currentMonth < 0) { currentMonth = 11; currentYear--; }
                renderCalendar(currentMonth, currentYear);
            });
            document.getElementById('nextMonth').addEventListener('click', ()=>{
                currentMonth++;
                if(currentMonth > 11) { currentMonth = 0; currentYear++; }
                renderCalendar(currentMonth, currentYear);
            });
            function renderCalendar(month, year){
                const monthYear = document.getElementById('monthYear');
                monthYear.textContent = `${monthNames[month]} ${year}`;
                const calendarBody = document.getElementById('calendarBody');
                calendarBody.innerHTML = '';
                const days = ['D','L','M','X','J','V','S'];
                for(let d of days){
                    const dayHeader = document.createElement('div');
                    dayHeader.textContent = d;
                    dayHeader.classList.add('day-header');
                    calendarBody.appendChild(dayHeader);
                }
                const firstDay = new Date(year, month, 1).getDay();
                const daysInMonth = new Date(year, month+1, 0).getDate();

                for(let i=0; i<firstDay; i++){
                    const emptyCell = document.createElement('div');
                    calendarBody.appendChild(emptyCell);
                }
                for(let i=1; i<=daysInMonth; i++){
                    const dayCell = document.createElement('div');
                    dayCell.textContent = i;
                    dayCell.classList.add('day');

                    if(i === today.getDate() && month === today.getMonth() && year === today.getFullYear()){
                        dayCell.classList.add('today');
                    }
                    calendarBody.appendChild(dayCell);
                }
            }
        </script>
    </body>
</html>
