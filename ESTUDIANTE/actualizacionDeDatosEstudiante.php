<?php
// Recuperar parámetros de la URL
$id_acudiente = isset($_GET['id_acudiente']) ? $_GET['id_acudiente'] : '';
$contrasenaAcudiente = isset($_GET['contrasena']) ? $_GET['contrasena'] : '';

include("conexionEstudiante.php");

// Capturar los valores del POST
$id_acudiente_post = isset($_POST['id_acudiente']) ? $_POST['id_acudiente'] : $id_acudiente;
$id_estudiante = isset($_POST['id_estudiante']) ? $_POST['id_estudiante'] : null;
$contrasenaAcudiente_post = isset($_POST['contrasenaAcudiente']) ? $_POST['contrasenaAcudiente'] : $contrasenaAcudiente;
$cambio = isset($_POST['cambio']) ? $_POST['cambio'] : null;
$nuevoDato = isset($_POST['dato']) ? $_POST['dato'] : null;
$nuevoGenero = isset($_POST['Genero']) ? $_POST['Genero'] : null;

// Consulta para obtener los estudiantes relacionados con el acudiente
$query2 = "SELECT ID_ESTUDIANTE, CONCAT(NOMBRE, ' ', APELLIDO) AS NOMBRE_COMPLETO FROM ESTUDIANTE WHERE ID_ACUDIENTE = ?";
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

// Ejecutar actualización si se ha enviado el formulario
if ($cambio) {
    switch ($cambio) {
      case "Nombre":
        $query = "UPDATE ESTUDIANTE SET NOMBRE = ? WHERE ID_ESTUDIANTE = ?";
        $params = array($nuevoDato, $id_estudiante);
        actualizar($conn, $query, $params, $id_acudiente_post, $contrasenaAcudiente_post);
        break;
      case "Apellido":
        $query = "UPDATE ESTUDIANTE SET APELLIDO = ? WHERE ID_ESTUDIANTE = ?";
        $params = array($nuevoDato, $id_estudiante);
        actualizar($conn, $query, $params, $id_acudiente_post, $contrasenaAcudiente_post);
        break;
      case "Genero":
        $query = "UPDATE ESTUDIANTE SET GENERO = ? WHERE ID_ESTUDIANTE = ?";
        $params = array($nuevoGenero, $id_estudiante);
        actualizar($conn, $query, $params, $id_acudiente_post, $contrasenaAcudiente_post);
        break;
      case "Telefono":
        $query = "UPDATE ESTUDIANTE SET TELEFONO = ? WHERE ID_ESTUDIANTE = ?";
        $params = array($nuevoDato, $id_estudiante);
        actualizar($conn, $query, $params, $id_acudiente_post, $contrasenaAcudiente_post);
        break;
      case "EPS":
        $query = "UPDATE ESTUDIANTE SET EPS = ? WHERE ID_ESTUDIANTE = ?";
        $params = array($nuevoDato, $id_estudiante);
        actualizar($conn, $query, $params, $id_acudiente_post, $contrasenaAcudiente_post);
        break;
      case "Direccion":
        $query = "UPDATE ESTUDIANTE SET DIRECCION = ? WHERE ID_ESTUDIANTE = ?";
        $params = array($nuevoDato, $id_estudiante);
        actualizar($conn, $query, $params, $id_acudiente_post, $contrasenaAcudiente_post);
        break;
      default:
        break;
    }
}

function actualizar($conn, $query, $params, $id_acudiente, $contrasenaAcudiente) {
  $res = sqlsrv_prepare($conn, $query, $params);
  if (sqlsrv_execute($res)) {
      header("Location: /sql/ESTUDIANTE/actualizacionDeDatosEstudiante.php?id_acudiente=$id_acudiente&contrasena=$contrasenaAcudiente");
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
  <link rel="stylesheet" href="actualizacionDeDatosProfesor.css">
  <title>Actualización de datos Estudiante</title>
</head>
<body>
  <div class="formulario">
    <h2>Actualización de datos Estudiante</h2>
    <form id="Actualizacion" class="" method="post">
      <input type="hidden" name="id_acudiente" value="<?php echo htmlspecialchars($id_acudiente); ?>">
      <input type="hidden" name="contrasenaAcudiente" value="<?php echo htmlspecialchars($contrasenaAcudiente); ?>">
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
      <input type="hidden" name="IdIngreso" value="<?php echo htmlspecialchars($id_acudiente); ?>">
      <input type="hidden" name="contrasena" value="<?php echo htmlspecialchars($contrasenaAcudiente); ?>">
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