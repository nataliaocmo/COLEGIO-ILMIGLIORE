<?php
include("conexion.php");

$id_acudiente = $_GET['id_acudiente'];
$id_estudiante = $_POST['id_estudiante'];
$cambio = $_POST["cambio"];
$nuevoDato = $_POST["dato"];
$nuevoGenero = $_POST["Genero"];

// Consulta para obtener los estudiantes relacionados con el acudiente
$query2 = "SELECT ID_ESTUDIANTE, CONCAT(NOMBRE, ' ', APELLIDO) AS NOMBRE_COMPLETO FROM ESTUDIANTE WHERE ID_ACUDIENTE ='$id_acudiente'";
$params = array($id_acudiente);
$result = sqlsrv_query($conn, $query2, $params);
// Verificar si se obtuvieron resultados
if ($result === false) {
    echo "Error en la consulta: " . sqlsrv_errors()[0]['message'];
    exit();
}
// Construir las opciones del select con los estudiantes obtenidos
$options = "";
while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    $options .= "<option value='" . $row['ID_ESTUDIANTE'] . "'>" . $row['NOMBRE_COMPLETO'] . "</option>";
}

switch ($cambio) {
  case "Nombre":
    $query = "UPDATE ESTUDIANTE SET NOMBRE='$nuevoDato' WHERE ID_ESTUDIANTE='$id_estudiante'";
    actualizar($conn,$query,$id_acudiente);
    break;
  case "Apellido":
    $query = "UPDATE ESTUDIANTE SET APELLIDO='$nuevoDato' WHERE ID_ESTUDIANTE='$id_estudiante'";
    actualizar($conn,$query,$id_acudiente);
    break;
  case "Genero":
    $query = "UPDATE ESTUDIANTE SET GENERO='$nuevoGenero' WHERE ID_ESTUDIANTE='$id_estudiante'";
    actualizar($conn,$query,$id_acudiente);
    break;
  case "Correo":
    $query = "UPDATE ESTUDIANTE SET CORREO='$nuevoDato' WHERE ID_ESTUDIANTE='$id_estudiante'";
    actualizar($conn,$query,$id_acudiente);
    break;
  case "Telefono":
    $query = "UPDATE ESTUDIANTE SET TELEFONO='$nuevoDato' WHERE ID_ESTUDIANTE='$id_estudiante'";
    actualizar($conn,$query,$id_acudiente);
    break;
  case "EPS":
    $query = "UPDATE ESTUDIANTE SET EPS='$nuevoDato' WHERE ID_ESTUDIANTE='$id_estudiante'";
    actualizar($conn,$query,$id_acudiente);
    break;
  case "Direccion":
    $query = "UPDATE ESTUDIANTE SET DIRECCION='$nuevoDato' WHERE ID_ESTUDIANTE='$id_estudiante'";
    actualizar($conn,$query,$id_acudiente);
    break;
  default:
    break;
}

function actualizar($conn, $query, $id_acudiente) {
  $res = sqlsrv_prepare($conn, $query);
  if (sqlsrv_execute($res)) {
      header("Location: /sql/ESTUDIANTE/actualizacionDeDatosEstudiante.php?id_acudiente=$id_acudiente");
      exit();
  } else {
      if (($errors = sqlsrv_errors()) != null) {
          foreach ($errors as $error) {
              echo "ERROR AL INGRESAR LOS DATOS: " . $error['message'];
          }
      }
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Actualizacion de datos Estudiante</title>
</head>
<body>
  <div class="formulario">
    <h2>Actualizacion de datos Estudiante</h2>
    <form id="Actualizacion" class="" method="post">
      <input type="hidden" name="id_acudiente" value="<?php echo $id_acudiente; ?>">
      <input type="hidden" name="id_estudiante" id="id_estudiante" value="">
      <p>Selecciona el estudiante al cual deseas actualizar los datos</p>
      <select id="est" name="est" onchange="actualizarIdEstudiante()">
        <option value=" "></option>
        <?php echo $options; ?>
      </select>
      <p>Dato a cambiar</p>
      <select id="cambio" name="cambio" onchange="mostrarOcultarCampo()">
        <option value=" "></option>
        <option value="Nombre">Nombre</option>
        <option value="Apellido">Apellido</option>
        <option value="Genero">Genero</option>
        <option value="Correo">Correo</option>
        <option value="Telefono">Telefono</option>
        <option value="EPS">EPS</option>
        <option value="Direccion">Direccion</option>
      </select>
      <input type="text" name="dato" required style="display: none;">
      <select id="Genero" name="Genero" style="display: none;">
        <option value=""></option>
        <option value="Femenino">Femenino</option>
        <option value="Masculino">Masculino</option>
        <option value="Otro">Otro</option>
      </select>
      <button type="submit">Actualizar</button>
    </form>
    <form id="login-form" action="http://localhost:8081/sql/procesar_login.php" method="post">
      <input type="hidden" name="rol" value="acudiente">
      <input type="hidden" name="IdIngreso" value="<?php echo $_GET['id_acudiente']; ?>">
      <button id="volver" type="submit">Volver</button>
    </form>
  </div>
  <script>
    function mostrarOcultarCampo() {
      var select = document.getElementById("cambio");
      var datoInput = document.querySelector('input[name="dato"]');
      var generoSelect = document.getElementById("Genero");

      if (select.value === "Genero") {
        datoInput.style.display = "none";
        generoSelect.style.display = "inline-block";
      } else {
        generoSelect.style.display = "none";
        datoInput.style.display = "inline-block";
      }
    }

    function actualizarIdEstudiante() {
      var estudianteSelect = document.getElementById("est");
      var idEstudianteInput = document.getElementById("id_estudiante");
      var selectedOption = estudianteSelect.options[estudianteSelect.selectedIndex];

      idEstudianteInput.value = selectedOption.value;
    }
  </script>
</body>
</html>