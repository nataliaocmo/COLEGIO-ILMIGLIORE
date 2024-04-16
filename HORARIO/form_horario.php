<?php
include("conexion.php");

$idHorario = $_POST["idHorario"];
$dia = $_POST["dia"];
$horaInicio = $_POST["horaInicio"];
$horaFin = $_POST["horaFin"];
$departamento = $_POST["departamento"];
$materia = $_POST["materia"];
$profesor = $_POST["profesor"];

$sql = "INSERT INTO HORARIO (ID_HORARIO, DIA, HORA_INICIO, HORA_FIN, DEPARTAMENTO, MATERIA, PROFESOR) VALUES (?, ?, ?, ?, ?, ?, ?)";
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
