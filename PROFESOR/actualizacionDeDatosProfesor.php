<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$id_profesor = isset($_GET['id_profesor']) ? $_GET['id_profesor'] : null;
$contrasena = isset($_GET['contrasena']) ? $_GET['contrasena'] : null;

include("conexionProfesor.php");

// Capturar los valores del POST
$id_profesor_post = isset($_POST['id_profesor']) ? $_POST['id_profesor'] : $id_profesor;
$cambio = isset($_POST['cambio']) ? $_POST['cambio'] : null;
$nuevoDato = isset($_POST['dato']) ? $_POST['dato'] : null;
$nuevoGenero = isset($_POST['Genero']) ? $_POST['Genero'] : null;
$contrasena_post = isset($_POST['contrasena']) ? $_POST['contrasena'] : $contrasena;

// Ejecutar actualización si se ha enviado el formulario
if ($cambio) {
    switch ($cambio) {
      case "Nombre":
        $query = "UPDATE PROFESOR SET NOMBRE = ? WHERE ID_PROFESOR = ?";
        $params = array($nuevoDato, $id_profesor_post);
        actualizar($conn, $query, $params, $id_profesor_post, $contrasena_post, $cambio);
        break;
      case "Apellido":
        $query = "UPDATE PROFESOR SET APELLIDO = ? WHERE ID_PROFESOR = ?";
        $params = array($nuevoDato, $id_profesor_post);
        actualizar($conn, $query, $params, $id_profesor_post, $contrasena_post, $cambio);
        break;
      case "Genero":
        $query = "UPDATE PROFESOR SET GENERO = ? WHERE ID_PROFESOR = ?";
        $params = array($nuevoGenero, $id_profesor_post);
        actualizar($conn, $query, $params, $id_profesor_post, $contrasena_post, $cambio);
        break;
      case "Telefono":
        $query = "UPDATE PROFESOR SET TELEFONO = ? WHERE ID_PROFESOR = ?";
        $params = array($nuevoDato, $id_profesor_post);
        actualizar($conn, $query, $params, $id_profesor_post, $contrasena_post, $cambio);
        break;
      case "Direccion":
        $query = "UPDATE PROFESOR SET DIRECCION = ? WHERE ID_PROFESOR = ?";
        $params = array($nuevoDato, $id_profesor_post);
        actualizar($conn, $query, $params, $id_profesor_post, $contrasena_post, $cambio);
        break;
      case "Contrasena":
        $query = "UPDATE PROFESOR SET CONTRASENA = ? WHERE ID_PROFESOR = ?";
        $params = array($nuevoDato, $id_profesor_post);
        actualizar($conn, $query, $params, $id_profesor_post, $contrasena_post, $cambio);
        break;
      default:
        break;
    }
}

function actualizar($conn, $query, $params, $id_profesor, $contrasena, $cambio) {
  $res = sqlsrv_prepare($conn, $query, $params);
  if (sqlsrv_execute($res)) {
    if($cambio === "Contrasena"){
      header("Location: /sql/log_in.html");
      exit();
    }else{
      header("Location: /sql/PROFESOR/actualizacionDeDatosProfesor.php?id_profesor=$id_profesor&contrasena=$contrasena");
      exit();
    }
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
  <title>Actualización de datos Profesor</title>
</head>
<body>
  <div class="formulario">
    <h2>Actualización de datos Profesor</h2>
    <form id="Actualizacion" class="" method="post">
      <input type="hidden" name="id_profesor" value="<?php echo htmlspecialchars($id_profesor); ?>">
      <input type="hidden" name="contrasena" value="<?php echo htmlspecialchars($contrasena); ?>">
      <p>Dato a cambiar</p>
      <select id="cambio" name="cambio" onchange="mostrarOcultarCampo()">
        <option value=" "></option>
        <option value="Nombre">Nombre</option>
        <option value="Apellido">Apellido</option>
        <option value="Genero">Genero</option>
        <option value="Telefono">Telefono</option>
        <option value="Direccion">Direccion</option>
        <option value="Contrasena">Contraseña</option>
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
      <input type="hidden" name="rol" value="profesor">
      <input type="hidden" name="IdIngreso" value="<?php echo htmlspecialchars($id_profesor); ?>">
      <input type="hidden" name="contrasena" value="<?php echo htmlspecialchars($contrasena); ?>">
      <button id="volver" type="submit">Volver</button>
    </form>
    <div id="error-message" style="color: red;"></div>
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