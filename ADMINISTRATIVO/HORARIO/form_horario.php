<?php
include("conexion.php");

$Grado = $_POST["grado"];

//Generar ID
$sql = "SELECT COUNT(*) AS total FROM HORARIO";
$result = sqlsrv_query($conn,$sql);
$row = sqlsrv_fetch($result);
$total = sqlsrv_get_field($result, 0); 
$nuevo_numero = $total + 1;
$idHorario = "HOR".str_pad($nuevo_numero, 10, "0", STR_PAD_LEFT);


$sql = "INSERT INTO HORARIO (ID_HORARIO) VALUES ($idHorario)";
$params = array($idHorario, $dia, $horaInicio, $horaFin, $departamento, $materia, $profesor);
$res = sqlsrv_query($conn, $sql, $params);

if ($res === false) {
    if (($errors = sqlsrv_errors()) != null) {
        foreach ($errors as $error) {
            echo "Error al ingresar los datos: " . $error['message'];
        }
    }
} else {
    echo "Datos ingresados correctamente.";
}

?>