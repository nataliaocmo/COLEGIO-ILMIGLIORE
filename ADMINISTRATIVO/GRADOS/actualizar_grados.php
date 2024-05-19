<?php
// Incluir el archivo de conexión a la base de datos
include("conexion.php");

// Verificar si se recibieron datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el ID del nuevo jefe de área y el área seleccionada
    $nuevoDirector = $_POST['NuevoDirector'];
    $gradoSeleccionado = strtoupper($_POST['grado']);

    //Encontrar el ID del Jefe Seleccionado
    $sql = "SELECT ID_PROFESOR FROM PROFESOR WHERE CONCAT(NOMBRE, ' ', APELLIDO) = ?";
    $params = array($nuevoDirector);
    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    // Obtener el ID_PROFESOR si la consulta devuelve resultados
    if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $nuevoDirectorId = $row['ID_PROFESOR'];

        // Realizar la consulta para actualizar la base de datos
        $sqlUpdate = "UPDATE GRADO SET ID_DIRECTOR_DE_CURSO = ? WHERE NOMBRE = ?";
        $paramsUpdate = array($nuevoDirectorId, $gradoSeleccionado);
        $stmtUpdate = sqlsrv_query($conn, $sqlUpdate, $paramsUpdate);

        // Verificar si la consulta se ejecutó correctamente
        if ($stmtUpdate === false) {
            die("Error al actualizar la base de datos: " . print_r(sqlsrv_errors(), true));
        } else {
            echo "<script>alert('Actualización exitosa.');</script>";
            echo "<script>window.location = 'form_grados.html';</script>";
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