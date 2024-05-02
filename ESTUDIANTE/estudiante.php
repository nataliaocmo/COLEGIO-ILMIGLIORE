<?php
session_start();

// Verifica si el usuario está autenticado como estudiante
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== "estudiante") {
    header("Location: /sql/log_in.html");
    exit();
}

// Accede a los datos del estudiante desde la sesión
$estudiante = $_SESSION['estudiante'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estudiante</title>
    <link rel="stylesheet" href="estudiante.css">
</head>
<body>
    <div class="container">
        <div class="left-side">
            <div class="top-bar">
                <div class="icons">
                    <a href="/sql/log_in.html"><img src="/sql/IMG/inicio.png" alt="Home"></a>
                </div>
                <div class="red-bar">ESTUDIANTE</div>
            </div>
            <div class="formulario">
                <h2>Datos Personales</h2>
                    <p>Documento de Identificación</p>
                    <input type="text" name="DocId" value="<?php echo $estudiante['DOCUMENTO_DE_IDENTIDAD']; ?>" readonly>
                    <p>Nombres</p>
                    <input type="text" name="Nombre" value="<?php echo $estudiante['NOMBRE']; ?>" readonly>
                    <p>Apellidos</p>
                    <input type="text" name="Apellido" value="<?php echo $estudiante['APELLIDO']; ?>" readonly>
                    <p>Genero</p>
                    <input type="text" name="Genero" value="<?php echo $estudiante['GENERO']; ?>" readonly>
                    <p>Telefono</p>
                    <input type="text" name="Telefono" value="<?php echo $estudiante['TELEFONO']; ?>" readonly>
                    <p>EPS</p>
                    <input type="text" name="EPS" value="<?php echo $estudiante['EPS']; ?>" readonly>
                    <p>RH</p>
                    <input type="text" name="RH" value="<?php echo $estudiante['RH']; ?>" readonly>
                    <p>Direccion de residencia</p>
                    <input type="text" name="Direccion" value="<?php echo $estudiante['DIRECCION']; ?>" readonly>
            </div>
        </div>
        <div class="right-side">
            <div class="content">
                <ul>
                    <li><span>Ver Notas</span><a href=""><button class="circular-button">&#8594;</button></a></li>
                    <li><span>Ver Horario</span><a href=""><button class="circular-button">&#8594;</button></a></li>
                    <li><span>Ver Profesores</span><a href=""><button class="circular-button">&#8594;</button></a></li>
                    <li><span>Ver Materias</span><a href=""><button class="circular-button">&#8594;</button></a></li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>