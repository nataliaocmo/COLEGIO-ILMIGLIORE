<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Actualizacion de datos Administrativo</title>
</head>
<body>
  <div class="formulario">
    <h2>Actualizacion de datos Administrativo</h2>
    <form id="Actualizacion" class="" action="actualizacionDeDatosAdministrativo.php" method="post" onsubmit="return validarFormulario()">
      <input type="hidden" name="id_administrativo" value="<?php echo $_GET['id_administrativo']; ?>">
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
        <option value="Contrasena">Contraseña</option>
      </select>
      <input type="text" name="dato" style="display: none;">
      <select id="NuevoGenero" name="NuevoGenero" style="display: none;">
        <option value=""></option>
        <option value="Femenino">Femenino</option>
        <option value="Masculino">Masculino</option>
        <option value="Otro">Otro</option>
      </select>
      <button type="submit">Actualizar Administrativo</button>
    </form>
    <form id="login-form" action="http://localhost:8081/sql/procesar_login.php" method="post">
      <input type="hidden" name="rol" value="administrativo">
      <input type="hidden" name="IdIngreso" value="<?php echo $_GET['id_administrativo']; ?>">
      <button id="volver" type="submit">Volver</button>
    </form>
  </div>
  <script>
  function mostrarOcultarCampo() {
    var select = document.getElementById("cambio");
    var datoInput = document.querySelector('input[name="dato"]');
    var generoSelect = document.getElementById("NuevoGenero");

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
$id_administrativo = $_POST['id_administrativo'];
$cambio = $_POST["cambio"];
$nuevoDato = $_POST["dato"];
$nuevoGenero = $_POST["NuevoGenero"];

switch ($cambio) {
  case "Nombre":
    $query = "UPDATE ADMINISTRATIVO SET NOMBRE='$nuevoDato' WHERE ID_ADMINISTRATIVO='$id_administrativo'";
    actualizar($conn, $query, $id_administrativo);
    break;
  case "Apellido":
    $query = "UPDATE ADMINISTRATIVO SET APELLIDO='$nuevoDato' WHERE ID_ADMINISTRATIVO='$id_administrativo'";
    actualizar($conn, $query, $id_administrativo);
    break;
  case "Genero":
    $query = "UPDATE ADMINISTRATIVO SET GENERO='$nuevoGenero' WHERE ID_ADMINISTRATIVO='$id_administrativo'";
    actualizar($conn, $query, $id_administrativo);
    break;
  case "Correo":
    $query = "UPDATE ADMINISTRATIVO SET CORREO='$nuevoDato' WHERE ID_ADMINISTRATIVO='$id_administrativo'";
    actualizar($conn, $query,$id_administrativo);
    break;
  case "Telefono":
    $query = "UPDATE ADMINISTRATIVO SET TELEFONO='$nuevoDato' WHERE ID_ADMINISTRATIVO='$id_administrativo'";
    actualizar($conn, $query, $id_administrativo);
    break;
  case "EPS":
      $query = "UPDATE ADMINISTRATIVO SET EPS='$nuevoDato' WHERE ID_ADMINISTRATIVO='$id_administrativo'";
      actualizar($conn, $query, $id_administrativo);
      break;
  case "Direccion":
    $query = "UPDATE ADMINISTRATIVO SET DIRECCION='$nuevoDato' WHERE ID_ADMINISTRATIVO='$id_administrativo'";
    actualizar($conn, $query, $id_administrativo);
    break;
    case "Contrasena":
      $query = "UPDATE ADMINISTRATIVO SET CONTRASENA='$nuevoDato' WHERE ID_ADMINISTRATIVO='$id_administrativo'";
      actualizar($conn, $query, $id_administrativo);
      break;
  default:
    break;
}
function actualizar($conn, $query, $id_administrativo) {
  $res = sqlsrv_prepare($conn, $query);
  if (sqlsrv_execute($res)) {
      echo $id_administrativo;
      header("Location: /sql/ADMINISTRATIVO/actualizacionDeDatosAdministrativo.php?id_administrativo=$id_administrativo");
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