<?php
include("conexion_acudiente.php");

$IdIngreso=$_POST["IdIngreso"];

$query="SELECT *FROM ACUDIENTE WHERE ID_ACUDIENTE = '$IdIngreso';";

$result = sqlsrv_query($conn, $query);

if ($result === false) {
    die(print_r(sqlsrv_errors(), true)); // Manejo de errores si la consulta falla
}

if ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    $IdAcu = $row["ID_ACUDIENTE"];
    $DocId = $row["DOCUMENTO_DE_IDENTIDAD"];
    $Nombre = $row["NOMBRE"];
    $Apellido = $row["APELLIDO"];
    $Genero = $row["GENERO"];
    $Correo = $row["CORREO"];
    $Telefono = $row["TELEFONO"];
    $Direccion = $row["DIRECCION"];
}
sqlsrv_close($conn);
?>
