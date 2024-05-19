<?php
// Incluir el archivo de conexión a la base de datos
include("conexion.php");

// Verificar si se recibieron datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el ID del nuevo jefe de área y el área seleccionada
    $nuevoJefe = $_POST['NuevoJefe'];
    $areaSeleccionada = strtoupper($_POST['area']);

    //Encontrar el ID del Jefe Seleccionado
    $sql = "SELECT ID_PROFESOR FROM PROFESOR WHERE CONCAT(NOMBRE, ' ', APELLIDO) = ? AND AREA = ?";
    $params = array($nuevoJefe, $areaSeleccionada);
    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    // Obtener el ID_PROFESOR si la consulta devuelve resultados
    if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $nuevoJefeId = $row['ID_PROFESOR'];

        // Realizar la consulta para actualizar la base de datos
        $sqlUpdate = "UPDATE ASIGNATURA SET ID_JEFE_DE_AREA = ? WHERE AREA = ?";
        $paramsUpdate = array($nuevoJefeId, $areaSeleccionada);
        $stmtUpdate = sqlsrv_query($conn, $sqlUpdate, $paramsUpdate);

        // Verificar si la consulta se ejecutó correctamente
        if ($stmtUpdate === false) {
            die("Error al actualizar la base de datos: " . print_r(sqlsrv_errors(), true));
        } else {
            $sqlConsult = "UPDATE PROFESOR SET SALARIO = '3500000' WHERE ID_PROFESOR= ?";
            $paramsConsult = array($nuevoJefeId);
            $stmtConsult = sqlsrv_query($conn,$sqlConsult,$paramsConsult);
            echo "<script>alert('Actualización exitosa. Tu nuevo salario es de 3'500.000');</script>";
            echo "<script>window.location = 'form_asignatura.php';</script>";
        }
    } else {
        echo "No se encontró ningún profesor";
    }
} else {
    // Si no se reciben datos por POST, redirigir al formulario para evitar acceso directo a este script
    header("Location: form_asignatura.php");
    exit;
}
?>