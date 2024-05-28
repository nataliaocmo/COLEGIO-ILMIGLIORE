<?php
session_start();
include ('conexion.php');
// Verifica si el usuario está autenticado como estudiante
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== "profesor") {
    header("Location: /sql/log_in.html");
    exit();
}
// Accede al ID del estudiante desde la sesión
$estudiante = $_SESSION['estudiante'];
//$id_estudiante = $_SESSION['estudiante']['ID_ESTDUIANTE'];

date_default_timezone_set('America/New_York');
$fecha_actual = date("Y-m-d H:i:s");
$anio_actual = date("Y");

echo "Fecha actual: " . $fecha_actual . "<br>";
echo "Año actual: " . $anio_actual;
echo $anio_actual;

$query= "SELECT * FROM NOTA WHERE ANO ='$anio_actual'";
$result = sqlsrv_query($conn, $query);
echo $result;


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notas Académicas</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>Periodo Académico</th>
                <th>Primer Periodo</th>
                <th>Segundo Periodo</th>
                <th>Tercer Periodo</th>
                <th>Cuarto Periodo</th>
                <th>Fallas</th>
                <th>Nota Final</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . $row['ANO'] . "</td>";
                echo "<td>" . $row['NOTA_P1'] . "</td>";
                echo "<td>" . $row['NOTA_P2'] . "</td>";
                echo "<td>" . $row['NOTA_P3'] . "</td>";
                echo "<td>" . $row['NOTA_P4'] . "</td>";
                echo "<td>" . $row['NUMERO_DE_FALLAS'] . "</td>";
                echo "<td>" . $row['NOTA_FINAL'] . "</td>";
                echo "<td>" . $row['APROBO/NO_APROBO'] . "</td>";
                echo "</tr>";
            }
            sqlsrv_free_stmt($stmt);
            ?>
        </tbody>
    </table>
</body>
</html>