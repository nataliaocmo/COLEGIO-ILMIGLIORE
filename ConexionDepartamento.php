<?php
include("conexion.php");
 
$IdDep=$_POST["IdDepartamento"];
$Nombre=$_POST["Nombre"];

 
$query="INSERT INTO DEPARTAMENTO(ID_DEPARTAMENTO,NOMBRE)
VALUES('$IdDep','$Nombre')";
 
$res=sqlsrv_prepare($conn,$query);
 
if (sqlsrv_execute($res)){
 
echo"DATOS INGRESADOS";
 
}else{
 
echo"ERROR AL INGRESAR LOS DATOS";
 
}
 
?>