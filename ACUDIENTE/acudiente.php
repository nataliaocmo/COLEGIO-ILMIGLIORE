<?php
session_start();
// Verifica si el usuario está autenticado como acudiente
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== "acudiente") {
    header("Location: /sql/log_in.html");
    exit();
}
// Accede a los datos del acudiente desde la sesión
$acudiente = $_SESSION['acudiente'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acudiente</title>
    <link rel="stylesheet" href="acudiente.css">
</head>
<body>
    <div class="container">
        <div class="left-side">
            <div class="top-bar">
                <div class="icons">
                    <a href="/sql/log_in.html"><img src="/sql/IMG/inicio.png" alt="Home"></a>
                    <div class="red-bar">ACUDIENTE</div>
                </div>
            </div>
            <div class="formulario">
                <h2>Datos Personales</h2>
                <form class="datos" action="RolAcudiente.php" method="post">
                    <p>Documento de Identificación</p>
                    <input type="text" name="DocId" value="<?php echo $acudiente['DOCUMENTO_DE_IDENTIDAD']; ?>" readonly>
                    <p>Nombre</p>
                    <input type="text" name="Nombre" value="<?php echo $acudiente['NOMBRE']; ?>" readonly>
                    <p>Apellido</p>
                    <input type="text" name="Apellido" value="<?php echo $acudiente['APELLIDO']; ?>" readonly>
                    <p>Genero</p>
                    <input type="text" name="Genero" value="<?php echo $acudiente['GENERO']; ?>" readonly>
                    <p>Correo</p>
                    <input type="text" name="Correo" value="<?php echo $acudiente['CORREO']; ?>" readonly>
                    <p>Telefono</p>
                    <input type="text" name="Telefono" value="<?php echo $acudiente['TELEFONO']; ?>" readonly>
                    <p>Direccion</p>
                    <input type="text" name="Direccion" value="<?php echo $acudiente['DIRECCION']; ?>" readonly>
                </form>
            </div>
        </div>
        <div class="right-side">
            <div class="content">
                <ul>
                    <li><span>Actualizacion de Datos</span><a href="actualizacionDeDatosAcudiente.php?id_acudiente=<?php echo $acudiente['ID_ACUDIENTE']; ?>&contrasena=<?php echo $acudiente['CONTRASENA']; ?>"><button class="circular-button">&#8594;</button></a></li>
                    <li><span>Actualizar Datos Estudiante</span><a href="/sql/ESTUDIANTE/actualizacionDeDatosEstudiante.php?id_acudiente=<?php echo $acudiente['ID_ACUDIENTE']; ?>&contrasena=<?php echo $acudiente['CONTRASENA']; ?>"><button class="circular-button">&#8594;</button></a></li>
                    <li><span>Ver Notas Estudiante</span><button class="circular-button" onclick="location.href='/sql/PROFESOR/NOTA/form_notas.html'">&#8594;</button></li>
                    <li><span>Pagar Matricula</span><button class="circular-button" onclick="location.href='/sql/ADMINISTRATIVO/PAGO_MATRICULA/form_pago_matricula.html'">&#8594;</button></li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>