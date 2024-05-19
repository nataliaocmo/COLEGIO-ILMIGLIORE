<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Selección de Nuevo Director de curso</title>
</head>
<body>
  <div class="formulario">
    <h2>Seleccionar Nuevo Director de Curso</h2>
    <form id="formulario" action="actualizar_grados.php" method="post">
      <select id="NuevoDirector" name="NuevoDirector">
        <option value=" "></option>
        <?php
          include("conexion.php");
          $sql = "SELECT CONCAT(NOMBRE, ' ', APELLIDO) AS NOMBRE_COMPLETO FROM PROFESOR WHERE ID_PROFESOR NOT IN (SELECT ID_DIRECTOR_DE_CURSO FROM GRADO)";
          $stmt = sqlsrv_query($conn, $sql);
          if ($stmt === false) {
              die(print_r(sqlsrv_errors(), true));
          }
          while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
              echo "<option value='" . $row['NOMBRE_COMPLETO'] . "'>" . $row['NOMBRE_COMPLETO'] . "</option>";
          }
          $gradoSeleccionado = strtoupper($_POST['grado']);
        ?>
      </select>
      <input type="hidden" name="grado" value="<?php echo $gradoSeleccionado; ?>">
      <button type="submit">Guardar Cambios</button>
    </form>
  </div>
</body>
</html>