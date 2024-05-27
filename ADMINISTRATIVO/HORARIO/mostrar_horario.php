<?php
session_start();
if (!isset($_SESSION['horario_generado'])) {
    header("Location: asignar_horario.php");
    exit();
}

$horarioGenerado = $_SESSION['horario_generado'];
include("conexion.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Horario Generado</title>
    <link rel="stylesheet" href="mostrar_horario.css">
</head>
<body>
    <div class="container">
        <h2>Horario Generado</h2>
        <table>
            <thead>
                <tr>
                    <th>Dia</th>
                    <th>Hora de Inicio</th>
                    <th>Hora de Fin</th>
                    <th>Asignatura</th>
                    <th>Profesor</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($horarioGenerado as $horario): ?>
                    <?php
                    $query = "SELECT HU.DIA, HU.HORA_DE_INICIO, HU.HORA_DE_FIN, A.NOMBRE AS ASIGNATURA, P.NOMBRE AS PROFESOR
                              FROM HORARIO H
                              JOIN HORARIO_UNICO HU ON H.ID_HORARIO_UNICO = HU.ID_HORARIO_UNICO
                              JOIN RELACION_PROFESOR_ASIGNATURA RPA ON H.ID_RELACION_PROFESOR_ASIGNATURA = RPA.ID_RELACION_PROFESOR_ASIGNATURA
                              JOIN ASIGNATURA A ON RPA.ID_ASIGNATURA = A.ID_ASIGNATURA
                              JOIN PROFESOR P ON RPA.ID_PROFESOR = P.ID_PROFESOR
                              WHERE H.ID_HORARIO = '{$horario['ID_HORARIO']}'";
                    $result = sqlsrv_query($conn, $query);
                    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
                    ?>
                    <tr>
                        <td><?php echo $row['DIA']; ?></td>
                        <td><?php echo $row['HORA_DE_INICIO']; ?></td>
                        <td><?php echo $row['HORA_DE_FIN']; ?></td>
                        <td><?php echo $row['ASIGNATURA']; ?></td>
                        <td><?php echo $row['PROFESOR']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button onclick="window.location.href='asignar_horario.php'">Volver</button>
    </div>
</body>
</html>