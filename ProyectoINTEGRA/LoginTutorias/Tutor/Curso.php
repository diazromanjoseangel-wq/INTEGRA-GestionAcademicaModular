<?php
include('../../conexion.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$alumnos = array();
$periodos = array();
$carreras = array();

// MAPEO CORREGIDO DE ABREVIATURAS
$abreviaturas = [
    'ISC' => 'SistemasComputacionales',
    'IDA' => 'DesarrolloDeAplicaciones', 
    'IC' => 'Civil',
    'IBQ' => 'Bioquimica', // CORREGIDO: sin acento
    'IGE' => 'GestionEmpresarial',
    'II' => 'Informatica',
    'IE' => 'Electronica',
    'IEM' => 'Electromecanica',
    'LA' => 'Administracion',
    'CP' => 'ContadorPublico'
];

try {
    // CONSULTA PARA ALUMNOS
    $sql = "SELECT 
        a.nombre as nombre_alumno,
        a.numero_control,
        c.nombre_carrera,
        p.descripcion as periodo,
        a.estatus
    FROM alumnos a
    LEFT JOIN carrera c ON a.id_carrera = c.id_carrera
    LEFT JOIN periodo p ON a.id_periodo = p.id_periodo
    ORDER BY a.nombre";
    
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $alumnos[] = $row;
        }
        echo "<!-- Se cargaron " . count($alumnos) . " alumnos -->";
    }
    
    // CONSULTA PARA PERIODOS
    $sql_periodos = "SELECT id_periodo, descripcion 
                    FROM periodo 
                    WHERE descripcion NOT LIKE '%verano%' 
                    AND descripcion NOT LIKE '%Verano%'
                    AND descripcion NOT LIKE '%VERANO%'
                    ORDER BY id_periodo ASC";
    
    $result_periodos = $conn->query($sql_periodos);
    
    if ($result_periodos && $result_periodos->num_rows > 0) {
        while($row = $result_periodos->fetch_assoc()) {
            $periodos[] = $row;
        }
        echo "<!-- Se cargaron " . count($periodos) . " periodos (sin verano) -->";
    }
    
    // CONSULTA: OBTENER CARRERAS DESDE BD
    $sql_carreras = "SELECT id_carrera, nombre_carrera 
                        FROM carrera 
                        ORDER BY nombre_carrera";
    
    $result_carreras = $conn->query($sql_carreras);
    
    if ($result_carreras && $result_carreras->num_rows > 0) {
        while($row = $result_carreras->fetch_assoc()) {
            // BUSCAR ABREVIATURA CORRECTAMENTE
            $nombre_carrera_sin_espacios = str_replace(' ', '', $row['nombre_carrera']);
            $abreviatura_encontrada = '';
            
            // Buscar en el array de abreviaturas
            foreach ($abreviaturas as $abrev => $nombre) {
                if (strcasecmp($nombre_carrera_sin_espacios, $nombre) === 0) {
                    $abreviatura_encontrada = $abrev;
                    break;
                }
            }
            
            $row['abreviatura'] = $abreviatura_encontrada ? $abreviatura_encontrada : $row['nombre_carrera'];
            $carreras[] = $row;
            
            // DEBUG: Ver qué se está cargando
            echo "<!-- Carrera: " . $row['nombre_carrera'] . " -> Abreviatura: " . $row['abreviatura'] . " -->";
        }
        echo "<!-- Se cargaron " . count($carreras) . " carreras -->";
    } else {
        echo "<!-- No se encontraron carreras -->";
    }
    
} catch (Exception $e) {
    echo "<!-- Error: " . $e->getMessage() . " -->";
}

// Convertir a JSON
$alumnos_json = json_encode($alumnos);
$periodos_json = json_encode($periodos);
$carreras_json = json_encode($carreras);

