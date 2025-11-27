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
        <!-- html2canvas (convierte la gráfica en imagen) -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
        <!-- jsPDF (genera el PDF) -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
                <div id="Main">
                <!-- CONTENEDOR LINEAL DE FILTROS -->
                    <div id="contenedorFiltros">
                        <!-- Select principal -->
                        <div class="filtro-item">
                            <label>Tipo:</label>
                            <select id="selectTipoReporte">
                                <option value="">Seleccione...</option>
                                <option value="prestamos_dia">Préstamos por día</option>
                                <option value="prestamos_mes">Préstamos por mes</option>
                                <option value="prestamos_periodo">Préstamos por periodo</option>
                                <option value="proyectores_usados">Proyectores más usados</option>
                                <option value="estado_proyectores">Estado de proyectores</option>
                                <option value="prestamos_docente">Préstamos por docente</option>
                                <option value="prestamos_departamento">Préstamos por departamento</option>
                                <option value="tiempo_promedio">Tiempo promedio de uso</option>
                            </select>
                        </div>
                        <!-- Segundo select dinámico -->
                        <div class="filtro-item" id="contenedorSelectSecundario" style="display:none;">
                            <label id="labelSegundoSelect"></label>
                            <select id="selectSecundario"></select>
                        </div>
                        <!-- Selector de periodo (ÚNICO – NO se duplicará) -->
                        <div class="filtro-item" id="contenedorPeriodo" style="display:none;">
                            <label>Periodo:</label>
                            <select id="inputPeriodo">
                                <option value="">Seleccione periodo...</option>
                                <option value="enero-julio">Enero - Julio</option>
                                <option value="verano">Verano</option>
                                <option value="agosto-diciembre">Agosto - Diciembre</option>
                            </select>
                        </div>
                        <!-- Filtro especial para TIEMPO PROMEDIO (día / mes / periodo) -->
                        <div class="filtro-item" id="contenedorTiempoTipo" style="display:none;">
                            <label>Tipo:</label>
                            <select id="selectTiempoTipo">
                                <option value="">Seleccione...</option>
                                <option value="dia">Por día</option>
                                <option value="mes">Por mes</option>
                                <option value="periodo">Por periodo</option>
                            </select>
                        </div>
                        <!-- Selección de departamento -->
                        <div class="filtro-item" id="contenedorDepartamento" style="display:none;">
                            <label>Departamento:</label>
                            <select id="inputDepartamento"></select>
                        </div>
                        <!-- Selección de docentes -->
                        <div class="filtro-item" id="contenedorDocente" style="display:none;">
                            <label>Docente:</label>
                            <select id="inputDocente"></select>
                        </div>
                        <!-- Fecha para día -->
                        <div class="filtro-item" id="contenedorFecha" style="display:none;">
                            <label>Fecha:</label>
                            <input type="date" id="inputFecha">
                        </div>
                        <!-- Mes y año -->
                        <div class="filtro-item" id="contenedorMes" style="display:none;">
                            <label>Mes:</label>
                            <select id="inputMes">
                                <option value="">Mes...</option>
                                <option value="1">Enero</option><option value="2">Febrero</option>
                                <option value="3">Marzo</option><option value="4">Abril</option>
                                <option value="5">Mayo</option><option value="6">Junio</option>
                                <option value="7">Julio</option><option value="8">Agosto</option>
                                <option value="9">Septiembre</option><option value="10">Octubre</option>
                                <option value="11">Noviembre</option><option value="12">Diciembre</option>
                            </select>
                            <label>Año:</label>
                            <select id="inputAnio">
                                <option value="">Año...</option>
                            </select>
                        </div>
                    </div> <!-- fin contenedorFiltros -->
                    <!-- Gráfica centrada -->
                    <div class="grafica-box">
                        <canvas id="graficaReporte"></canvas>
                    </div>
                    <!-- Botón PDF -->
                    <div class="exportar">
                        <button id="btnExportarPDF" disabled>Exportar PDF</button>
                    </div>
                </div>
            </div>
        </main>
