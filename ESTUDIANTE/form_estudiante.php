<?php
include("conexion.php");

// $IdESTU = $_POST["IdEstu"];
$Nombre = $_POST['nombre'];
$Apellido = $_POST['apellido'];
$Genero = $_POST["genero"];
$Telefono = $_POST["telefono"];
$EPS = $_POST["eps"];
$RH = $_POST["rh"];
$Direccion = $_POST["direccion"];
$FechaDeContratacion = $_POST["fechaDeContratacion"];

$sql = "SELECT COUNT(*) AS total FROM ESTUDIANTE";
$result = sqlsrv_query($conn,$sql);
$row = sqlsrv_fetch($result);
//Obtener el valor numerico
$total = sqlsrv_get_field($result, 0); 


// Generar el nuevo ID
$nuevo_numero = $total + 1;
$IdProfesor = "PROF".$nuevo_numero;
$fechaFormateada = date('Y-m-d H:i:s', strtotime($FechaDeContratacion));

$CorreoInstitucional=$Nombre.$Apellido.'@ims.edu.co';

$query = "INSERT INTO ESTUDIANTE(ID_ESTUDIANTE,NOMBRE,APELLIDO,GENERO,TELEFONO,EPS,RH,DIRECCION,FECHA_DE_CONTRATACION,CORREO_INSTITUCIONAL,DOCUMENTO_IDENTIDAD) VALUES ('$IdEstudiante','$Nombre','$Apellido','$Genero','$Telefono','$EPS','$RH','$Direccion','$fechaFormateada','$CorreoInstitucional')";
echo $query;
$res = sqlsrv_prepare($conn, $query);

if (sqlsrv_execute($res)) {
    echo "DATOS INGRESADOS";
} else {
    if (($errors = sqlsrv_errors()) != null) {
        foreach ($errors as $error) {
            echo "ERROR AL INGRESAR LOS DATOS: " . $error['message'];
        }
    }
}
?>