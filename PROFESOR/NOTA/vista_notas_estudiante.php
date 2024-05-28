<?php
include 'conexion.php';
session_start();

// Verifica si el usuario está autenticado como estudiante
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== "estudiante") {
    header("Location: /sql/log_in.html");
    exit();
}

// Accede al ID del estudiante desde la sesión
$estudiante = $_SESSION['estudiante'];

// Verifica si se ha enviado un grado desde el formulario
if (isset($_GET['grado'])) {
    $grado = htmlspecialchars($_GET['grado']);

    // Consulta para obtener las notas del estudiante por grado
    $sql = "SELECT n.*, rpa.ID_GRADO, rpa.ID_ASIGNATURA, rpa.ID_PROFESOR 
            FROM NOTA n
            INNER JOIN RELACION_NOTAS_ASIGNATURAS_ESTUDIANTES rnae ON n.ID_NOTA = rnae.ID_NOTAS
            INNER JOIN RELACION_PROFESOR_ASIGNATURA rpa ON rnae.ID_RELACION_PROFESORES_ASIGNATURA = rpa.ID_RELACION_PROFESOR_ASIGNATURA
            WHERE rnae.ID_ESTUDIANTE = ? AND rpa.ID_GRADO = ?";
    $params = array($estudiante['ID_ESTUDIANTE'], $grado);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
} else {
    echo "No se ha seleccionado ningún grado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notas Académicas</title>
</head>
<body>
    <h2>Notas del Estudiante</h2>
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" value="<?php echo $estudiante['NOMBRE'] . ' ' . $estudiante['APELLIDO']; ?>" readonly><br><br>

    <label for="year">Año Académico:</label>
    <select id="year" name="year">
        <option value="2022">2022</option>
        <option value="2023">2023</option>
        <option value="2024">2024</option>
    </select><br><br>

    <table border="1">
        <thead>
            <tr>
                <th>Periodo Académico</th>
                <th>Asignatura</th>
                <th>Docente</th>
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
                echo "<td>" . htmlspecialchars($row['ANO']) . "</td>";
                echo "<td>" . htmlspecialchars($row['ID_ASIGNATURA']) . "</td>";
                echo "<td>" . htmlspecialchars($row['ID_PROFESOR']) . "</td>";
                echo "<td>" . htmlspecialchars($row['NOTA_P1']) . "</td>";
                echo "<td>" . htmlspecialchars($row['NOTA_P2']) . "</td>";
                echo "<td>" . htmlspecialchars($row['NOTA_P3']) . "</td>";
                echo "<td>" . htmlspecialchars($row['NOTA_P4']) . "</td>";
                echo "<td>" . htmlspecialchars($row['NUMERO_DE_FALLAS']) . "</td>";
                echo "<td>" . htmlspecialchars($row['NOTA_FINAL']) . "</td>";
                echo "<td>" . htmlspecialchars($row['APROBO/NO_APROBO']) . "</td>";
                echo "</tr>";
            }

            sqlsrv_free_stmt($stmt);
            ?>
        </tbody>
    </table>
</body>
</html>
