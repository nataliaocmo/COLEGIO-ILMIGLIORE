<?php
session_start();
if (!isset($_SESSION['horario_generado'])) {
    header("Location: form_horario.html");
    exit();
}

$horarioGenerado = $_SESSION['horario_generado'];
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
                    include("conexion.php");
                    $query = "SELECT HU.DIA, HU.HORA_DE_INICIO, HU.HORA_DE_FIN, A.NOMBRE AS ASIGNATURA, P.NOMBRE AS PROFESOR
                              FROM HORARIO H
                              JOIN HORARIO_UNICO HU ON H.ID_HORARIO_UNICO = HU.ID_HORARIO_UNICO
                              JOIN RELACION_PROFESOR_ASIGNATURA RPA ON H.ID_RELACION_PROFESOR_ASIGNATURA = RPA.ID_RELACION_PROFESOR_ASIGNATURA
                              JOIN ASIGNATURA A ON RPA.ID_ASIGNATURA = A.ID_ASIGNATURA
                              JOIN PROFESOR P ON RPA.ID_PROFESOR = P.ID_PROFESOR
                              WHERE H.ID_HORARIO = ?";
                    $params = array($horario['ID_HORARIO']);
                    $result = sqlsrv_query($conn, $query, $params);
                    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['DIA']); ?></td>
                        <td><?php echo htmlspecialchars($row['HORA_DE_INICIO']); ?></td>
                        <td><?php echo htmlspecialchars($row['HORA_DE_FIN']); ?></td>
                        <td><?php echo htmlspecialchars($row['ASIGNATURA']); ?></td>
                        <td><?php echo htmlspecialchars($row['PROFESOR']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="form_horario.html">Volver</a>
    </div>
</body>
</html>