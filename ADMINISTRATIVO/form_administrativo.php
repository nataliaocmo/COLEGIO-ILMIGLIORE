<?php
include("conexion.php");

// Función para obtener el siguiente ID para la tabla HORARIO
function obtenerNuevoIdHorario($conn) {
    $sql = "SELECT COUNT(*) AS total FROM HORARIO";
    $result = sqlsrv_query($conn, $sql);
    $row = sqlsrv_fetch_array($result);
    $total = $row['total'];
    $nuevo_numero = $total + 1;
    return "HOR" . str_pad($nuevo_numero, 4, "0", STR_PAD_LEFT);
}

function obtenerRelacionProfesorAsignatura($conn, $idAsignatura) {
    $query = "SELECT ID_RELACION_PROFESOR_ASIGNATURA FROM RELACION_PROFESOR_ASIGNATURA WHERE ID_ASIGNATURA = ?";
    $params = array($idAsignatura);
    $result = sqlsrv_query($conn, $query, $params);
    $row = sqlsrv_fetch_array($result);
    return $row['ID_RELACION_PROFESOR_ASIGNATURA'];
}

function verificarConflictos($conn, $idGrado, $idRelacionProfesorAsignatura, $idHorarioUnico) {
    // Verificar conflicto de horario para el grado
    $query = "SELECT COUNT(*) AS total FROM HORARIO WHERE ID_GRADO = ? AND ID_HORARIO_UNICO = ?";
    $params = array($idGrado, $idHorarioUnico);
    $result = sqlsrv_query($conn, $query, $params);
    $row = sqlsrv_fetch_array($result);
    if ($row['total'] > 0) {
        return true; // Conflicto encontrado
    }

    // Verificar conflicto de horario para el profesor
    $query = "SELECT COUNT(*) AS total FROM HORARIO WHERE ID_RELACION_PROFESOR_ASIGNATURA = ? AND ID_HORARIO_UNICO = ?";
    $params = array($idRelacionProfesorAsignatura, $idHorarioUnico);
    $result = sqlsrv_query($conn, $query, $params);
    $row = sqlsrv_fetch_array($result);
    if ($row['total'] > 0) {
        return true; // Conflicto encontrado
    }

    return false; // No hay conflicto
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $gradoSeleccionado = $_POST['gradoSeleccionado'];

    // Obtener asignaturas según la escolaridad del grado
    $query = "SELECT ESCOLARIDAD FROM GRADO WHERE ID_GRADO = ?";
    $params = array($gradoSeleccionado);
    $result = sqlsrv_query($conn, $query, $params);
    $row = sqlsrv_fetch_array($result);
    $escolaridad = $row['ESCOLARIDAD'];

    $query = "SELECT * FROM ASIGNATURA WHERE ESCOLARIDAD = ?";
    $params = array($escolaridad);
    $result = sqlsrv_query($conn, $query, $params);

    $asignaturas = array();
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $asignaturas[] = $row;
    }

    // Obtener horarios únicos
    $query = "SELECT * FROM HORARIO_UNICO";
    $result = sqlsrv_query($conn, $query);

    $horariosUnicos = array();
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $horariosUnicos[] = $row;
    }

    // Generar horario automático
    foreach ($asignaturas as $asignatura) {
        $intensidad = $asignatura['INTENSIDAD_HORARIO'];
        $idRelacionProfesorAsignatura = obtenerRelacionProfesorAsignatura($conn, $asignatura['ID_ASIGNATURA']);
        foreach ($horariosUnicos as $horarioUnico) {
            if ($intensidad <= 0) {
                break;
            }
            if (!verificarConflictos($conn, $gradoSeleccionado, $idRelacionProfesorAsignatura, $horarioUnico['ID_HORARIO_UNICO'])) {
                $idHorario = obtenerNuevoIdHorario($conn);
                $query = "INSERT INTO HORARIO (ID_HORARIO, ID_RELACION_PROFESOR_ASIGNATURA, ID_GRADO, ID_HORARIO_UNICO) 
                          VALUES (?, ?, ?, ?)";
                $params = array($idHorario, $idRelacionProfesorAsignatura, $gradoSeleccionado, $horarioUnico['ID_HORARIO_UNICO']);
                sqlsrv_query($conn, $query, $params);
                $intensidad--;
            }
        }
    }

    echo "Horario generado exitosamente para el grado " . $gradoSeleccionado;
}
?>