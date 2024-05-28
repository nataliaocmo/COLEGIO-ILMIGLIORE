<?php
session_start();

// Verifica si el usuario está autenticado como acudiente
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== "administrativo") {
    header("Location: /sql/log_in.html");
    exit();
}

$administrativo = $_SESSION['administrativo'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrativo</title>
    <link rel="stylesheet" href="administrativo.css">
</head>
<body>
    <div class="container">
        <div class="left-side">
            <div class="top-bar">
                <div class="icons">
                    <a href="/sql/Principal_IMS.html"><img src="/sql/IMG/inicio.png" alt="Home"></a>
                </div>
                <br>
                <div class="red-bar">ADMINISTRATIVO<button class="circular-button">&#8594;</button></div>
                <div class="formulario">
                <h2>Datos Personales</h2>
                    <p>Documento de Identificación</p>
                    <input type="text" name="DocId" value="<?php echo $administrativo['DOCUMENTO_DE_IDENTIDAD']; ?>" readonly>
                    <p>Nombres</p>
                    <input type="text" name="Nombre" value="<?php echo $administrativo['NOMBRE']; ?>" readonly>
                    <p>Apellidos</p>
                    <input type="text" name="Apellido" value="<?php echo $administrativo['APELLIDO']; ?>" readonly>
                    <p>Genero</p>
                    <input type="text" name="Genero" value="<?php echo $administrativo['GENERO']; ?>" readonly>
                    <p>Cargo</p>
                    <input type="text" name="Cargo" value="<?php echo $administrativo['CARGO']; ?>" readonly>
                    <p>Correo Institucional</p>
                    <input type="text" name="Correo" value="<?php echo $administrativo['CORREO_INSTITUCIONAL']; ?>" readonly>
                    <p>Telefono</p>
                    <input type="text" name="Telefono" value="<?php echo $administrativo['TELEFONO']; ?>" readonly>
                    <p>EPS</p>
                    <input type="text" name="EPS" value="<?php echo $administrativo['EPS']; ?>" readonly>
                    <p>RH</p>
                    <input type="text" name="RH" value="<?php echo $administrativo['RH']; ?>" readonly>
                    <p>Direccion de residencia</p>
                    <input type="text" name="Direccion" value="<?php echo $administrativo['DIRECCION']; ?>" readonly>
                    <p>Contraseña</p>
                    <input type="text" name="Contraseña" value="<?php echo $administrativo['CONTRASENA']; ?>" readonly>
                </form>
            </div>
            </div>
        </div>
        <div class="right-side">
            <div class="content">
                <ul>
                    <li><span>Actualizacion de Datos</span><a href="actualizacionDeDatosAdministrativo.php?id_administrativo=<?php echo $administrativo['ID_ADMINISTRATIVO']; ?>&contrasena=<?php echo $administrativo['CONTRASENA']; ?>"><button class="circular-button">&#8594;</button></a></li>
                    <li><span>Admisiones</span><a href="/sql/ACUDIENTE/form_acudiente.html"><button class="circular-button">&#8594;</button></a></li>
                    <li><span>Gestión de Grados</span><a href="/sql/ADMINISTRATIVO/GRADOS/form_grados.html"><button class="circular-button">&#8594;</button></a></li>
                    <li><span>Gestión de Horarios</span><a href="HORARIO/gestion_horarios.html"><button class="circular-button">&#8594;</button></a></li>
                    <li><span>Gestion Asignaturas</span><a href="ASIGNATURAS/form_asignatura.html"><button class="circular-button">&#8594;</button></a></li>   
                    <li><span>Contrataciones Administrativos</span><a href="/sql/ADMINISTRATIVO/form_administrativo.html"><button class="circular-button">&#8594;</button></a></li>
                    <li><span>Contrataciones Profesores</span><a href="/sql/PROFESOR/form_profesor.html"><button class="circular-button">&#8594;</button></a></li>
                    <li>
                        <span>Transferencias externas</span>
                        <a href="/sql/ADMINISTRATIVO/CARGUE_MASIVO/cargas_acudientes.php?id_administrativo=<?php echo $administrativo['ID_ADMINISTRATIVO']; ?>&contrasena=<?php echo $administrativo['CONTRASENA']; ?>" onclick="return confirmarCargaAcudientes();"><button class="circular-button">&#8594;</button></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</body>
<script>
function confirmarCargaAcudientes() {
    alert("Para realizar una transferencia externa de estudiantes, primero debemos agregar a sus acudientes.");
    return true; // permite la navegación después de la alerta
}
</script>
</html>