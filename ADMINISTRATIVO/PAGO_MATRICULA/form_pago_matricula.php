<?php
include("conexion.php");

$idEstudiante = $_POST["idEstudiante"];
$monto = $_POST["monto"];
$fecha = $_POST["fecha"];
$metodoPago = $_POST["metodoPago"];
$tipoPago = $_POST["tipoPago"];

$sql2 = "SELECT COUNT(*) AS total FROM PAGO_MATRICULA";
$result2 = sqlsrv_query($conn,$sql2);
$row2 = sqlsrv_fetch($result2);
$total = sqlsrv_get_field($result2, 0); 

$nuevo_numero = $total + 1;
$IdPagoMatricula = "PM".str_pad($nuevo_numero, 10, "0", STR_PAD_LEFT);


//BUSCADOR ID ESTUDIANTE
$sql3 = "SELECT COUNT(*) AS total FROM ESTUDIANTE";
$result3 = sqlsrv_query($conn,$sql3);
$row3 = sqlsrv_fetch($result3);
//Obtener el valor numerico
$total2 = sqlsrv_get_field($result3, 0); 
// ID ESTUDIANTE
$IdEstduiante = "EST".$total2;

$fechaFormateada = date('Y-m-d H:i:s', strtotime($fecha));

$sql = "INSERT INTO PAGO_MATRICULA (ID_PAGO_MATRICULA, ID_ESTUDIANTE, MONTO_PAGADO, FECHA_PAGO, METODO_PAGO, TIPO_PAGO) VALUES ($IdPagoMatricula, $IdEstudiante, $monto, $fechaFormateada, $metodoPago, $tipoPago)";
$params = array($IdPagoMatricula, $IdEstudiante, $monto, $fechaFormateada, $metodoPago, $tipoPago);
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