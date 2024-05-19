<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Actualizacion de datos Profesor</title>
</head>
<body>
  <div class="formulario">
    <h2>Actualizacion de datos Profesor</h2>
    <form id="Actualizacion" class="" action="actualizacionDeDatosProfesor.php" method="post" onsubmit="return validarFormulario()">
      <input type="hidden" name="id_profesor" value="<?php echo $_GET['id_profesor']; ?>">
      <p>Dato a cambiar</p>
      <select id="cambio" name="cambio" onchange="mostrarOcultarCampo()">
        <option value=" "></option>
        <option value="Nombre">Nombre</option>
        <option value="Apellido">Apellido</option>
        <option value="Genero">Genero</option>
        <option value="Telefono">Telefono</option>
        <option value="Direccion">Direccion</option>
      </select>
      <input type="text" name="dato" required style="display: none;">
      <select id="Genero" name="Genero" style="display: none;">
        <option value=""></option>
        <option value="Femenino">Femenino</option>
        <option value="Masculino">Masculino</option>
        <option value="Otro">Otro</option>
      </select>
      <button type="submit">Actualizar Profesor</button>
    </form>
    <form id="login-form" action="http://localhost:8081/sql/procesar_login.php" method="post">
      <input type="hidden" name="rol" value="acudiente">
      <input type="hidden" name="IdIngreso" value="<?php echo $_GET['id_profesor']; ?>">
      <button id="volver" type="submit">Volver</button>
    </form>
    <div id="error-message" style="color: red;"></div>
    <div id="success-message" style="color: green; display: none;">¡Acudiente registrado correctamente!</div>
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
</script>
</body>
</html>

<?php
include("conexion.php");
$id_acudiente = $_POST['id_profesor'];
$cambio = $_POST["cambio"];
$nuevoDato = $_POST["dato"];
switch ($cambio) {
  case "Nombre":
    $query = "UPDATE PROFESOR SET NOMBRE='$nuevoDato' WHERE ID_PROFESOR='$id_profesor'";
    actualizar($conn, $query, $id_profesor);
    break;
  case "Apellido":
    $query = "UPDATE PROFESOR SET APELLIDO='$nuevoDato' WHERE ID_PROFESOR='$id_profesor'";
    actualizar($conn, $query, $id_profesor);
    break;
  case "Genero":
    $query = "UPDATE PROFESOR SET GENERO='$nuevoDato' WHERE ID_PROFESOR='$id_profesor'";
    actualizar($conn, $query, $id_profesor);
    break;
  case "Telefono":
    $query = "UPDATE PROFESOR SET TELEFONO='$nuevoDato' WHERE ID_PROFESOR='$id_profesor'";
    actualizar($conn, $query, $id_profesor);
    break;
  case "Direccion":
    $query = "UPDATE PROFESOR SET DIRECCION='$nuevoDato' WHERE ID_PROFESOR='$id_profesor'";
    actualizar($conn, $query, $id_profesor);
    break;
  default:
    break;
}
function actualizar($conn, $query, $id_profesor) {
  $res = sqlsrv_prepare($conn, $query);
  if (sqlsrv_execute($res)) {
      echo $id_profesor;
      header("Location: /sql/PROFESOR/actualizacionDeDatosProfesor.php?id_profesor=$id_profesor");
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