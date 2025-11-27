<?php
// ========== INICIA FUNCIÓN PARA EL CAMBIO DE SEMESTRE ==========

// Procesar guardado de cambios si se envió el formulario - DEBE IR AL INICIO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cambios'])) {
    
    // DEBUG: Ver qué estamos recibiendo
    error_log("DEBUG: Cambios recibidos - " . $_POST['cambios']);
    
    include('../../conexion.php');
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    $cambios_json = $_POST['cambios'];
    $cambios = json_decode($cambios_json, true);
    
    // DEBUG: Ver datos decodificados
    error_log("DEBUG: Cambios decodificados - " . print_r($cambios, true));
    
    try {
        $conn->begin_transaction();

        // Mapeo de periodos a id_periodo (sin verano)
        $periodos_map = [
            'Ene-Jun 2025' => 1,
            'Ago-Dic 2025' => 3,
            'Ene-Jun 2026' => 4,
            'Ago-Dic 2026' => 6
        ];

        $contador = 0;
        foreach ($cambios as $cambio) {
            $id_alumno = $cambio['id_alumno'];
            $nuevo_periodo = $cambio['nuevo_periodo'];
            $nuevo_semestre = $cambio['nuevo_semestre'];
            $id_periodo = $periodos_map[$nuevo_periodo] ?? 1;

            // DEBUG: Ver cada cambio individual
            error_log("DEBUG: Procesando cambio - Alumno ID: $id_alumno, Periodo: $nuevo_periodo, Nuevo Semestre: $nuevo_semestre");

            $sql = "UPDATE alumnos SET 
                    id_periodo = ?, 
                    semestre = ? 
                    WHERE id_alumno = ?";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $id_periodo, $nuevo_semestre, $id_alumno);
            
            if ($stmt->execute()) {
                error_log("✅ UPDATE exitoso - Filas afectadas: " . $stmt->affected_rows);
            } else {
                error_log("❌ UPDATE fallido - Error: " . $stmt->error);
            }
            
            $stmt->close();
            
            $contador++;
        }

        $conn->commit();
        
        // DEBUG: Confirmar cambios aplicados
        error_log("DEBUG: Cambios aplicados exitosamente - $contador registros");
        
        // Redirigir para evitar el bucle
        header("Location: " . $_SERVER['PHP_SELF'] . "?success=1&count=" . $contador);
        exit();
        
    } catch (Exception $e) {
        $conn->rollback();
        error_log("ERROR: " . $e->getMessage());
        header("Location: " . $_SERVER['PHP_SELF'] . "?error=1&message=" . urlencode($e->getMessage()));
        exit();
    }
}

