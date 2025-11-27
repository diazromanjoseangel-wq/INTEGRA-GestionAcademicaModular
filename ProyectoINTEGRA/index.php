<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>INTEGRA-GestiónAcadémicaModular</title>
    <link rel="stylesheet" href="index.css">
    <link rel="icon" type="image/png" href="Imagen/LogoTecnm.png">
</head>
<!----------------------------------------------------------------------------------------------------------------->
<body>
<!-- ENCABEZADO BIENVENIDA -->
    <header>
        <h1>
            <span class="bienvenidos">BIENVENIDOS A</span>
            <span class="integra">INTEGRA</span>
            <span class="sub">"GESTIÓN ACADÉMICA MODULAR"</span>
        </h1>
    </header>
<!-- LÍNEA DECORATIVA -->
    <div class="linea-verde"></div>
<!----------------------------------------------------------------------------------------------------------------->
<!-- CONTENIDO PRINCIPAL -->
    <main class="contenedor">
    <!-- NIVEL 1: Logo izquierdo -->
        <div class="nivel logo-izq">
            <img src="Imagen/LogoTec.png" alt="Logo izquierdo">
        </div>
    <!-- NIVEL 2: Módulos (Proyector y Tutorías) -->
        <div class="nivel modulos">
            <div class="modulo">
                <a href="LoginProyector/Proyectores.php">
                    <img src="Imagen/Proyector.png" alt="Proyector">
                    <h2>Préstamo de<br>Proyectores</h2>
                </a>
            </div>
            <div class="modulo">
                <a href="LoginTutorias/Tutorias.php">
                    <img src="Imagen/Tutorias.png" alt="Tutorías">
                    <h2>Área de<br>Tutorías</h2>
                </a>
            </div>
        </div>
    <!-- NIVEL 3: Logo derecho -->
        <div class="nivel logo-der">
            <img src="Imagen/LogoTecnm.png" alt="Logo derecho">
        </div>
    </main>
</body>
</html>