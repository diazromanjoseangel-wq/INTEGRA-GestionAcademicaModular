<?php
include("../../conexion.php");
date_default_timezone_set('America/Mexico_City');
// ==================== OBTENER SIGUIENTE ID ====================
$query = "SELECT MAX(id_prestamo) AS ultimo_id FROM prestamo";
$resultado = $conn->query($query);
$nuevo_id = ($resultado && $fila = $resultado->fetch_assoc()) ? intval($fila['ultimo_id']) + 1 : 1;
// ==================== CARGAR DOCENTES ====================
$lista = "";
$sql_docentes = "SELECT nombre_personal FROM personal ORDER BY nombre_personal ASC";
$res_docentes = $conn->query($sql_docentes);
while ($docente = $res_docentes->fetch_assoc()) {
    $nombre = htmlspecialchars($docente['nombre_personal']);
    $lista .= "<option value='$nombre'></option>";
}
// ==================== API: VERIFICAR PROYECTOR ====================
if (isset($_GET['verificar_proyector'])) {
    $id_proyector = intval($_GET['verificar_proyector']);
    $sql = "SELECT estatus, estado FROM proyector WHERE id_proyector = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_proyector);
    $stmt->execute();
    $res = $stmt->get_result();
    header("Content-Type: application/json");
    if ($res->num_rows === 0) {
        echo json_encode(["estado" => "ProyectorNoExistente"]);
        exit;
    }
    $proy = $res->fetch_assoc();
    // === 1. NO DISPONIBLE POR ESTATUS !== Disponible ===
    if ($proy['estatus'] !== "Disponible") {
        echo json_encode(["estado" => "NoDisponible"]);
        exit;
    }
    // === 2. NO DISPONIBLE POR ESTADO FÍSICO !== funcionamiento ===
    if ($proy['estado'] !== "funcionamiento") {
        echo json_encode(["estado" => "EnReparacion"]);
        exit;
    }
    // === 3. REVISAR SI TIENE PRÉSTAMO ACTIVO ===
    $sql2 = "SELECT id_prestamo FROM prestamo WHERE id_proyector = ? AND solicitud_estatus = 'en uso'";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("i", $id_proyector);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    if ($res2->num_rows > 0) {
        echo json_encode(["estado" => "EnUso"]);
        exit;
    }
    // === SI TODO ESTÁ BIEN ===
    echo json_encode(["estado" => "Disponible"]);
    exit;
}
// ==================== PROCESAR FORMULARIO ====================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_prestamo = intval($_POST['id_prestamo']);
    $docente = trim($_POST['docente']);
    $departamento = trim($_POST['nombre_departamento']);
    $id_proyector = intval($_POST['proyector']);
    $hora_salida = $_POST['salida'];
    $hora_entrada = $_POST['entrega'];
    $identificacion = $_POST['identificacion'];
    $ext = isset($_POST['ext']) ? 1 : 0;
    $hdmi = isset($_POST['hdmi']) ? 1 : 0;
    $vga = isset($_POST['vga']) ? 1 : 0;
    $fecha_actual = date("Y-m-d");
    // === VALIDAR ===
    if (!$docente || !$departamento || !$id_proyector || !$hora_salida || !$hora_entrada || !$identificacion) {
        echo "<script>alert('⚠️ Completa todos los campos.'); window.history.back();</script>";
        exit;
    }
    // === 1. VERIFICAR PRÉSTAMO ACTIVO ===
    $sql_check = "SELECT id_prestamo FROM prestamo WHERE id_proyector = ? AND solicitud_estatus = 'en uso'";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $id_proyector);
    $stmt_check->execute();
    $res_check = $stmt_check->get_result();
    if ($res_check->num_rows > 0) {
        echo "<script>alert('⚠️ El proyector ya tiene un préstamo activo.'); window.history.back();</script>";
        exit;
    }
    // === 2. VERIFICAR DISPONIBILIDAD DEL PROYECTOR ===
    $sql_estado = "SELECT estatus, estado FROM proyector WHERE id_proyector = ?";
    $stmt_estado = $conn->prepare($sql_estado);
    $stmt_estado->bind_param("i", $id_proyector);
    $stmt_estado->execute();
    $res_estado = $stmt_estado->get_result();
    if ($res_estado->num_rows === 0) {
        echo "<script>alert('❌ El proyector no existe.'); window.history.back();</script>";
        exit;
    }
    $p = $res_estado->fetch_assoc();
    if ($p['estatus'] !== "Disponible" || $p['estado'] !== "funcionamiento") {
        echo "<script>alert('⚠️ El proyector no está disponible o no está en funcionamiento.'); window.history.back();</script>";
        exit;
    }
    // === 3. INSERTAR PRESTAMO ===
    $sql_insert = "INSERT INTO prestamo
    (id_prestamo, nombre_personal, id_proyector, hora_salida, hora_entrada,
     accesorio_HDMI, accesorio_EXT, accesorio_VGA,
     nombre_departamento, identificacion, fecha, solicitud_estatus)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'en uso')";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param(
        "isissiiisss",
        $id_prestamo,
        $docente,
        $id_proyector,
        $hora_salida,
        $hora_entrada,
        $hdmi,
        $ext,
        $vga,
        $departamento,
        $identificacion,
        $fecha_actual
    );
    if ($stmt_insert->execute()) {
        // === 4. CAMBIAR PROYECTOR A EN USO ===
        $conn->query("UPDATE proyector SET estatus = 'EnUso' WHERE id_proyector = $id_proyector");
        echo "<script>alert('✅ Solicitud registrada correctamente.'); window.location.href='Solicitud.php';</script>";
        exit;
    } else {
        echo "<script>alert('❌ Error al registrar la solicitud.'); window.history.back();</script>";
        exit;
    }
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
                <form action="Solicitud.php" method="POST">
                    <div id="Main"> 
                        <div id="Prestamos" style="margin:50px auto; max-width:700px;">
                            <div class="encabezado-form">
                            <div class="solicitud">
                                <label for="id_prestamo">Número de solicitud:</label>
                                <input type="text" id="id_prestamo" name="id_prestamo" readonly value="<?php echo htmlspecialchars($nuevo_id, ENT_QUOTES); ?>">
                            </div>
                            <div class="fecha" id="fecha-hora"></div>
                            </div>
                        <!-- CONTENEDOR HORIZONTAL -->
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 50px;">
                        <!-- FORMULARIO A LA IZQUIERDA -->
                            <div class="campos-compacto" style="flex: 2;">
                                <label for="docente">Nombre del Docente:</label>
                                <input list="docentes" id="docente" name="docente" class="largo" placeholder="Escribe el nombre...">
                                <datalist id="docentes"><?php echo $lista; ?></datalist>
                                <label for="nombre_departamento">Departamento:</label>
                                <select id="nombre_departamento" name="nombre_departamento" class="largo">
                                    <option value="">-- Seleccione el departamento correspondiente --</option>
                                    <option value="CoordinacionDeMetodosyMedios">Coordinacion De Metodos y Medios</option>
                                    <option value="DepertamentoDeQuimicayBioquimica">Depertamento De Quimica y Bioquimica</option>
                                    <option value="DepartamentoDeCienciasBasicas">Departamento De Ciencias Basicas</option>
                                    <option value="DepartamentoDeMetalMecanica">Departamento De MetalMecanica</option>
                                    <option value="DepartamentoDeSistemasyComputacion">Departamento De Sistemas y Computacion</option>
                                    <option value="DepartamentoDeCienciasdelaTierra">Departamento De Ciencias de la Tierra</option>
                                    <option value="DepartamentoDeElectricayElectronica">Departamento De Electrica y Electronica</option>
                                    <option value="DepartamentoEconomicoAdministrativo">Departamento Economico Administrativo</option>
                                    <option value="Tutorias">Tutorias</option>
                                    <option value="Proyectores">Proyectores</option>
                                    <option value="DepartamentoDeLenguasExtranjeras">Departamento De Lenguas Extranjeras</option>
                                </select> 
                                <label for="proyector">Número del Proyector Asignado:</label>
                                <input type="text" id="proyector" name="proyector" class="corto">
                                <span id="estadoProyector" style="font-weight:bold; margin-left:10px;"></span>

                                <div class="horario">
                                    <label for="salida">Horario de Salida:</label>
                                        <input type="time" id="salida" name="salida" class="corto">
                                    <label for="entrega">Horario de Entrega:</label>
                                        <input type="time" id="entrega" name="entrega" class="corto">
                                </div>
                                <div class="extras-compacto">
                                    <label><input type="checkbox" name="ext"> EXT</label>
                                    <label><input type="checkbox" name="hdmi"> HDMI</label>
                                    <label><input type="checkbox" name="vga"> VGA</label>
                                </div>
                                <label for="identificacion">Documento dejado como identificación:</label>
                                <select id="identificacion" name="identificacion" class="largo">
                                    <option value="">-- Seleccione una opción --</option>
                                    <option value="CredencialInstitucional">Credencial Institucional</option>
                                    <option value="INE">INE</option>
                                    <option value="LicenciaDeConducir">Tarjeta de Circulación</option>
                                    <option value="Pasaporte">Pasaporte</option>
                                    <option value="NoDejo">No dejó</option>
                                </select>
                            </div>
                        <!-- BOTÓN A LA DERECHA -->
                            <div style="display: flex; align-items: flex-start;">
                                <button type="submit" class="asignar">ASIGNAR</button>
                            </div>
                            </div> <!-- fin del contenedor horizontal -->
                        </div>
                    </div>
                </form>
            </div>
        </main>
