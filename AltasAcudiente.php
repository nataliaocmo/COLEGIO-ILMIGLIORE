<?php
include("conexion.php");
 
$DocId=$_POST["DocId"];
$Nombre=$_POST["Nombre"];
$Apellido=$_POST["Apellido"];
$Genero=$_POST["Genero"];
$Correo=$_POST["Correo"];
$Telefono=$_POST["Teléfono"];
$Direccion=$_POST["Direccion"];
 
$query="INSERT INTO ACUDIENTE(DOCUMENTO_DE_IDENTIDAD,NOMBRE,APELLIDO,GENERO,CORREO,TELEFONO,DIRECCIÓN)VALUES('$DocId','$Nombre',
'$Apellido','$Genero','$Correo','$Telefono','$Direccion')";
 
$res=sqlsrv_prepare($conn,$query);
 
if (sqlsrv_execute($res)){
 
echo"DATOS INGRESADOS";
 
}else{
 
echo"ERROR AL INGRESAR LOS DATOS";
 
}
 
?>