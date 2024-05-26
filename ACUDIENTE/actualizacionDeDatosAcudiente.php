<?php
$id_acudiente = isset($_GET['id_acudiente']) ? $_GET['id_acudiente'] : null;
$contrasena = isset($_GET['contrasena']) ? $_GET['contrasena'] : null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Actualización de datos Acudiente</title>
  <link rel="stylesheet" type="text/css" href="actualizacionDeDatosAcudiente.css">
</head>
<body>
  <div class="formulario">
    <h2>Actualización de datos Acudiente</h2>
    <form id="Actualizacion" action="actualizacionDeDatosAcudiente.php" method="post">
      <input type="hidden" name="id_acudiente" value="<?php echo htmlspecialchars($_GET['id_acudiente']); ?>">
      <input type="hidden" name="contrasena" value="<?php echo htmlspecialchars($_GET['contrasena']); ?>">  
      <p>Dato a cambiar</p>
      <select id="cambio" name="cambio" onchange="mostrarOcultarCampo()">
        <option value=""></option>
        <option value="Nombre">Nombre</option>
        <option value="Apellido">Apellido</option>
        <option value="Genero">Genero</option>
        <option value="Correo">Correo</option>
        <option value="Telefono">Telefono</option>
        <option value="Direccion">Direccion</option>
        <option value="Contrasena">Direccion</option>
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
      <input type="hidden" name="IdIngreso" value="<?php echo htmlspecialchars($_GET['id_acudiente']); ?>">
      <input type="hidden" name="rol" value="acudiente">
      <input type="hidden" name="contrasena" value="<?php echo htmlspecialchars($_GET['contrasena']); ?>">
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
include("conexionAcudiente.php");

// Capturar los valores del POST
$id_acudiente = isset($_POST['id_acudiente']) ? $_POST['id_acudiente'] : null;
$cambio = isset($_POST['cambio']) ? $_POST['cambio'] : null;
$nuevoDato = isset($_POST['dato']) ? $_POST['dato'] : null;
$nuevoGenero = isset($_POST['Genero']) ? $_POST['Genero'] : null;
$contrasena = isset($_POST['contrasena']) ? $_POST['contrasena'] : null;

// Switch para actualizar la base de datos
switch ($cambio) {
  case "Nombre":
    $query = "UPDATE ACUDIENTE SET NOMBRE = ? WHERE ID_ACUDIENTE = ?";
    $params = array($nuevoDato, $id_acudiente);
    actualizar($conn, $query, $params, $id_acudiente, $contrasena, $cambio);
    break;
  case "Apellido":
    $query = "UPDATE ACUDIENTE SET APELLIDO = ? WHERE ID_ACUDIENTE = ?";
    $params = array($nuevoDato, $id_acudiente);
    actualizar($conn, $query, $params, $id_acudiente, $contrasena, $cambio);
    break;
  case "Genero":
    $query = "UPDATE ACUDIENTE SET GENERO = ? WHERE ID_ACUDIENTE = ?";
    $params = array($nuevoGenero, $id_acudiente);
    actualizar($conn, $query, $params, $id_acudiente, $contrasena, $cambio);
    break;
  case "Correo":
    $query = "UPDATE ACUDIENTE SET CORREO = ? WHERE ID_ACUDIENTE = ?";
    $params = array($nuevoDato, $id_acudiente);
    actualizar($conn, $query, $params, $id_acudiente, $contrasena, $cambio);
    break;
  case "Telefono":
    $query = "UPDATE ACUDIENTE SET TELEFONO = ? WHERE ID_ACUDIENTE = ?";
    $params = array($nuevoDato, $id_acudiente);
    actualizar($conn, $query, $params, $id_acudiente, $contrasena, $cambio);
    break;
  case "Direccion":
    $query = "UPDATE ACUDIENTE SET DIRECCION = ? WHERE ID_ACUDIENTE = ?";
    $params = array($nuevoDato, $id_acudiente);
    actualizar($conn, $query, $params, $id_acudiente, $contrasena, $cambio);
    break;
  case "Contrasena":
    $query = "UPDATE ACUDIENTE SET CONTRASENA = ? WHERE ID_ACUDIENTE = ?";
    $params = array($nuevoDato, $id_acudiente);
    actualizar($conn, $query, $params, $id_acudiente, $contrasena, $cambio);
    break;
}

// Función para actualizar la base de datos usando sqlsrv_query
function actualizar($conn, $query, $params, $id_acudiente, $contrasena, $cambio) {
  $res=sqlsrv_prepare($conn, $query, $params);
    if (sqlsrv_execute($res)){
      if($cambio === "Contrasena"){
        header("Location: /sql/log_in.html");
        exit();
      }else{
        header("Location: /sql/ACUDIENTE/actualizacionDeDatosAcudiente.php?id_acudiente=$id_acudiente&contrasena=$contrasena");
        exit();
      }
    }else{
      if( ($errors = sqlsrv_errors() ) != null) {
        foreach( $errors as $error ) {
          echo "ERROR AL INGRESAR LOS DATOS: " . $error['message'];
        }
      }
    }
}
?>