<!---------------------------------------------------------------------------------------------------------------->
<!-- --------------------------
SCRIPT PARA OPCIÓN ACTIVA
-------------------------- -->
        <script>
        document.addEventListener("DOMContentLoaded", () => {
            // === MENÚ ACTIVO ===
            const menuItems = document.querySelectorAll("#Listado a");
            const currentPath = window.location.pathname.split("/").pop();
            menuItems.forEach(item => {
                const linkPath = item.getAttribute("href").split("/").pop();
                if (currentPath !== "main.html" && linkPath === currentPath) {
                    item.classList.add("is-active");
                }
                item.addEventListener("click", () => {
                    menuItems.forEach(el => el.classList.remove("is-active"));
                    item.classList.add("is-active");
                });
            });
            // === MOSTRAR FECHA ACTUAL ===
            const fechaElemento = document.getElementById("fecha-hora");
            const ahora = new Date();
            fechaElemento.textContent = ahora.toLocaleDateString('es-MX', {
                weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
            });
            // === AUTO-RELLENAR ID DE PRÉSTAMO ===
            const idPrestamo = document.getElementById("id_prestamo");
            if (idPrestamo && idPrestamo.value.trim() === "") {
                idPrestamo.value = "<?php echo $nuevo_id; ?>";
            }
            // === VERIFICAR PROYECTOR ===
            const inputProyector = document.getElementById("proyector");
            const estadoSpan = document.getElementById("estadoProyector");
            const btnAsignar = document.querySelector(".asignar"); // botón de enviar
            inputProyector.addEventListener("input", () => {
                const id = inputProyector.value.trim();
                if (id === "") {
                    estadoSpan.textContent = "";
                    btnAsignar.disabled = false;
                    return;
                }
                fetch(`Solicitud.php?verificar_proyector=${id}`)
                    .then(res => res.json())
                    .then(data => {
                        let color = "black", texto = "", bloquear = false;
                        switch (data.estado) {
                            case "Disponible": texto = "✅ Disponible"; color = "green"; break;
                            case "EnUso": texto = "⚠️ En Uso"; color = "orange"; bloquear = true; break;
                            case "EnReparacion": texto = "🔧 En Reparación"; color = "blue"; bloquear = true; break;
                            case "NoDisponible": texto = "⛔ No Disponible"; color = "red"; bloquear = true; break;
                            case "ProyectorNoExistente": texto = "❌ No existente"; color = "darkred"; bloquear = true; break;
                            default: texto = "❓ Estado desconocido"; color = "gray";
                        }
                        estadoSpan.textContent = texto;
                        estadoSpan.style.color = color;
                        btnAsignar.disabled = bloquear;
                    })
                    .catch(err => {
                        console.error(err);
                        estadoSpan.textContent = "⚠️ Error al verificar";
                        estadoSpan.style.color = "red";
                    });
            });
            // === FECHA Y HORA AUTOMÁTICAS ===
            function actualizarFechaHora() {
                const fechaHoraDiv = document.getElementById("fecha-hora");
                const ahora = new Date();
                const opcionesFecha = { year: 'numeric', month: '2-digit', day: '2-digit' };
                fechaHoraDiv.textContent = ahora.toLocaleDateString('es-ES', opcionesFecha);
            }
            actualizarFechaHora();
            setInterval(actualizarFechaHora, 1000);
            // === HORARIO DE SALIDA AUTOMÁTICO ===
            function actualizarHoraSalida() {
                const salida = document.getElementById("salida");
                const ahora = new Date();
                const horas = ahora.getHours().toString().padStart(2, '0');
                const minutos = ahora.getMinutes().toString().padStart(2, '0');
                salida.value = `${horas}:${minutos}`;
            }
            actualizarHoraSalida();
            setInterval(actualizarHoraSalida, 1000);
            // === INACTIVIDAD ===
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