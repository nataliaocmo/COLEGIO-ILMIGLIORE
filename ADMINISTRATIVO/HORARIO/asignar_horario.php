<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignar Horario</title>
</head>
<body>
    <div class="container">
        <h2>Asignar Horario</h2>
        <form action="generar_horario.php" method="post">
            <label for="id_grado">Seleccione el Grado:</label>
            <select id="id_grado" name="id_grado">
                <option value=""></option>
                <?php
                include("conexion.php");
                $query = "SELECT ID_GRADO, NOMBRE FROM GRADO WHERE ID_GRADO NOT IN (SELECT DISTINCT ID_GRADO FROM HORARIO)";
                $result = sqlsrv_query($conn, $query);
                while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                    echo "<option value='".$row['ID_GRADO']."'>".$row['NOMBRE']."</option>";
                }
                ?>
            </select>
            <button type="submit">Generar Horario</button>
        </form>
    </div>
</body>
</html>