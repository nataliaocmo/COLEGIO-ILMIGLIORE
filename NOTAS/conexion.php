<?php
$serverName="DESKTOP-07UTCOR";
$connectionInfo=array("Database"=>"Proyecto","UID"=>"Profesor","PWD"=>"root");
$conn=sqlsrv_connect($serverName,$connectionInfo);

if($conn){
	echo "Conexion establecida.<br/>";
}else{
	echo"Conexion no se pudo establecer.<br/>";
	die(print_r(sqlsrv_erRors(),true));
}
?>