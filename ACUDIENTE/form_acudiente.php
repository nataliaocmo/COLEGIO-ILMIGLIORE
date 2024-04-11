<?php
include("conexion.php");
 
$IdAcu=$_POST["IdAcu"];
$DocId=$_POST["DocId"];
$Nombre=$_POST["Nombre"];
$Apellido=$_POST["Apellido"];
$Genero=$_POST["Genero"];
$Correo=$_POST["Correo"];
$Telefono=$_POST["Telefono"];
$Direccion=$_POST["Direccion"];

$IdAcudiente = 'ACU'.$IdAcu;
 
$query="INSERT INTO ACUDIENTE(ID_ACUDIENTE,DOCUMENTO_DE_IDENTIDAD,NOMBRE,APELLIDO,GENERO,CORREO,TELEFONO,DIRECCION) VALUES('$IdAcudiente','$DocId','$Nombre','$Apellido','$Genero','$Correo','$Telefono','$Direccion')";
 
$res=sqlsrv_prepare($conn,$query);
 
if (sqlsrv_execute($res)){
    echo "DATOS INGRESADOS";
}else{
    if( ($errors = sqlsrv_errors() ) != null) {
        foreach( $errors as $error ) {
            echo "ERROR AL INGRESAR LOS DATOS: " . $error['message'];
        }
    }
}
?>