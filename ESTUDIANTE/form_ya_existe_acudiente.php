<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Formulario de Registro de Estudiante</title>
  <link rel="stylesheet" href="form_estudiante.css">
  <style> 
  body {

font-family: 'Arial', sans-serif;
background-color: #042d3f;
display: flex;
justify-content: center;
align-items: center;
height: 100vh;
margin: 0;
}

.formulario {
width: 100%;
margin: 10px;
margin-top: 300px;
max-width: 400px;
background: #fff;
padding: 20px;
border-radius: 10px;
box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.formulario h2 {
padding: 8%;
text-align: center;
margin-bottom: 10px;
color: #333;
}

.formulario input,
.formulario button {
width: 100%;
padding: 15px;
margin-bottom: 10px;
border-radius: 5px;
border: 1px solid #ddd;
box-sizing: border-box;
}

.formulario button {
background-color: #af184b;
color: white;
padding: 20px;
border: none;
cursor: pointer;
transition: background-color 0.3s ease;
}

.formulario button:hover {
background-color: #7e6060;
}

.formulario select {
width: 100%;
padding: 15px;
margin-bottom: 10px;
border-radius: 5px;
border: 1px solid #ddd;
box-sizing: border-box;
background-color: #fff;
/* Agrega este estilo para que los selectores tengan un fondo blanco */
color: #333;
/* Agrega este estilo para que el texto dentro de los selectores sea negro */
}

/* Estilos adicionales para cuando se pasa el ratón sobre los selectores */
.formulario select:hover {
border-color: #af184b;
/* Cambia el color del borde al pasar el ratón sobre los selectores */
}

/* Estilos adicionales para los selectores cuando están enfocados */
.formulario select:focus {
outline: none;
/* Elimina el contorno alrededor del selector cuando está enfocado */
border-color: #af184b;
/* Cambia el color del borde cuando el selector está enfocado */
}
</style>
</head>
<body>
  <div class="formulario">
    <h2>Registro de Estudiante</h2>
    <form id="formulario" class="" action="form_estudiante.php" method="post" onsubmit="return validarFormulario()">
        <input type="text" name="AcuDocId" placeholder="Documento de Identidad Acudiente" required>  
        <input type="text" name="docId" placeholder="Documento de Identidad" required>
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="text" name="apellido" placeholder="Apellido" required>
        <select id="genero" name="genero">
        <option value=" "></option>
        <option value="Femenino">Femenino</option>
        <option value="Masculino">Masculino</option>
        <option value="Otro">Otro</option>
      </select>
      <input type="date" name="fechaDeNacimiento" placeholder="Fecha de Nacimiento" required>
      <select id="grado" name="grado">
        <option value=" " disabled selected>Grado</option>
        <option value="PREESCOLAR">PREESCOLAR</option>
        <option value="PRIMERO">PRIMERO</option>
        <option value="SEGUNDA">SEGUNDO</option>
        <option value="TERCERO">TERCERO</option>
        <option value="CUARTO">CUARTO</option>
        <option value="QUINTO">QUINTO</option>
        <option value="SEXTO">SEXTO</option>
        <option value="SEPTIMO">SEPTIMO</option>
        <option value="OCTAVO">OCTAVO</option>
        <option value="NOVENO">NOVENO</option>
        <option value="DECIMO">DECIMO</option>
        <option value="UNDECIMO">UNDECIMO</option>
      </select>
      <input type="text" name="telefono" placeholder="Telefono" required>
      <input type="text" name="eps" placeholder="EPS" required>
      <select id="rh" name="rh">
        <option value=" "></option>
        <option value="O+">O+</option>
        <option value="O-">O-</option>
        <option value="A+">A+</option>
        <option value="A-">A-</option>
        <option value="AB+">AB-</option>
        <option value="AB-">AB-</option>
      </select>
      <input type="text" name="direccion" placeholder="Direccion" required>
      <button type="submit">Guardar Estudiante</button>
    </form>
    <div id="error-message" style="color: red;"></div>
    <div id="success-message" style="color: green; display: none;">¡Estudiante registrado correctamente!</div>
  </div>
  <script>
    function validarFormulario() {
      var docId = document.getElementsByName("DocId")[0].value.trim();
      var nombre = document.getElementsByName("Nombre")[0].value.trim();
      var apellido = document.getElementsByName("Apellido")[0].value.trim();
      var fechaContratacion = document.getElementsByName("FechaDeContratacion")[0].value.trim();
      var telefono = document.getElementsByName("Telefono")[0].value.trim();
      var eps = document.getElementsByName("EPS")[0].value.trim();
      var direccion = document.getElementsByName("Direccion")[0].value.trim();
      var errorMessage = "";

      // Validaciones
      if (!/^\d+$/.test(docId)) {
        errorMessage += "El campo Documento de Identidad solo debe contener números.<br>";
      }
      if (!nombre.trim()) {
        errorMessage += "El campo Nombre es obligatorio.<br>";
      }
      if (!apellido.trim()) {
        errorMessage += "El campo Apellido es obligatorio.<br>";
      }
      if (isNaN(telefono)) {
        errorMessage += "El campo Teléfono solo debe contener números.<br>";
      }
      if (!direccion.trim()) {
        errorMessage += "El campo Dirección es obligatorio.<br>";
      }
      if (new Date(fechaDeNacimiento) > new Date()) {
                errorMessage += "La Fecha de Contratación no puede ser una fecha futura.<br>";
      }
      if (!eps.trim()) {
      errorMessage += "El campo EPS es obligatorio.<br>";
      }
      if (errorMessage !== "") {
        document.getElementById("error-message").innerHTML = errorMessage;
        document.getElementById("success-message").style.display = "none"; // Ocultar mensaje de éxito
        return false; // Detener el envío del formulario
      }else {document.getElementById("success-message").style.display = "block";
      document.getElementById("error-message").innerHTML = ""; // Limpiar mensajes de error
      document.getElementById("formulario").reset(); // Limpiar formulario
      return true; // Permitir el envío del formulario si no hay errores
    }
    }
  </script>
</body>
</html>

<?php
include("conexion.php");
$AcuDocId=$_POST["AcuDocId"];

//REVISION DEL DOCUMENTO DE IDENTIDAD DEL ACUDIENTE
$query2="SELECT FROM ACUDIENTE WHERE DOCUMENTO_DE_IDENTIDAD= '$AcuDocId'";
// Preparar la consulta
$stmt = sqlsrv_prepare($conn, $query, array(&$AcuDocId));
// Ejecutar la consulta
if (sqlsrv_execute($stmt)) {
    // Obtener el resultado de la consulta
    if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $id_acudiente = $row['ID_ACUDIENTE'];
        // Utiliza el valor de $id_acudiente según lo necesites
        echo "El ID del acudiente correspondiente al documento de identidad $AcuDocId es: $id_acudiente";
    } else {
        echo "No se encontró ningún acudiente con el documento de identidad $AcuDocId";
    }
} else {
    echo "Error al ejecutar la consulta: " . sqlsrv_errors()[0]['message'];
}
?>