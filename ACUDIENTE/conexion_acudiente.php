<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$serverName="DESKTOP-07UTCOR";
$connectionInfo=array("Database"=>"Proyecto","UID"=>"Danna","PWD"=>"root");
$conn=sqlsrv_connect($serverName,$connectionInfo);

if($conn){
    echo "Conexion establecida.<br/>";
}else{
    echo"Conexion no se pudo establecer.<br/>";
    die(print_r(sqlsrv_errors(),true));
}
?>