<!---------------------------------------------------------------------------------------------------------------->
        <script>
        /***************************************************
         *  SCRIPT: menú + inactividad + reportes/Chart/pdf
         ***************************************************/
        document.addEventListener("DOMContentLoaded", () => {

            /* ================ MENU ACTIVO ================ */
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

            /* =============== INACTIVIDAD =============== */
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
            document.ontouchstart = reiniciarTemporizador;

            /* ========== PALETA Y UTILIDADES ========== */
            const paletaReportes = [
                "#a8ab9b","#172a38","#ec4b5d","#f48773","#e0c590",
                "#110303","#c3062c","#ff194b","#8fa080","#708066"
            ];
            function obtenerColor(i) { return paletaReportes[i % paletaReportes.length]; }

            /* ========== REFERENCIAS DOM ========== */
            const selectTipo = document.getElementById("selectTipoReporte");

            const contenedorSecundario = document.getElementById("contenedorSelectSecundario");
            const labelSecundario = document.getElementById("labelSegundoSelect");
            const selectSecundario = document.getElementById("selectSecundario");

            const contenedorPeriodo = document.getElementById("contenedorPeriodo");
            const inputPeriodo = document.getElementById("inputPeriodo");

            const contenedorDepartamento = document.getElementById("contenedorDepartamento");
            const selectDepartamento = document.getElementById("inputDepartamento");

            const contenedorDocente = document.getElementById("contenedorDocente");
            const selectDocente = document.getElementById("inputDocente");

            const contenedorFecha = document.getElementById("contenedorFecha");
            const inputFecha = document.getElementById("inputFecha");

            const contenedorMes = document.getElementById("contenedorMes");
            const inputMes = document.getElementById("inputMes");
            const inputAnio = document.getElementById("inputAnio");

            const contenedorTiempoTipo = document.getElementById("contenedorTiempoTipo");
            const selectTiempoTipo = document.getElementById("selectTiempoTipo");

            const btnPDF = document.getElementById("btnExportarPDF");
            const lienzo = document.getElementById("graficaReporte");
            let chart = null;

            /* ========== Rellenar años ========== */
            if (inputAnio) {
                const anioActual = new Date().getFullYear();
                for (let i = anioActual - 4; i <= anioActual; i++) {
                    inputAnio.innerHTML += `<option value="${i}">${i}</option>`;
                }
            }

            /* ========== UI según tipo de reporte ========== */
            selectTipo.addEventListener("change", () => {
                ocultarTodo();
                btnPDF.disabled = true;
                const v = selectTipo.value;

                // PRÉSTAMOS POR DÍA
                if (v === "prestamos_dia") {
                    contenedorFecha.style.display = "flex";
                }

                // PRÉSTAMOS POR MES
                else if (v === "prestamos_mes") {
                    contenedorMes.style.display = "flex";
                    inputMes.style.display = "block";
                    inputMes.previousElementSibling.style.display = "inline";
                }

                // PRÉSTAMOS POR PERIODO
                else if (v === "prestamos_periodo") {
                    contenedorPeriodo.style.display = "flex";
                    contenedorMes.style.display = "flex";
                    inputMes.style.display = "none";
                    inputMes.previousElementSibling.style.display = "none";
                }

                // PROYECTORES MÁS USADOS
                else if (v === "proyectores_usados") {
                    contenedorMes.style.display = "flex";
                }

                // ESTADO PROYECTORES
                else if (v === "estado_proyectores") {
                    contenedorPeriodo.style.display = "flex";
                    contenedorMes.style.display = "flex";
                    inputMes.style.display = "none";
                    inputMes.previousElementSibling.style.display = "none";
                }

                // PRÉSTAMOS POR DOCENTE
                else if (v === "prestamos_docente") {
                    contenedorPeriodo.style.display = "flex";
                    contenedorMes.style.display = "flex";
                    inputMes.style.display = "none";
                    inputMes.previousElementSibling.style.display = "none";

                    contenedorDepartamento.style.display = "flex";
                    selectDepartamento.innerHTML = `<option value="">Cargando...</option>`;

                    fetch("obtener_departamentos.php")
                        .then(r => r.json())
                        .then(list => {
                            selectDepartamento.innerHTML = `<option value="">Seleccione departamento...</option>`;
                            list.forEach(d => {
                                selectDepartamento.innerHTML += `<option value="${d}">${d}</option>`;
                            });
                        });
                }

                // PRÉSTAMOS POR DEPARTAMENTO
                else if (v === "prestamos_departamento") {
                    contenedorSecundario.style.display = "flex";
                    labelSecundario.innerText = "Departamento:";
                    selectSecundario.innerHTML = `<option value="">Cargando...</option>`;
                    fetch("obtener_departamentos.php")
                        .then(r => r.json())
                        .then(list => {
                            selectSecundario.innerHTML = `<option value="">Seleccione...</option>`;
                            list.forEach(d => selectSecundario.innerHTML += `<option value="${d}">${d}</option>`);
                        });
                }

                // TIEMPO PROMEDIO
                else if (v === "tiempo_promedio") {
                    contenedorTiempoTipo.style.display = "flex";
                }
            });

            /* ========== Ocultar todo ========== */
            function ocultarTodo() {
                contenedorSecundario.style.display = "none";
                contenedorPeriodo.style.display = "none";
                contenedorDepartamento.style.display = "none";
                contenedorDocente.style.display = "none";
                contenedorFecha.style.display = "none";
                contenedorMes.style.display = "none";
                contenedorTiempoTipo.style.display = "none";

                inputMes.style.display = "block";
                if (inputMes.previousElementSibling) inputMes.previousElementSibling.style.display = "inline";
            }

            /* ========== REACCIÓN A OPCIONES DE TIEMPO PROMEDIO ========== */
            selectTiempoTipo.addEventListener("change", () => {
                const modo = selectTiempoTipo.value;

                contenedorFecha.style.display = "none";
                contenedorMes.style.display = "none";
                contenedorPeriodo.style.display = "none";

                if (modo === "dia") {
                    contenedorFecha.style.display = "flex";
                }
                else if (modo === "mes") {
                    contenedorMes.style.display = "flex";
                    inputMes.style.display = "block";
                    inputMes.previousElementSibling.style.display = "inline";
                }
                else if (modo === "periodo") {
                    contenedorPeriodo.style.display = "flex";
                    contenedorMes.style.display = "flex";
                    inputMes.style.display = "none";
                    inputMes.previousElementSibling.style.display = "none";
                }
            });

            /* ======== EVENTOS PARA GENERAR ======== */
            if (selectSecundario) selectSecundario.addEventListener("change", validarYGenerar);
            if (inputPeriodo) inputPeriodo.addEventListener("change", validarYGenerar);
            if (inputFecha) inputFecha.addEventListener("change", validarYGenerar);
            if (inputMes) inputMes.addEventListener("change", validarYGenerar);
            if (inputAnio) inputAnio.addEventListener("change", validarYGenerar);
            if (selectDepartamento) selectDepartamento.addEventListener("change", validarYGenerar);
            if (selectDocente) selectDocente.addEventListener("change", validarYGenerar);
            selectTiempoTipo.addEventListener("change", validarYGenerar);

            /* ========== VALIDAR ========= */
            function validarYGenerar() {
                const ui = selectTipo.value;
                const periodo = inputPeriodo.value;

                if (ui === "prestamos_dia" && inputFecha.value)
                    return generarGrafica("por_dia", { fecha: inputFecha.value });

                if (ui === "prestamos_mes" && inputMes.value && inputAnio.value)
                    return generarGrafica("por_mes", { mes: inputMes.value, anio: inputAnio.value });

                if (ui === "prestamos_periodo" && periodo && inputAnio.value)
                    return generarGrafica("por_periodo", { periodo, anio: inputAnio.value });

                if (ui === "proyectores_usados" && inputMes.value && inputAnio.value)
                    return generarGrafica("proyectores_mes", { mes: inputMes.value, anio: inputAnio.value });

                if (ui === "estado_proyectores" && periodo && inputAnio.value)
                    return generarGrafica("estado_periodo", { periodo, anio: inputAnio.value });

                if (ui === "prestamos_docente" && periodo && inputAnio.value && selectDepartamento.value)
                    return generarGrafica("docente_periodo", {
                        periodo,
                        anio: inputAnio.value,
                        departamento: selectDepartamento.value
                    });

                if (ui === "prestamos_departamento" && selectSecundario.value)
                    return generarGrafica("por_departamento", { departamento: selectSecundario.value });

                if (ui === "tiempo_promedio") {
                    const modo = selectTiempoTipo.value;

                    if (modo === "dia" && inputFecha.value)
                        return generarGrafica("promedio_dia", { fecha: inputFecha.value });

                    if (modo === "mes" && inputMes.value && inputAnio.value)
                        return generarGrafica("promedio_mes", { mes: inputMes.value, anio: inputAnio.value });

                    if (modo === "periodo" && periodo && inputAnio.value)
                        return generarGrafica("promedio_periodo", { periodo, anio: inputAnio.value });
                }
            }

            /* ========== AJAX para la gráfica ========== */
            function generarGrafica(tipo, extra = {}) {
                const formData = new FormData();
                formData.append("tipo", tipo);

                Object.entries(extra).forEach(([k, v]) => formData.append(k, v));

                fetch("generar_reporte.php", { method: "POST", body: formData })
                    .then(r => r.json())
                    .then(data => {
                        if (!data || !Array.isArray(data.labels)) {
                            alert("Error en datos del servidor.");
                            return;
                        }
                        if (chart) chart.destroy();
                        chart = new Chart(lienzo, {
                            type: "bar",
                            data: {
                                labels: data.labels,
                                datasets: [{
                                    label: "Total",
                                    data: data.valores,
                                    backgroundColor: data.valores.map((_, i) => obtenerColor(i))
                                }]
                            },
                            options: { responsive: true }
                        });
                        btnPDF.disabled = false;
                    });
            }

            /* ========== EXPORTAR PDF ========== */
            btnPDF.addEventListener("click", async () => {
                try {
                    const { jsPDF } = window.jspdf;
                    const canvas = await html2canvas(document.querySelector(".grafica-box"));
                    const img = canvas.toDataURL("image/png");
                    const pdf = new jsPDF("p", "mm", "a4");

                    pdf.text("Reporte de Préstamos", 10, 10);
                    const imgProps = pdf.getImageProperties(img);
                    const pdfWidth = 190;
                    const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

                    pdf.addImage(img, "PNG", 10, 20, pdfWidth, pdfHeight);
                    pdf.save("reporte.pdf");

                } catch (e) {
                    alert("Error al generar PDF");
                }
            });

        });
</script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- html2canvas para capturar la gráfica -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <!-- jsPDF para generar el PDF -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="js/reportes.js"></script>
    </body>
</html>