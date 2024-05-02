<?php
include("conexion.php");

$dia = $_POST["dia"];
$horaInicio = $_POST["horaInicio"];
$horaFin = $_POST["horaFin"];
$departamento = $_POST["departamento"];
$materia = $_POST["materia"];
$profesor = $_POST["profesor"];

$sql = "SELECT COUNT(*) AS total FROM HORARIO";
$result = sqlsrv_query($conn,$sql);
$row = sqlsrv_fetch($result);
//Obtener el valor numerico
$total = sqlsrv_get_field($result, 0); 

// Generar el nuevo ID
$nuevo_numero = $total + 1;
$idHorario = "HOR".$nuevo_numero;

$sql = "INSERT INTO HORARIO (ID_HORARIO, DIA, HORA_INICIO, HORA_FIN, DEPARTAMENTO, MATERIA, PROFESOR) VALUES ($idHorario, $dia, $horaInicio, $horaFin , $departamento, $materia, $profesor)";
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