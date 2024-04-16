<?php
include("conexion.php");

$grado = $_POST["grado"];
$institucion = $_POST["institucion"];
$año = $_POST["año"];

// Validación del año actual y hacia atrás
$currentYear = date("Y");
if (!is_numeric($año) || $año > $currentYear) {
    echo "Error: El año cursado debe ser un año válido no mayor al presente.";
    exit;
}

$sql = "SELECT COUNT(*) AS total FROM HISTORIAL_ACADEMICO";
$result = sqlsrv_query($conn, $sql);
$row = sqlsrv_fetch($result);
$total = sqlsrv_get_field($result, 0); 

$nuevo_numero = $total + 1;
$IdHistorial = "HIST".$nuevo_numero;

$query = "INSERT INTO HISTORIAL_ACADEMICO(ID_HISTORIAL, GRADO, INSTITUCION, AÑO) VALUES ('$IdHistorial', '$grado', '$institucion', '$año')";
echo $query;

$res = sqlsrv_prepare($conn, $query);

if (sqlsrv_execute($res)) {
    echo "Datos ingresados correctamente.";
} else {
    if (($errors = sqlsrv_errors()) != null) {
        foreach ($errors as $error) {
            echo "Error al ingresar los datos: " . $error['message'];
        }
    }
}
?>
