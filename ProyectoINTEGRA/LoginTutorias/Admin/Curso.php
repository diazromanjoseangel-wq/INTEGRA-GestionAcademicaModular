<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>INTEGRA-GestiónAcadémicaModular</title>
    <link rel="stylesheet" href="Admin.css">
    <link rel="icon" type="image/png" href="../../Imagen/LogoTecnm.png">
</head>
<body>
    <!-- ENCABEZADO (igual que antes) -->
    <header>
        <img src="../../Imagen/LogoTec.png" class="logo-izq">
        <div class="titulo">
            <h2>INSTITUTO TECNOLÓGICO DE TUXTEPEC</h2>
            <h3>GESTIÓN ACADÉMICA MODULAR (INTEGRA)</h3>
        </div>
        <img src="../../Imagen/LogoTecnm.png" class="logo-der">
    </header>
    <div class="linea-azul"></div>
    <div class="linea-verde"></div>

    <main class="contenedor">
        <!-- MENÚ LATERAL (igual que antes) -->
        <div id="Listado">
            <a href="Usuarios.php"><img src="../../Imagen/UsuarioByN.png"><span class="menu-text">Usuarios</span></a>
            <a href="Calendario.php"><img src="../../Imagen/CalendarioByN.png"><span class="menu-text">Calendario</span></a>
            <a href="Actividades.php"><img src="../../Imagen/ActividadesByN.png"><span class="menu-text">Actividades</span></a>
            <a href="Actualizacion.php"><img src="../../Imagen/ActualizacionByN.png"><span class="menu-text">Actualización</span></a>
            <a href="Curso.php"><img src="../../Imagen/Curso.png"><span class="menu-text">Curso</span></a>
            <a href="Soporte.php"><img src="../../Imagen/SoporteByN.png"><span class="menu-text">Soporte</span></a>
        </div>
        <div class="linea-azul-horizontal"></div>
        <div class="linea-verde-horizontal"></div>

        <!-- CONTENEDOR DE BÚSQUEDA Y SALIDA -->
<div class="busqueda-salida">
    <img src="../../Imagen/Lupa.png" alt="Buscar" class="search-icon">
    <input type="text" id="searchInput" placeholder="Buscar alumno o número de control">
    <a href="../../index.php" class="top-icon">
        <img src="../../Imagen/Salida.png"> 
    </a>
