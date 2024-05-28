<?php
include("conexion.php");

// Consulta para obtener grados que no tienen horario asignado
$query = "SELECT ID_GRADO, NOMBRE FROM GRADO WHERE ID_GRADO NOT IN (SELECT DISTINCT ID_GRADO FROM HORARIO)";
$result = sqlsrv_query($conn, $query);
$grados = [];
while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    $grados[] = $row;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Asignar Horario</title>
</head>
<body>
  <h1>Asignar Horario</h1>
  <form action="generar_horario.php" method="post">
    <label for="grado">Seleccione un grado:</label>
    <select name="grado" id="grado">
      <?php foreach ($grados as $grado): ?>
        <option value="<?= $grado['ID_GRADO'] ?>"><?= $grado['NOMBRE'] ?></option>
      <?php endforeach; ?>
    </select>
    <button type="submit">Generar Horario</button>
  </form>
</body>
</html>