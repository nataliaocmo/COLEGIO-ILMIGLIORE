<?php
include("conexion.php");

$IdProf = $_POST["IdProf"];
$Nombre = $_POST["Nombre"];
$Apellido = $_POST["Apellido"];
$Genero = $_POST["Genero"];
$Telefono = $_POST["Telefono"];
$EPS = $_POST["EPS"];
$RH = $_POST["RH"];
$Direccion = $_POST["Direccion"];
$FechaDeContratacion = $_POST["FechaDeContratacion"];
$Salario = $_POST["Salario"];
$CorreoInstitucional = $_POST["CorreoInstitucional"];
$DocId = $_POST["DocId"];

$IdProfesor = 'PROF'.$IdProf;
$fechaFormateada = date('Y-m-d H:i:s', strtotime($FechaDeContratacion));

$query = "INSERT INTO PROFESOR(ID_PROFESOR,NOMBRE,APELLIDO,GENERO,TELEFONO,EPS,RH,DIRECCION,FECHA_DE_CONTRATACION,SALARIO,CORREO_INSTITUCIONAL,DOCUMENTO_DE_IDENTIDAD) VALUES ('$IdProfesor','$Nombre','$Apellido','$Genero','$Telefono','$EPS','$RH','$Direccion','$fechaFormateada','$Salario','$CorreoInstitucional','$DocId')";

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