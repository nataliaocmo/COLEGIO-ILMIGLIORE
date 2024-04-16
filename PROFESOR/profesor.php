
<?php
session_start();

// Verifica si el usuario está autenticado como profesor
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== "profesor") {
    header("Location: /sql/log_in.html");
    exit();
}

// Accede a los datos del profesor desde la sesión
$profesor = $_SESSION['profesor'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profesor</title>
    <link rel="stylesheet" href="profesor.css">
</head>
<body>
    <div class="container">
        <div class="left-side">
            <div class="top-bar">
                <div class="icons">
                    <a href="/sql/log_in.html"><img src="/sql/IMG/inicio.png" alt="Home"></a>
                </div>
                <div class="red-bar">PROFESOR</div>
            </div>
            <div class="formulario">
                <h2>Datos Personales</h2>
                    <p>Documento de Identificación</p>
                    <input type="text" name="DocId" value="<?php echo $profesor['DOCUMENTO_DE_IDENTIDAD']; ?>" readonly>
                    <p>Nombres</p>
                    <input type="text" name="Nombre" value="<?php echo $profesor['NOMBRE']; ?>" readonly>
                    <p>Apellidos</p>
                    <input type="text" name="Apellido" value="<?php echo $profesor['APELLIDO']; ?>" readonly>
                    <p>Genero</p>
                    <input type="text" name="Genero" value="<?php echo $profesor['GENERO']; ?>" readonly>
                    <p>Teléfono</p>
                    <input type="text" name="Telefono" value="<?php echo $profesor['TELEFONO']; ?>" readonly>
                    <p>EPS</p>
                    <input type="text" name="EPS" value="<?php echo $profesor['EPS']; ?>" readonly>
                    <p>RH</p>
                    <input type="text" name="RH" value="<?php echo $profesor['RH']; ?>" readonly>
                    <p>Dirección de residencia</p>
                    <input type="text" name="Direccion" value="<?php echo $profesor['DIRECCION']; ?>" readonly>
                    <p>Fecha de Contratación</p>
                    <input type="text" name="FechaDeContratacion" value="<?php echo $profesor['FECHA_DE_CONTRATACION']; ?>" readonly>
                    <p>Salario</p>
                    <input type="text" name="Salario" value="<?php echo $profesor['SALARIO']; ?>" readonly>
            </div>
        </div>
        <div class="right-side">
            <div class="content">
                <ul>
                    <li><span>Ver Horario</span><a href=""><button class="circular-button">&#8594;</button></a></li>
                    <li><span>Ver Materias</span><a href=""><button class="circular-button">&#8594;</button></a></li>
                    <li><span>Ver Estudiantes</span><a href=""><button class="circular-button">&#8594;</button></a></li>
                    <li><span>Ver Pago Matricula</span><a href=""><button class="circular-button">&#8594;</button></a></li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>