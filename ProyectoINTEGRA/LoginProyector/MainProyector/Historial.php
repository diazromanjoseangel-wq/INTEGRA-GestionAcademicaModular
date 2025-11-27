<?php
include('../../conexion.php');

/* ============================
   OBTENER LISTA DE DOCENTES
   ============================ */
$sql = "SELECT nombre_personal FROM personal ORDER BY nombre_personal ASC";
$result = $conn->query($sql);

$lista = "";
while ($row = $result->fetch_assoc()) {
    $lista .= "<option value='".$row['nombre_personal']."'>";
}

/* ============================
   OBTENER LISTA DE DEPARTAMENTOS
   ============================ */
$sqlDep = "SELECT nombre_departamento FROM departamento ORDER BY nombre_departamento ASC";
$resultDep = $conn->query($sqlDep);

$listaDepartamentos = "";
while ($row = $resultDep->fetch_assoc()) {
    $listaDepartamentos .= "<option value='".$row['nombre_departamento']."'>";
}

/* ============================
   FUNCIONALIDAD AJAX PARA FILTROS
   ============================ */
if(isset($_GET['ajax']) && $_GET['ajax'] == "1"){
    $metodo = $_GET['metodo'];
    $sqlHistorial = "SELECT * FROM prestamo WHERE 1";

    if($metodo == "docente" && !empty($_GET['valor'])){
        $valor = $conn->real_escape_string($_GET['valor']);
        $sqlHistorial .= " AND nombre_personal LIKE '%$valor%'";
    } elseif($metodo == "departamento" && !empty($_GET['valor'])){
        $valor = $conn->real_escape_string($_GET['valor']);
        $sqlHistorial .= " AND nombre_departamento LIKE '%$valor%'";
    } elseif($metodo == "proyector" && !empty($_GET['valor'])){
        $valor = $conn->real_escape_string($_GET['valor']);
        $sqlHistorial .= " AND id_proyector LIKE '%$valor%'";
    } elseif($metodo == "fecha" && !empty($_GET['inicio']) && !empty($_GET['fin'])){
        $inicio = $conn->real_escape_string($_GET['inicio']);
        $fin = $conn->real_escape_string($_GET['fin']);
        $sqlHistorial .= " AND fecha BETWEEN '$inicio' AND '$fin'";
    }

    $sqlHistorial .= " ORDER BY id_prestamo DESC";
    $resultHistorial = $conn->query($sqlHistorial);

    if($resultHistorial && $resultHistorial->num_rows > 0){

        echo "<table class='historial-tabla'>";
        echo "<thead>
                <tr>
                    <th>ID</th>
                    <th>Docente</th>
                    <th>Proyector</th>
                    <th>Departamento</th>
                    <th>Fecha Préstamo</th>
                    <th>Hora Entrada</th>
                    <th>Estatus</th>
                </tr>
            </thead>";
        echo "<tbody>";

        while($row = $resultHistorial->fetch_assoc()){
            echo "<tr>
                    <td>".$row['id_prestamo']."</td>
                    <td>".$row['nombre_personal']."</td>
                    <td>".$row['id_proyector']."</td>
                    <td>".$row['nombre_departamento']."</td>
                    <td>".$row['fecha']."</td>
                    <td>".$row['hora_real_entrada']."</td>
                    <td>".$row['solicitud_estatus']."</td>
                </tr>";
        }

        echo "</tbody></table>";

    } else {

        echo "<p>No hay registros de historial.</p>";

    }
    exit;
}

/* ============================
   CARGA INICIAL DE TABLA COMPLETA
   ============================ */
$sqlHistorial = "SELECT * FROM prestamo ORDER BY id_prestamo DESC";
$resultHistorial = $conn->query($sqlHistorial);
?>

<!----------------------------------------------------------------------------------------------------------------->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>INTEGRA-GestiónAcadémicaModular</title>
    <link rel="stylesheet" href="Main.css">
    <link rel="icon" type="image/png" href="../../Imagen/LogoTecnm.png">
