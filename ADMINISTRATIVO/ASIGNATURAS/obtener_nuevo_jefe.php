<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Selección de Nuevo Jefe de Área</title>
</head>
<body>
  <div class="formulario">
    <h2>Seleccionar Nuevo Jefe de Área</h2>
    <form id="formulario" action="actualizar_asignatura.php" method="post">
      <select id="NuevoJefe" name="NuevoJefe">
        <option value=" "></option>
        <?php
          include("conexion.php");
          // Obtener el área seleccionada
          $areaSeleccionada = strtoupper($_POST['area']);
          // Consulta para obtener los nombres completos de los profesores del área seleccionada
          $sql = "SELECT NOMBRE, APELLIDO FROM PROFESOR WHERE AREA = ?";
          $params = array($areaSeleccionada);
          $stmt = sqlsrv_query($conn, $sql, $params);
          if ($stmt === false) {
              die(print_r(sqlsrv_errors(), true));
          }
          // Imprimir opciones para el select
          while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
              echo "<option value='" . $row['NOMBRE'] . " " . $row['APELLIDO'] . "'>" . $row['NOMBRE'] . " " . $row['APELLIDO'] . "</option>";
          }
        ?>
      </select>
      <input type="hidden" name="area" value="<?php echo $areaSeleccionada; ?>">
      <button type="submit">Guardar Cambios</button>
    </form>
  </div>
</body>
</html>