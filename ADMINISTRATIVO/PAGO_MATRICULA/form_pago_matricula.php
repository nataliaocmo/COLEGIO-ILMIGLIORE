<?php
include("conexion.php");

$idPagoMatricula = $_POST["idPagoMatricula"];
$idEstudiante = $_POST["idEstudiante"];
$monto = $_POST["monto"];
$fecha = $_POST["fecha"];
$metodoPago = $_POST["metodoPago"];
$tipoPago = $_POST["tipoPago"];

$sql = "INSERT INTO PAGO_MATRICULA (ID_PAGO_MATRICULA, ID_ESTUDIANTE, MONTO_PAGADO, FECHA_PAGO, METODO_PAGO, TIPO_PAGO) VALUES (?, ?, ?, ?, ?, ?)";
$params = array($idPagoMatricula, $idEstudiante, $monto, $fecha, $metodoPago, $tipoPago);
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