</head>
<!----------------------------------------------------------------------------------------------------------------->
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
                    <div class="historial-filtro">
                        <!-- SELECT DE MÉTODO -->
                        <select id="filtro-metodo" class="corto_historial1">
                            <option value="">Seleccione una opción</option>
                            <option value="docente">Nombre del docente</option>
                            <option value="proyector">Número de proyector</option>
                            <option value="departamento">Nombre del departamento</option>
                            <option value="fecha">Fecha</option>
                        </select>
                        <!-- CONTENEDOR donde cambia el input -->
                        <div id="filtro-dinamico"></div>
                        <!-- LISTA PARA AUTOCOMPLETAR -->
                        <datalist id="listaDocentes">
                            <?php echo $lista; ?>
                        </datalist>
                        <datalist id="listaDepartamentos">
                            <?php echo $listaDepartamentos; ?>
                        </datalist>
                    </div>
                <!--   CONTENEDOR DONDE SE CARGA LA TABLA    -->
                    <div id="resultado-busqueda" class="tabla-contenedor">
                        <?php
                        if($resultHistorial && $resultHistorial->num_rows > 0){
                            echo "<table class='historial-tabla'>";
                            echo "<thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Docente</th>
                                        <th>Proyector</th>
                                        <th>Departamento</th>
                                        <th>Fecha Préstamo</th>
                                        <th>Hora Entrada</th>
                                        <th>Estatus</th>
                                    </tr>
                                </thead>";
                            echo "<tbody>";
                            while($row = $resultHistorial->fetch_assoc()){
                                echo "<tr>
                                        <td>".$row['id_prestamo']."</td>
                                        <td>".$row['nombre_personal']."</td>
                                        <td>".$row['id_proyector']."</td>
                                        <td>".$row['nombre_departamento']."</td>
                                        <td>".$row['fecha']."</td> 
                                        <td>".$row['hora_real_entrada']."</td>
                                        <td>".$row['solicitud_estatus']."</td>
                                    </tr>";
                            }
                            echo "</tbody></table>";
                        } else {
                            echo "<p>No hay registros de historial.</p>";
                        }
                        ?>
                    </div>
            </div>
        </main>
<!---------------------------------------------------------------------------------------------------------------->
        <script>
        /* =============================
        ACTIVAR MENÚ
        ============================= */
        document.addEventListener("DOMContentLoaded", () => {
            const menuItems = document.querySelectorAll("#Listado a");
            const currentPath = window.location.pathname.split("/").pop();
            menuItems.forEach(item => {
                const linkPath = item.getAttribute("href").split("/").pop();
                if (currentPath !== "main.php" && linkPath === currentPath) {
                    item.classList.add("is-active");
                }
            });
        });

        /* =============================
        INACTIVIDAD
        ============================= */
        const tiempoInactividad = 5 * 60 * 1000;
        let temporizador;
        function redirigir() { window.location.href = "Main.php"; }
        function reiniciarTemporizador() {
            clearTimeout(temporizador);
            temporizador = setTimeout(redirigir, tiempoInactividad);
        }
        window.onload = reiniciarTemporizador;
        document.onmousemove = reiniciarTemporizador;
        document.onkeypress = reiniciarTemporizador;
        document.onclick = reiniciarTemporizador;
        document.onscroll = reiniciarTemporizador;

        /* ============================================
        CAMBIO DE INPUT SEGÚN MÉTODO
        ============================================ */
        document.getElementById("filtro-metodo").addEventListener("change", function() {
            const contenedor = document.getElementById("filtro-dinamico");
            contenedor.innerHTML = "";
            if (this.value === "fecha") {
                contenedor.innerHTML = `
                    <div class="fecha-rango">
                        <input type="date" id="fecha-inicio">
                        <input type="date" id="fecha-fin">
                    </div>
                `;
                document.getElementById("fecha-inicio").addEventListener("change", buscarHistorial);
                document.getElementById("fecha-fin").addEventListener("change", buscarHistorial);
                return;
            }
            let html = "";
            if (this.value === "docente") {
                html = `<input list="listaDocentes" id="filtro-valor" class="corto_historial2" placeholder="Escribe el nombre…">`;
            } else if (this.value === "departamento") {
                html = `<input list="listaDepartamentos" id="filtro-valor" class="corto_historial2" placeholder="Escribe el departamento…">`;
            } else if (this.value !== "") {
                html = `<input type="text" id="filtro-valor" class="corto_historial2" placeholder="Escribe aquí…">`;
            }
            contenedor.innerHTML = html;
            const input = document.getElementById("filtro-valor");
            if (input) input.addEventListener("input", buscarHistorial);
        });

        /* ============================================
        FUNCIÓN PARA CONSULTA FILTRADA EN EL MISMO PHP
        ============================================ */
        function buscarHistorial() {
            const metodo = document.getElementById("filtro-metodo").value;
            const resultado = document.getElementById("resultado-busqueda");
            if (metodo === "") {
                resultado.innerHTML = "";
                return;
            }

            // Crear FormData para enviar parámetros al mismo PHP
            let params = new URLSearchParams();
            params.append("ajax", "1"); // indicador de que es AJAX
            params.append("metodo", metodo);

            if (metodo === "fecha") {
                params.append("inicio", document.getElementById("fecha-inicio").value);
                params.append("fin", document.getElementById("fecha-fin").value);
            } else {
                const valor = document.getElementById("filtro-valor").value;
                params.append("valor", valor);
            }

            // Fetch al mismo archivo
            fetch("Historial.php?" + params.toString())
                .then(r => r.text())
                .then(data => resultado.innerHTML = data)
                .catch(() => resultado.innerHTML = "<p style='color:red;'>Error al obtener datos</p>");
        }
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