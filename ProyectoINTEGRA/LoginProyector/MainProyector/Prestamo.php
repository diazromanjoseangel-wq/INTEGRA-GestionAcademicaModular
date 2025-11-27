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
                <!-- Contenido principal -->
                    <div class="contenedorprestamo">
                        <div class="fecha-contenedor-wrapper">
                            <div class="fecha-contenedor">
                                <label class="fecha-label">Seleccionar Fecha:</label>
                                <input type="date" id="fecha" class="fecha-input">
                            </div>
                            <!-- Mensaje compacto a la derecha -->
                            <div id="mensaje-flotante"></div>
                        </div>
                        <div class="tabla-contenedor-prestamo">
                            <table class="tabla-prestamo">
                                <thead id="thead-fijo">
                                    <tr>
                                    <th>No. Proyector</th>
                                    <th>Nombre del Docente</th>
                                    <th>Hora de Entrega</th>
                                    <th>HDMI</th>
                                    <th>VGA</th>
                                    <th>Extensión</th>
                                    <th>Identificacion</th>
                                    </tr>
                                </thead>
                                <tbody id="tabla-prestamos-body">
                                    <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><input type="checkbox"></td>
                                    <td><input type="checkbox"></td>
                                    <td><input type="checkbox"></td>
                                    <td></td>
                                    </tr>
                                </tbody>
                            </table>
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
document.addEventListener("DOMContentLoaded", () => {

    // ================== Activar menú ==================
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

    // ================== Fecha actual ==================
    const fechaInput = document.getElementById("fecha");
    const hoy = new Date();
    const hoyLocal = new Date(hoy.getTime() - hoy.getTimezoneOffset() * 60000);
    fechaInput.value = hoyLocal.toISOString().split("T")[0];

    // ================== Temporizador inactividad ==================
    const tiempoInactividad = 5 * 60 * 1000;
    let temporizador;

    function redirigir() { window.location.href = "Main.php"; }

    function reiniciarTemporizador() {
        clearTimeout(temporizador);
        temporizador = setTimeout(redirigir, tiempoInactividad);
    }

    ["onmousemove","onkeypress","onclick","onscroll","ontouchstart"].forEach(e => {
        document[e] = reiniciarTemporizador;
    });

    reiniciarTemporizador();

    // ================== NUEVO SISTEMA DE MENSAJES COMPACTOS ==================
    const mensajeFlotante = document.getElementById("mensaje-flotante");

    function mostrarMensaje(texto, tipo = "success") {
        mensajeFlotante.textContent = texto;

        mensajeFlotante.className = "";
        mensajeFlotante.classList.add(tipo === "success" ? "msj-success" : "msj-error");

        mensajeFlotante.style.opacity = "1";

        setTimeout(() => {
            mensajeFlotante.style.opacity = "0";
        }, 3000);
    }

    // ================== Cargar préstamos ==================
    const tbody = document.getElementById("tabla-prestamos-body"); // ✔ CORREGIDO

    function cargarPrestamos(fechaSeleccionada = fechaInput.value) {

        mostrarMensaje("Cargando...", "success");

        fetch(`prestamo_api.php?fecha_prestamos=${fechaSeleccionada}`)
            .then(res => res.json())
            .then(data => {
                tbody.innerHTML = "";

                if (!Array.isArray(data) || data.length === 0) {
                    tbody.innerHTML = `
                        <tr><td colspan="7" style="text-align:center; color: gray;">
                            ⚠️ Sin préstamos para esta fecha.
                        </td></tr>`;
                    mostrarMensaje("Sin préstamos", "error");
                    return;
                }

                data.forEach(p => {
                    const row = document.createElement("tr");
                    row.setAttribute("data-id", p.id_prestamo);

                    row.innerHTML = `
                        <td>${p.id_proyector}</td>
                        <td>${p.nombre_personal}</td>
                        <td>${convertirHora12h(p.hora_entrada)}</td>
                        <td><input type="checkbox" ${p.accesorio_HDMI == 1 ? "checked" : ""} disabled></td>
                        <td><input type="checkbox" ${p.accesorio_VGA == 1 ? "checked" : ""} disabled></td>
                        <td><input type="checkbox" ${p.accesorio_EXT == 1 ? "checked" : ""} disabled></td>
                        <td>${p.identificacion ?? ""}</td>
                        <td>
                            <button 
                                class="btn-devolver"
                                data-id="${p.id_prestamo}"
                                ${p.solicitud_estatus === "devuelto" ? "disabled" : ""}>
                                ${p.solicitud_estatus === "devuelto" ? "✔️ Devuelto" : "🔄 Devolver"}
                            </button>
                        </td>
                    `;

                // ================== Formato 12hrs ==================
                    tbody.appendChild(row);
                                    });

                                    function convertirHora12h(hora24) {
                        if (!hora24) return "";
                        let [hora, minutos, segundos] = hora24.split(":");
                        hora = parseInt(hora);

                        let periodo = hora >= 12 ? "PM" : "AM";
                        hora = hora % 12;
                        hora = hora ? hora : 12; // Si es 0 → 12

                        return `${hora}:${minutos} ${periodo}`;
                    }

                // ================== Botón devolver ==================
                document.querySelectorAll(".btn-devolver").forEach(btn => {
                    btn.addEventListener("click", () => {
                        const idPrestamo = btn.dataset.id;

                        if (confirm(`¿Esta seguro que desea devolver el proyector?`)) {
                            devolverPrestamo(idPrestamo);
                        }
                    });
                });

                mostrarMensaje(`${data.length} proyector(es) cargado(s) correctamente`, "success");
            })
            .catch(() => {
                tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; color:red;">❌ Error al obtener los datos.</td></tr>`;
                mostrarMensaje("Error al obtener datos", "error");
            });
    }

    // ================== Función devolver ==================
    function devolverPrestamo(idPrestamo) {

    if (!confirm("¿Está seguro que desea devolver el proyector?")) {
        return;
    }

    // Solicita comentario
    let comentario = prompt("Agregar comentario (opcional):");

    // Si cancela el prompt → no continua
    if (comentario === null) {
        mostrarMensaje("Operación cancelada", "error");
        return;
    }

    fetch("devolver_api.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "id_prestamo=" + encodeURIComponent(idPrestamo) +
            "&comentario=" + encodeURIComponent(comentario)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            mostrarMensaje("Proyector devuelto", "success");

            const fila = document.querySelector(`tr[data-id='${idPrestamo}']`);
            if (fila) fila.remove();
        } else {
            mostrarMensaje("Error al devolver", "error");
        }
    })
    .catch(err => console.error("Error:", err));
}

    // ================== Inicializar ==================
    cargarPrestamos(fechaInput.value);
    fechaInput.addEventListener("change", () => cargarPrestamos(fechaInput.value));

});


// ================== Notificaciones ==================
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

// Ejecutar cada 30s
setInterval(revisarEntrega, 30000);

// Ejecutar al cargar
document.addEventListener("DOMContentLoaded", revisarEntrega);
</script>


    </body>
</html>