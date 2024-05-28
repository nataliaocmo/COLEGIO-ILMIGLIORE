<?php
session_start();
include ('conexion.php');
// Verifica si el usuario está autenticado como estudiante
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== "profesor") {
    header("Location: /sql/log_in.html");
    exit();
}
// Accede al ID del estudiante desde la sesión
$profesor = $_SESSION['profesor'];
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
    <style>
       body{font-family: 'Arial', sans-serif;
        background-color: #042d3f;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;}
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            
            background-color: #af184b;
        }
        td[contenteditable="true"] {
            background-color: #fff;
        }
    </style>
</head>
<body>
<h1>Insertar Datos en la Base de Datos</h1>
    <form action="insertar_notas.php" method="POST">
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
            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td contenteditable='true'>" . $row['ANO'] . "</td>";
                echo "<td contenteditable='true'>" . $row['NOTA_P1'] . "</td>";
                echo "<td contenteditable='true'>". $row['NOTA_P2'] . "</td>";
                echo "<td contenteditable='true'>". $row['NOTA_P3'] . "</td>";
                echo "<td contenteditable='true'>" . $row['NOTA_P4'] . "</td>";
                echo "<td contenteditable='true'>". $row['NUMERO_DE_FALLAS'] . "</td>";
                echo "<td contenteditable='true'>". $row['NOTA_FINAL'] . "</td>";
                echo "<td contenteditable='true'>" . $row['APROBO/NO_APROBO'] . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
        </table>
        <br>
        <input type="submit" value="Insertar Datos">
    </form>
</body>
</html>