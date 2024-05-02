<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Actualizacion de datos Acudiente</title>
</head>
<body>
  <div class="formulario">
    <h2>Actualizacion de datos Acudiente</h2>
    <form id="Actualizacion" class="" action="actualizacionDeDatosAcudiente.php" method="post" onsubmit="return validarFormulario()">
      <input type="hidden" name="id_acudiente" value="<?php echo $_GET['id_acudiente']; ?>">
      <p>Dato a cambiar</p>
      <select id="cambio" name="cambio" onchange="mostrarOcultarCampo()">
        <option value=" "></option>
        <option value="Nombre">Nombre</option>
        <option value="Apellido">Apellido</option>
        <option value="Genero">Genero</option>
        <option value="Correo">Correo</option>
        <option value="Telefono">Telefono</option>
        <option value="Direccion">Direccion</option>
      </select>
      <input type="text" name="dato" style="display: none;">
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
</script>
</body>
</html>

<?php
include("conexion.php");
$id_acudiente = $_POST['id_acudiente'];
$cambio = $_POST["cambio"];
$nuevoDato = $_POST["dato"];
$nuevoGenero = $_POST["Genero"];
switch ($cambio) {
  case "Nombre":
    $query = "UPDATE ACUDIENTE SET NOMBRE='$nuevoDato' WHERE ID_ACUDIENTE='$id_acudiente'";
    actualizar($conn, $query, $id_acudiente);
    break;
  case "Apellido":
    $query = "UPDATE ACUDIENTE SET APELLIDO='$nuevoDato' WHERE ID_ACUDIENTE='$id_acudiente'";
    actualizar($conn, $query, $id_acudiente);
    break;
  case "Genero":
    $query = "UPDATE ACUDIENTE SET GENERO='$nuevoGenero' WHERE ID_ACUDIENTE='$id_acudiente'";
    actualizar($conn, $query, $id_acudiente);
    break;
  case "Correo":
    $query = "UPDATE ACUDIENTE SET CORREO='$nuevoDato' WHERE ID_ACUDIENTE='$id_acudiente'";
    actualizar($conn, $query,$id_acudiente);
    break;
  case "Telefono":
    $query = "UPDATE ACUDIENTE SET TELEFONO='$nuevoDato' WHERE ID_ACUDIENTE='$id_acudiente'";
    actualizar($conn, $query, $id_acudiente);
    break;
  case "Direccion":
    $query = "UPDATE ACUDIENTE SET DIRECCION='$nuevoDato' WHERE ID_ACUDIENTE='$id_acudiente'";
    actualizar($conn, $query, $id_acudiente);
    break;
  default:
    break;
}
function actualizar($conn, $query, $id_acudiente) {
  $res = sqlsrv_prepare($conn, $query);
  if (sqlsrv_execute($res)) {
      echo $id_acudiente;
      header("Location: /sql/ACUDIENTE/actualizacionDeDatosAcudiente.php?id_acudiente=$id_acudiente");
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