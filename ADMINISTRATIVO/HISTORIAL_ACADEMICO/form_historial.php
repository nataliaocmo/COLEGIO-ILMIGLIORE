<?php
include("conexion.php");

$Grado = $_POST["grado"];
$Institucion = $_POST["institucion"];
$Ano = $_POST["ano"];

//ID HISTORIAL
$sql = "SELECT COUNT(*) AS total FROM HISTORIAL_ACADEMICO";
$result = sqlsrv_query($conn, $sql);
$row = sqlsrv_fetch($result);
$total = sqlsrv_get_field($result, 0); 
$nuevo_numero = $total + 1;
$IdHistorial = "HIST".str_pad($nuevo_numero, 10, "0", STR_PAD_LEFT);

//ID ESTUDIANTE
$sql2 = "SELECT COUNT(*) AS total FROM ESTUDIANTE";
$result2 = sqlsrv_query($conn, $sql2);
$row2 = sqlsrv_fetch($result2);
$total2 = sqlsrv_get_field($result2, 0); 
$IdEstudiante= "EST".str_pad($total2, 10, "0", STR_PAD_LEFT);

$query = "INSERT INTO HISTORIAL_ACADEMICO(ID_HISTORIAL_DE_GRADOS,ID_ESTUDIANTE,GRADO,INSTITUCION_EDUCATIVA,ANO) VALUES ('$IdHistorial','$IdEstudiante','$Grado','$Institucion','$Ano')";
$res = sqlsrv_prepare($conn, $query);
echo $query;
echo $res;
if (sqlsrv_execute($res)) {
    header("Location: /sql/ADMINISTRATIVO/HISTORIAL_ACADEMICO/form_historial.html");
    exit();
} else {
    if (($errors = sqlsrv_errors()) != null) {
        foreach ($errors as $error) {
            echo "Error al ingresar los datos: " . $error['message'];
        }
    }
}
?>