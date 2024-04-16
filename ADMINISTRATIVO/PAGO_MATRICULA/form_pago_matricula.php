<?php
include("conexion.php");

$idEstudiante = $_POST["idEstudiante"];
$monto = $_POST["monto"];
$fecha = $_POST["fecha"];
$metodoPago = $_POST["metodoPago"];
$tipoPago = $_POST["tipoPago"];

$sql = "SELECT COUNT(*) AS total FROM ID_PAGO_MATRICULA";
$result = sqlsrv_query($conn,$sql);
$row = sqlsrv_fetch($result);
//Obtener el valor numerico
$total = sqlsrv_get_field($result, 0); 

// Generar el nuevo ID
$nuevo_numero = $total + 1;
$IdPagoMatricula = "PM".$nuevo_numero;

$sql = "INSERT INTO PAGO_MATRICULA (ID_PAGO_MATRICULA, ID_ESTUDIANTE, MONTO_PAGADO, FECHA_PAGO, METODO_PAGO, TIPO_PAGO) VALUES ($IdPagoMatricula, $idEstudiante , $monto, $fecha, $metodoPago, $tipoPago)";
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