if ($alumnos_json === false) $alumnos_json = '[]';
if ($periodos_json === false) $periodos_json = '[]';
if ($carreras_json === false) $carreras_json = '[]';
?>
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
            <!-- Contenido principal -->
                <div id="Main"> 
                    <!-- CONTENEDOR FLEX PARA ALINEAR -->
                    <div class="filtros-busqueda">
                        <!-- Botón PERIODO -->
                        <div class="dropdown">
                            <button class="dropdown-btn">
                                <span>PERIODO</span>
                                <img src="../../Imagen/Flecha.png" alt="flecha" class="icono">
                            </button>
                            <div class="dropdown-content" id="periodos-container"></div>
                        </div>
                        <!-- Botón CARRERA -->
                        <div class="dropdown">
                            <button class="dropdown-btn">
                                <span>CARRERA</span>
                                <img src="../../Imagen/Flecha.png" alt="flecha" class="icono">
                            </button>
                            <div class="dropdown-content" id="carreras-container"></div>
                        </div>
                        <!-- BARRA DE BÚSQUEDA (AHORA A LA DERECHA) -->
                        <div class="busqueda-container">
                            <img src="../../Imagen/Lupa.png" alt="Buscar" class="search-icon">
                            <input type="text" id="searchInput" placeholder="Buscar alumno o número de control">
                        </div>
                    </div>
                    <!-- TABLA CON CLASES UNIFICADAS -->
                    <div class="contenedor-tabla">
                        <div class="tabla-scroll-container">
                            <table class="tabla-alumnos">
                                <thead>
                                    <tr>
                                        <th>Nombre del Alumno</th>
                                        <th>Num. de Control</th>
                                        <th>Carrera</th>
                                        <th>Periodo</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody id="tabla-body">
                                    <tr>
                                        <td colspan="5" class="sin-datos">Cargando datos...</td>
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
        // ===============================
        //   VARIABLES GLOBALES (PHP → JS)
        // ===============================
        let alumnosData = <?php echo $alumnos_json; ?>;
        let periodosData = <?php echo $periodos_json; ?>;
        let carrerasData = <?php echo $carreras_json; ?>;
        console.log('=== DATOS CARGADOS ===');
        console.log('Alumnos:', alumnosData);
        console.log('Periodos:', periodosData);
        console.log('Carreras:', carrerasData);
        // FILTROS
        let carreraSeleccionada = '';
        let periodoSSeleccionado = '';
        let terminoBusqueda = '';


        // ===============================
        //   CARGAR PERIODOS
        // ===============================
        function cargarPeriodos() {
            const periodosContainer = document.getElementById('periodos-container');

            if (periodosData && periodosData.length > 0) {
                periodosContainer.innerHTML = '';

                periodosData.forEach(periodo => {
                    const link = document.createElement('a');
                    link.href = '#';
                    link.setAttribute('data-periodo', periodo.descripcion);
                    link.textContent = periodo.descripcion.toUpperCase();
                    periodosContainer.appendChild(link);
                });

                document.querySelectorAll('#periodos-container a[data-periodo]').forEach(link => {
                    link.addEventListener('click', (e) => {
                        e.preventDefault();
                        periodoSSeleccionado = link.getAttribute('data-periodo');
                        link.closest('.dropdown').querySelector('.dropdown-btn span').textContent =
                            link.textContent + ' ▾';
                        link.closest('.dropdown-content').classList.remove('show');
                        mostrarAlumnos();
                    });
                });
            } else {
                periodosContainer.innerHTML = '<a href="#" data-periodo="">No hay periodos disponibles</a>';
            }
        }


        // ===============================
        //   CARGAR CARRERAS
        // ===============================
        function cargarCarreras() {
            const carrerasContainer = document.getElementById('carreras-container');

            if (carrerasData && carrerasData.length > 0) {
                carrerasContainer.innerHTML = '';

                carrerasData.forEach(carrera => {
                    const link = document.createElement('a');
                    link.href = '#';
                    link.setAttribute('data-carrera', carrera.nombre_carrera);
                    link.textContent = carrera.abreviatura || carrera.nombre_carrera;
                    carrerasContainer.appendChild(link);
                });

                document.querySelectorAll('#carreras-container a[data-carrera]').forEach(link => {
                    link.addEventListener('click', (e) => {
                        e.preventDefault();
                        carreraSeleccionada = link.getAttribute('data-carrera');
                        link.closest('.dropdown').querySelector('.dropdown-btn span').textContent =
                            link.textContent + ' ▾';
                        link.closest('.dropdown-content').classList.remove('show');
                        mostrarAlumnos();
                    });
                });
            } else {
                carrerasContainer.innerHTML = '<a href="#" data-carrera="">No hay carreras disponibles</a>';
            }
        }


        // ===============================
        //   MOSTRAR ALUMNOS FILTRADOS
        // ===============================
        function mostrarAlumnos() {
            const tablaBody = document.getElementById('tabla-body');

            if (!alumnosData || alumnosData.length === 0) {
                tablaBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="sin-datos">No hay alumnos registrados en la base de datos</td>
                    </tr>
                `;
                return;
            }

            let alumnosMostrar = alumnosData.filter(alumno => {
                if (carreraSeleccionada && alumno.nombre_carrera !== carreraSeleccionada) return false;
                if (periodoSSeleccionado && alumno.periodo !== periodoSSeleccionado) return false;
                if (terminoBusqueda) {
                    const nombre = (alumno.nombre_alumno || '').toLowerCase();
                    const control = (alumno.numero_control || '').toLowerCase();
                    if (!nombre.includes(terminoBusqueda) && !control.includes(terminoBusqueda)) return false;
                }
                return true;
            });

            tablaBody.innerHTML = '';

            if (alumnosMostrar.length === 0) {
                let mensaje = 'No hay alumnos que coincidan con los filtros';

                if (carreraSeleccionada) mensaje = `No hay alumnos en la carrera ${carreraSeleccionada}`;
                if (periodoSSeleccionado) mensaje = `No hay alumnos en el periodo ${periodoSSeleccionado}`;
                if (terminoBusqueda) mensaje = `No se encontraron resultados para "${terminoBusqueda}"`;

                tablaBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="sin-datos">${mensaje}</td>
                    </tr>
                `;
            } else {
                alumnosMostrar.forEach(alumno => {
                    const fila = document.createElement('tr');
                    const claseEstado = alumno.estatus === 'Reprobado' ? 'estado-reprobado' : 'estado-aprobado';

                    fila.innerHTML = `
                        <td>${alumno.nombre_alumno || 'Sin nombre'}</td>
                        <td>${alumno.numero_control || 'Sin control'}</td>
                        <td>${alumno.nombre_carrera || 'Sin carrera'}</td>
                        <td>${alumno.periodo || 'Sin periodo'}</td>
                        <td class="${claseEstado}">${alumno.estatus || 'Sin estatus'}</td>
                    `;
                    tablaBody.appendChild(fila);
                });
            }
        }


        // ===============================
        //   BUSCADOR
        // ===============================
        document.getElementById('searchInput').addEventListener('input', (e) => {
            terminoBusqueda = e.target.value.toLowerCase();
            mostrarAlumnos();
        });


        // ===============================
        //   DROPDOWN
        // ===============================
        document.querySelectorAll(".dropdown-btn").forEach(btn => {
            btn.addEventListener("click", (e) => {
                e.stopPropagation();
                let content = btn.nextElementSibling;
                let icon = btn.querySelector(".icono");

                document.querySelectorAll(".dropdown-content").forEach(c => {
                    if (c !== content) {
                        c.classList.remove("show");
                        c.previousElementSibling.querySelector(".icono")?.classList.remove("rotate");
                    }
                });

                content.classList.toggle("show");
                icon.classList.toggle("rotate");
            });
        });

        window.addEventListener("click", () => {
            document.querySelectorAll(".dropdown-content").forEach(c => {
                c.classList.remove("show");
                c.previousElementSibling.querySelector(".icono")?.classList.remove("rotate");
            });
        });


        // ===============================
        //   ACTIVAR MENÚ LATERAL
        // ===============================
        function activarMenuLateral() {
            const menuItems = document.querySelectorAll("#Listado a");
            const currentPath = window.location.pathname.split("/").pop();

            menuItems.forEach(item => {
                const linkPath = item.getAttribute("href").split("/").pop();

                if (currentPath !== "mainTutor.php" && linkPath === currentPath) {
                    item.classList.add("is-active");
                }

                item.addEventListener("click", () => {
                    menuItems.forEach(el => el.classList.remove("is-active"));
                    item.classList.add("is-active");
                });
            });
        }


        // ===============================
        //   SISTEMA DE INACTIVIDAD
        // ===============================
        const tiempoInactividad = 5 * 60 * 1000;
        let temporizador;

        function redirigir() {
            window.location.href = "MainTutor.php";
        }

        function reiniciarTemporizador() {
            clearTimeout(temporizador);
            temporizador = setTimeout(redirigir, tiempoInactividad);
        }


        // ===============================
        //   INICIALIZAR TODO
        // ===============================
        document.addEventListener("DOMContentLoaded", () => {
            cargarPeriodos();
            cargarCarreras();
            mostrarAlumnos();
            activarMenuLateral();

            reiniciarTemporizador();
            document.onmousemove = reiniciarTemporizador;
            document.onkeypress = reiniciarTemporizador;
            document.onclick = reiniciarTemporizador;
            document.onscroll = reiniciarTemporizador;
            document.ontouchstart = reiniciarTemporizador;
        });
        </script>
    </body>
</html>