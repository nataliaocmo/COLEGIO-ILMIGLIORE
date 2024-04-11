conexion.php
<?php
$serverName="LAPTOP-3H9G1TCV";
$connectionInfo=array("Database"=> "Proyecto", "UID"=>"administrador", "PWD"=>"root");
$conn=sqlserver_connect($serverName, $connectionInfo);

if($conn){
	echo "Bienvenido admin. <br />";

}else{	
	echo "Conexión no se pudo estaboecer.<br />";
	die(print_r(sqlsrv_errors(),true));
}
?>