</div>

        <div id="MainContainer">
            <div id="Main"> 
                <div class="botones-container">
                    <!-- Botón PERIODO MODIFICADO -->
                    <div class="dropdown">
                        <button class="dropdown-btn">
                            <span>PERIODO</span>
                            <img src="../../Imagen/Flecha.png" alt="flecha" class="icono">
                        </button>
                       <div class="dropdown-content">
                        <a href="#" data-periodo="Ene-Jun 2025">ENE-JUN 2025</a>
                        <a href="#" data-periodo="Ago-Dic 2025">AGO-DIC 2025</a>
                        <a href="#" data-periodo="Verano  2025">VERANO 2025</a>
                        <a href="#" data-periodo="Ene-Jun 2026">ENE-JUN 2026</a>
                        <a href="#" data-periodo="Ago-Dic 2026">AGO-DIC 2026</a>
                        <a href="#" data-periodo="Verano 2026">VERANO 2026</a>
                    </div>
                    </div>

                    <!-- Botón CARRERA (igual que antes) -->
                    <div class="dropdown">
                        <button class="dropdown-btn">
                            <span>CARRERA</span>
                            <img src="../../Imagen/Flecha.png" alt="flecha" class="icono">
                        </button>
                        <div class="dropdown-content">
                            <a href="#" data-carrera="SistemasComputacionales">ISC</a>
                            <a href="#" data-carrera="Civil">IC</a>
                            <a href="#" data-carrera="Bioquimica">IBQ</a>
                            <a href="#" data-carrera="GestionEmpresarial">IGE</a>
                            <a href="#" data-carrera="Administracion">LA</a>
                            <a href="#" data-carrera="ContadorPublico">CP</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABLA -->
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

            <?php
            include('../../conexion.php');
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            try {
                $sql = "SELECT 
                    a.nombre as nombre_alumno,
                    a.numero_control,
                    c.nombre_carrera as nombre_carrera,
                    p.descripcion as periodo,
                    a.estatus
                FROM alumnos a
                LEFT JOIN carrera c ON a.id_carrera = c.id_carrera
                LEFT JOIN periodo p ON a.id_periodo = p.id_periodo
                ORDER BY c.nombre_carrera, a.nombre";
                
                $result = $conn->query($sql);
                if (!$result) {
                    throw new Exception("Error en consulta: " . $conn->error);
                }
                
                $alumnos = array();
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $alumnos[] = $row;
                    }
                }
                
                $orden_carreras = [
                    'Bioquimica' => 1,
                    'Civil' => 2, 
                    'Electromecanica' => 3,
                    'SistemasComputacionales' => 4,
                    'Electronica' => 5,
                    'GestionEmpresarial' => 6,
                    'Informatica' => 7,
                    'Administracion' => 8,
                    'ContadorPublico' => 9,
                    'DesarrolloDeAplicaciones' => 10
                ];
                
                usort($alumnos, function($a, $b) use ($orden_carreras) {
                    $ordenA = $orden_carreras[$a['nombre_carrera']] ?? 11;
                    $ordenB = $orden_carreras[$b['nombre_carrera']] ?? 11;
                    if ($ordenA == $ordenB) {
                        return strcmp($a['nombre_alumno'], $b['nombre_alumno']);
                    }
                    return $ordenA - $ordenB;
                });
                
                echo "<script>const alumnosData = " . json_encode($alumnos) . ";</script>";
                
            } catch (Exception $e) {
                echo "<script>const alumnosData = {error: '" . $e->getMessage() . "'};</script>";
            }
            $conn->close();
            ?>
        </div>
    </main>

    <script>
        // FILTROS
        let carreraSeleccionada = '';
        let periodoSSeleccionado = '';
        let terminoBusqueda = '';

        // Filtro por CARRERA
        document.querySelectorAll('.dropdown-content a[data-carrera]').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                carreraSeleccionada = link.getAttribute('data-carrera');
                const dropdownBtn = link.closest('.dropdown').querySelector('.dropdown-btn span');
                dropdownBtn.textContent = link.textContent + ' ▾';
                link.closest('.dropdown-content').classList.remove('show');
                filtrarYMostrarAlumnos();
            });
        });

        // Filtro por PERIODO
        document.querySelectorAll('.dropdown-content a[data-periodo]').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                periodoSSeleccionado = link.getAttribute('data-periodo');
                const dropdownBtn = link.closest('.dropdown').querySelector('.dropdown-btn span');
                dropdownBtn.textContent = link.textContent + ' ▾';
                link.closest('.dropdown-content').classList.remove('show');
                filtrarYMostrarAlumnos();
            });
        });

        // Barra de búsqueda (en tiempo real)
        document.getElementById('searchInput').addEventListener('input', (e) => {
            terminoBusqueda = e.target.value.toLowerCase();
            filtrarYMostrarAlumnos();
        });

        // Función para filtrar y mostrar alumnos
        function filtrarYMostrarAlumnos() {
            const tablaBody = document.getElementById('tabla-body');
            try {
                let alumnosFiltrados = alumnosData;

                if (carreraSeleccionada) {
                    alumnosFiltrados = alumnosFiltrados.filter(alumno => 
                        alumno.nombre_carrera === carreraSeleccionada
                    );
                }

                if (periodoSSeleccionado) {
                    alumnosFiltrados = alumnosFiltrados.filter(alumno => 
                        alumno.periodo === periodoSSeleccionado
                    );
                }

                if (terminoBusqueda) {
                    alumnosFiltrados = alumnosFiltrados.filter(alumno => 
                        alumno.nombre_alumno.toLowerCase().includes(terminoBusqueda) ||
                        alumno.numero_control.toLowerCase().includes(terminoBusqueda)
                    );
                }

                tablaBody.innerHTML = '';

                if (alumnosFiltrados.length === 0) {
                    let mensaje = 'No hay alumnos registrados';
                    if (carreraSeleccionada && periodoSSeleccionado) {
                        mensaje = No hay alumnos en ${carreraSeleccionada} del periodo ${periodoSSeleccionado};
                    } else if (carreraSeleccionada) {
                        mensaje = No hay alumnos en la carrera ${carreraSeleccionada};
                    } else if (periodoSSeleccionado) {
                        mensaje = No hay alumnos en el periodo ${periodoSSeleccionado};
                    } else if (terminoBusqueda) {
                        mensaje = No se encontraron resultados para "${terminoBusqueda}";
                    }

                    tablaBody.innerHTML = `
                        <tr>
                            <td colspan="5" class="sin-datos">${mensaje}</td>
                        </tr>
                    `;
                } else {
                    alumnosFiltrados.forEach(alumno => {
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
            } catch (error) {
                console.error('Error al filtrar datos:', error);
                tablaBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="sin-datos" style="color: red;">
                            Error: ${error.message}
                        </td>
                    </tr>
                `;
            }
        }

        document.addEventListener("DOMContentLoaded", () => {
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
        });

        const tiempoInactividad = 5 * 60 * 1000; 
        let temporizador;
        function redirigir() {
            window.location.href = "MainAdmin.html";
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

        function cargarAlumnosDesdePHP() {
            filtrarYMostrarAlumnos();
        }
        document.addEventListener('DOMContentLoaded', cargarAlumnosDesdePHP);
    </script>
</body>
</html>X