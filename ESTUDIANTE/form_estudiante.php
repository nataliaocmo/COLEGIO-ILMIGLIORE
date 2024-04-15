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
$Fecha_de_nacimiento = $_POST["fechadenacimiento"];

$sql = "SELECT COUNT(*) AS total FROM ESTUDIANTE";
$result = sqlsrv_query($conn,$sql);
$row = sqlsrv_fetch($result);
//Obtener el valor numerico
$total = sqlsrv_get_field($result, 0); 


// Generar el nuevo ID
$nuevo_numero = $total + 1;
$IdEstudiante = "ESTU".$nuevo_numero;
$fechaFormateada = date('Y-m-d H:i:s', strtotime($Fecha_de_nacimiento));

$CorreoInstitucional=$Nombre.$Apellido.'@ims.edu.co';

$query = "INSERT INTO ESTUDIANTE(ID_ESTUDIANTE,DOCUMENTO_IDENTIDAD,NOMBRE,APELLIDO,GENERO,FECHA_DE_NACIMIENTO,CORREO_INSTITUCIONAL,TELEFONO,EPS,RH,DIRECCION) VALUES ('$IdEstudiante','$CorreoInstitucional','$Nombre','$Apellido','$Genero','$fechaFormateada','$Telefono','$EPS','$RH','$Direccion')";
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