// Consulta normal para cargar datos
include('../../conexion.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inicializar la variable para evitar errores
$alumnos = array();

try {
    $sql = "SELECT 
                a.id_alumno,
                a.nombre as nombre_alumno,
                a.numero_control,
                c.nombre_carrera as nombre_carrera,
                a.semestre,
                p.nombre_personal as docente,
                per.descripcion as periodo,
                per.id_periodo
            FROM alumnos a
            LEFT JOIN carrera c ON a.id_carrera = c.id_carrera
            LEFT JOIN periodo per ON a.id_periodo = per.id_periodo
            LEFT JOIN personal p ON a.id_usuario = p.id_usuario
            ORDER BY c.nombre_carrera, a.nombre";
    
    $result = $conn->query($sql);
    
    if (!$result) {
        throw new Exception("Error en consulta: " . $conn->error);
    }
    
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
    
} catch (Exception $e) {
    echo "<!-- Error: " . $e->getMessage() . " -->";
    $alumnos = array(); // Asegurar que sea un array vacío
}

// DEBUG: Estructura de alumnos
echo "<!-- DEBUG: Total alumnos: " . count($alumnos) . " -->";
if (count($alumnos) > 0) {
    echo "<!-- Primer alumno: " . htmlspecialchars(print_r($alumnos[0], true)) . " -->";
}

// Siempre crear la variable JavaScript, incluso si está vacía
echo "<script>const alumnosData = " . json_encode($alumnos) . ";</script>";

$conn->close();
?>

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
                    <img src="../../Imagen/CalendarioByN.png">
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
            <div class="linea-azul-horizontal"></div>
            <div class="linea-verde-horizontal"></div>
            <div id="MainContainer">
            <a href="../../index.php" class="top-icon">
                <img src="../../Imagen/Salida.png">
            </a>
                <div id="Main"> 
                    <!-- Contenido principal -->
                    <div class="botones-container">
                        <!-- Botón PERIODO CON FUNCIONALIDAD -->
                        <div class="dropdown">
                            <button class="dropdown-btn">
                                <span>PERIODO</span>
                                <img src="../../Imagen/Flecha.png" alt="flecha" class="icono">
                            </button>
                            <div class="dropdown-content">
                                <a href="#" data-periodo="Ene-Jun 2025">ENE-JUN 2025</a>
                                <a href="#" data-periodo="Ago-Dic 2025">AGO-DIC 2025</a>
                                <a href="#" data-periodo="Ene-Jun 2026">ENE-JUN 2026</a>
                                <a href="#" data-periodo="Ago-Dic 2026">AGO-DIC 2026</a>
                            </div>
                        </div>

                        <!-- Botón CARRERA CON FUNCIONALIDAD -->
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

                        <!-- BOTÓN PARA GUARDAR CAMBIOS (SIN FLECHA) -->
                        <button class="cambio-periodo-btn" title="Guardar cambios de periodo">
                            <span>Guardar Cambios</span>
                        </button>
                    </div>

                    <!-- TABLA DE ALUMNOS CON DROPDOWN EN PERIODO -->
                    <div class="contenedor-tabla">
                        <div class="tabla-scroll-container">
                            <table class="tabla-alumnos">
                                <thead>
                                    <tr>
                                        <th>Nombre del Alumno</th>
                                        <th>Num. de Control</th>
                                        <th>Carrera</th>
                                        <th>Semestre</th>
                                        <th>Docente</th>
                                        <th>Periodo</th>
                                    </tr>
                                </thead>
                                <tbody id="tabla-body">
                                    <tr>
                                        <td colspan="6" class="sin-datos">
                                            Cargando datos...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <script>
        // Mostrar mensaje de éxito si viene por GET
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            alert('Cambios guardados exitosamente. <?php echo isset($_GET['count']) ? $_GET['count'] . ' registros actualizados' : ''; ?>');
        <?php endif; ?>

        // Mostrar mensaje de error si viene por GET
        <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
            alert('Error al guardar cambios: <?php echo isset($_GET['message']) ? $_GET['message'] : ''; ?>');
        <?php endif; ?>

        // Verificar que los datos se cargan
        console.log('=== DEBUG: INICIANDO ===');
        console.log('alumnosData cargada:', alumnosData);
        console.log('Número de alumnos:', alumnosData ? alumnosData.length : 0);

        // Mostrar los primeros 3 alumnos para ver su estructura
        if (alumnosData && alumnosData.length > 0) {
            console.log('Primeros 3 alumnos:', alumnosData.slice(0, 3));
        }

        // Script para los dropdowns
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

        // FILTROS (FUNCIONALIDAD MANTENIDA)
        let carreraSeleccionada = '';
        let periodoSSeleccionado = '';

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

        // VARIABLES PARA CONTROLAR CAMBIOS
        let cambiosPendientes = [];
        const guardarBtn = document.querySelector('.cambio-periodo-btn');

        // FUNCIÓN PARA GUARDAR CAMBIOS EN BD
        guardarBtn.addEventListener('click', () => {
            console.log('=== INTENTANDO GUARDAR CAMBIOS ===');
            console.log('Cambios pendientes:', cambiosPendientes);
            
            if (cambiosPendientes.length === 0) {
                alert('No hay cambios pendientes para guardar');
                return;
            }

            // Verificar que todos los cambios tengan ID válido
            const cambiosInvalidos = cambiosPendientes.filter(c => c.id_alumno === 0);
            if (cambiosInvalidos.length > 0) {
                console.error('Cambios con ID inválido:', cambiosInvalidos);
                alert(Error: ${cambiosInvalidos.length} cambios no tienen ID válido. Revisa la consola.);
                return;
            }

            if (!confirm(¿Estás seguro de guardar ${cambiosPendientes.length} cambios?)) {
                return;
            }

            console.log('✅ Enviando cambios al servidor...');
            
            // Crear formulario dinámico para enviar los cambios
            const form = document.createElement('form');
            form.method = 'POST';
            form.style.display = 'none';
            
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'cambios';
            input.value = JSON.stringify(cambiosPendientes);
            
            form.appendChild(input);
            document.body.appendChild(form);
            
            console.log('Datos enviados:', cambiosPendientes);
            form.submit();
            
            // Deshabilitar botón temporalmente para evitar múltiples clics
            guardarBtn.disabled = true;
            guardarBtn.textContent = 'Guardando...';
        });

        function obtenerAlumnosFiltrados() {
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

            return alumnosFiltrados;
        }

        function habilitarBotonGuardar() {
            guardarBtn.disabled = false;
            guardarBtn.style.backgroundColor = '#28a745';
        }

        function filtrarYMostrarAlumnos() {
            const tablaBody = document.getElementById('tabla-body');
            
            try {
                // Verificar que alumnosData existe
                if (typeof alumnosData === 'undefined') {
                    throw new Error('No se pudieron cargar los datos de los alumnos');
                }

                const alumnosFiltrados = obtenerAlumnosFiltrados();

                tablaBody.innerHTML = '';

                if (alumnosFiltrados.length === 0) {
                    let mensaje = 'No hay alumnos registrados';
                    
                    if (carreraSeleccionada && periodoSSeleccionado) {
                        mensaje = No hay alumnos en ${carreraSeleccionada} del periodo ${periodoSSeleccionado};
                    } else if (carreraSeleccionada) {
                        mensaje = No hay alumnos en la carrera ${carreraSeleccionada};
                    } else if (periodoSSeleccionado) {
                        mensaje = No hay alumnos en el periodo ${periodoSSeleccionado};
                    }
                    
                    tablaBody.innerHTML = <tr><td colspan="6" class="sin-datos">${mensaje}</td></tr>;
                } else {
                    alumnosFiltrados.forEach(alumno => {
                        const fila = document.createElement('tr');
                        fila.innerHTML = `
                            <td>${alumno.nombre_alumno || 'Sin nombre'}</td>
                            <td>${alumno.numero_control || 'Sin control'}</td>
                            <td>${alumno.nombre_carrera || 'Sin carrera'}</td>
                            <td>${alumno.semestre || 'Sin semestre'}</td>
                            <td>${alumno.docente || 'Sin docente'}</td>
                            <td>
                                <div class="periodo-dropdown">
                                    <span class="periodo-actual">${alumno.periodo || 'Sin periodo'}</span>
                                    <img src="../../Imagen/Flecha.png" alt="flecha" class="periodo-flecha">
                                    <div class="periodo-opciones">
                                        <a href="#" data-periodo="Ene-Jun 2025">ENE-JUN 2025</a>
                                        <a href="#" data-periodo="Ago-Dic 2025">AGO-DIC 2025</a>
                                        <a href="#" data-periodo="Ene-Jun 2026">ENE-JUN 2026</a>
                                        <a href="#" data-periodo="Ago-Dic 2026">AGO-DIC 2026</a>
                                    </div>
                                </div>
                            </td>
                        `;
                        tablaBody.appendChild(fila);
                    });

                    // Agregar event listeners a los dropdowns de periodo
                    agregarEventListenersPeriodos();
                }

                // Mostrar el botón guardar siempre que haya datos
                if (alumnosFiltrados.length > 0) {
                    guardarBtn.style.display = 'flex';
                }

            } catch (error) {
                console.error('Error al filtrar datos:', error);
                tablaBody.innerHTML = <tr><td colspan="6" class="sin-datos" style="color: red;">Error: ${error.message}</td></tr>;
            }
        }

        function agregarEventListenersPeriodos() {
            document.querySelectorAll('.periodo-dropdown').forEach((dropdown, index) => {
                const flecha = dropdown.querySelector('.periodo-flecha');
                const opciones = dropdown.querySelector('.periodo-opciones');
                const periodoActual = dropdown.querySelector('.periodo-actual');
                const fila = dropdown.closest('tr');
                
                flecha.addEventListener('click', (e) => {
                    e.stopPropagation();
                    opciones.classList.toggle('show');
                    flecha.classList.toggle('rotate');
                });

                opciones.querySelectorAll('a').forEach(opcion => {
                    opcion.addEventListener('click', (e) => {
                        e.preventDefault();
                        const nuevoPeriodo = opcion.getAttribute('data-periodo');
                        const alumnoId = obtenerIdAlumnoDesdeFila(fila);
                        const alumnoNombre = fila.cells[0].textContent;
                        const semestreActual = obtenerSemestreDesdeFila(fila);
                        
                        // CALCULAR NUEVO SEMESTRE - SIEMPRE SE INCREMENTA
                        const nuevoSemestre = semestreActual + 1;
                        
                        console.log('Cambio detectado:', {
                            alumno: alumnoNombre,
                            id_alumno: alumnoId,
                            nuevo_periodo: nuevoPeriodo,
                            semestre_actual: semestreActual,
                            nuevo_semestre: nuevoSemestre
                        });
                        
                        // Verificar que tenemos un ID válido
                        if (alumnoId === 0) {
                            alert('Error: No se pudo identificar al alumno. Los cambios no se guardarán.');
                            return;
                        }
                        
                        // Agregar a cambios pendientes
                        cambiosPendientes.push({
                            id_alumno: alumnoId,
                            nuevo_periodo: nuevoPeriodo,
                            nuevo_semestre: nuevoSemestre,
                            alumno_nombre: alumnoNombre
                        });

                        // Actualizar visualmente EL SEMESTRE también
                        periodoActual.textContent = nuevoPeriodo;
                        fila.cells[3].textContent = nuevoSemestre; // ← ACTUALIZAR SEMESTRE EN LA TABLA
                        
                        opciones.classList.remove('show');
                        flecha.classList.remove('rotate');
                        
                        habilitarBotonGuardar();
                        
                        console.log('Cambios pendientes:', cambiosPendientes);
                    });
                });
            });

            // Cerrar dropdowns al hacer click fuera
            window.addEventListener('click', () => {
                document.querySelectorAll('.periodo-opciones').forEach(opciones => {
                    opciones.classList.remove('show');
                });
                document.querySelectorAll('.periodo-flecha').forEach(flecha => {
                    flecha.classList.remove('rotate');
                });
            });
        }

        function obtenerIdAlumnoDesdeFila(fila) {
            try {
                // Obtener el nombre del alumno desde la primera celda
                const nombreAlumno = fila.cells[0].textContent.trim();
                const numeroControl = fila.cells[1].textContent.trim();
                
                console.log('Buscando alumno:', {
                    nombre: nombreAlumno,
                    control: numeroControl
                });

                // Buscar por nombre Y número de control para mayor precisión
                const alumno = alumnosData.find(a => {
                    const matchNombre = a.nombre_alumno && a.nombre_alumno.trim() === nombreAlumno;
                    const matchControl = a.numero_control && a.numero_control.toString().trim() === numeroControl;
                    return matchNombre && matchControl;
                });

                if (alumno) {
                    console.log('✅ Alumno encontrado:', {
                        id: alumno.id_alumno,
                        nombre: alumno.nombre_alumno,
                        control: alumno.numero_control
                    });
                    return alumno.id_alumno;
                } else {
                    console.log('❌ Alumno NO encontrado. Buscando solo por nombre...');
                    
                    // Intentar solo por nombre
                    const alumnoPorNombre = alumnosData.find(a => 
                        a.nombre_alumno && a.nombre_alumno.trim() === nombreAlumno
                    );
                    
                    if (alumnoPorNombre) {
                        console.log('✅ Alumno encontrado solo por nombre:', alumnoPorNombre.id_alumno);
                        return alumnoPorNombre.id_alumno;
                    }
                    
                    console.log('❌ Alumno no encontrado en absoluto');
                    console.log('Datos disponibles:', alumnosData);
                    return 0;
                }
            } catch (error) {
                console.error('Error en obtenerIdAlumnoDesdeFila:', error);
                return 0;
            }
        }

        function obtenerSemestreDesdeFila(fila) {
            const semestreTexto = fila.cells[3].textContent.trim();
            const semestre = parseInt(semestreTexto);
            
            // Si no es un número válido, devolver 1
            if (isNaN(semestre) || semestre < 1) {
                return 1;
            }
            
            return semestre;
        }

        function cargarAlumnosDesdePHP() {
            setTimeout(() => {
                filtrarYMostrarAlumnos();
            }, 100);
        }
        
        document.addEventListener('DOMContentLoaded', cargarAlumnosDesdePHP);
        </script>
    </body>
</html>