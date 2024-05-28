<?php
include("conexion.php");

function verificarConflicto($conn, $id_grado, $id_horario_unico, $id_profesor) {
    // Verificar conflictos de horarios para el grado
    $queryGrado = "SELECT COUNT(*) as total FROM HORARIO WHERE ID_GRADO = ? AND ID_HORARIO_UNICO = ?";
    $paramsGrado = array($id_grado, $id_horario_unico);
    $resultGrado = sqlsrv_query($conn, $queryGrado, $paramsGrado);
    $rowGrado = sqlsrv_fetch_array($resultGrado, SQLSRV_FETCH_ASSOC);

    if ($rowGrado['total'] > 0) {
        return true;
    }

    // Verificar conflictos de horarios para el profesor
    $queryProfesor = "SELECT COUNT(*) as total FROM HORARIO WHERE ID_HORARIO_UNICO = ? AND ID_RELACION_PROFESOR_ASIGNATURA IN (SELECT ID_RELACION_PROFESOR_ASIGNATURA FROM RELACION_PROFESOR_ASIGNATURA WHERE ID_PROFESOR = ?)";
    $paramsProfesor = array($id_horario_unico, $id_profesor);
    $resultProfesor = sqlsrv_query($conn, $queryProfesor, $paramsProfesor);
    $rowProfesor = sqlsrv_fetch_array($resultProfesor, SQLSRV_FETCH_ASSOC);

    if ($rowProfesor['total'] > 0) {
        return true;
    }

    return false;
}

function generarNuevoID($conn, $prefix) {
    $query = "SELECT COUNT(*) AS total FROM HORARIO";
    $result = sqlsrv_query($conn, $query);
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    $total = $row['total'] + 1;
    return $prefix . str_pad($total, 4, "0", STR_PAD_LEFT);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_grado = $_POST['id_grado'];

    // Obtener las asignaturas para el grado
    $queryAsignaturas = "SELECT ID_ASIGNATURA, INTENSIDAD_HORARIO FROM ASIGNATURA WHERE ESCOLARIDAD = (SELECT ESCOLARIDAD FROM GRADO WHERE ID_GRADO = ?)";
    $paramsAsignaturas = array($id_grado);
    $resultAsignaturas = sqlsrv_query($conn, $queryAsignaturas, $paramsAsignaturas);

    $asignaturas = array();
    while ($rowAsignaturas = sqlsrv_fetch_array($resultAsignaturas, SQLSRV_FETCH_ASSOC)) {
        $asignaturas[] = $rowAsignaturas;
    }

    // Obtener los horarios únicos disponibles
    $queryHorariosUnicos = "SELECT ID_HORARIO_UNICO FROM HORARIO_UNICO";
    $resultHorariosUnicos = sqlsrv_query($conn, $queryHorariosUnicos);

    $horariosUnicos = array();
    while ($rowHorariosUnicos = sqlsrv_fetch_array($resultHorariosUnicos, SQLSRV_FETCH_ASSOC)) {
        $horariosUnicos[] = $rowHorariosUnicos['ID_HORARIO_UNICO'];
    }

    $horarioGenerado = array();
    $conflictos = false;

    foreach ($asignaturas as $asignatura) {
        $intensidad = $asignatura['INTENSIDAD_HORARIO'];
        $id_asignatura = $asignatura['ID_ASIGNATURA'];

        // Obtener un profesor para la asignatura
        $queryProfesor = "SELECT TOP 1 ID_PROFESOR FROM RELACION_PROFESOR_ASIGNATURA WHERE ID_ASIGNATURA = ?";
        $paramsProfesor = array($id_asignatura);
        $resultProfesor = sqlsrv_query($conn, $queryProfesor, $paramsProfesor);
        $rowProfesor = sqlsrv_fetch_array($resultProfesor, SQLSRV_FETCH_ASSOC);
        $id_profesor = $rowProfesor['ID_PROFESOR'];

        // Asignar las horas necesarias para la asignatura
        for ($i = 0; $i < $intensidad; $i++) {
            $id_horario_unico = array_shift($horariosUnicos);

            if (verificarConflicto($conn, $id_grado, $id_horario_unico, $id_profesor)) {
                $conflictos = true;
                break;
            }

            $id_horario = generarNuevoID($conn, "HOR");

            $queryInsertHorario = "INSERT INTO HORARIO (ID_HORARIO, ID_RELACION_PROFESOR_ASIGNATURA, ID_GRADO, ID_HORARIO_UNICO) VALUES (?, (SELECT ID_RELACION_PROFESOR_ASIGNATURA FROM RELACION_PROFESOR_ASIGNATURA WHERE ID_PROFESOR = ? AND ID_ASIGNATURA = ?), ?, ?)";
            $paramsInsertHorario = array($id_horario, $id_profesor, $id_asignatura, $id_grado, $id_horario_unico);

            if (!sqlsrv_query($conn, $queryInsertHorario, $paramsInsertHorario)) {
                echo "Error al insertar horario: ";
                print_r(sqlsrv_errors());
                $conflictos = true;
                break;
            }

            $horarioGenerado[] = array(
                'ID_HORARIO' => $id_horario,
                'ID_RELACION_PROFESOR_ASIGNATURA' => $id_profesor,
                'ID_GRADO' => $id_grado,
                'ID_HORARIO_UNICO' => $id_horario_unico
            );
        }

        if ($conflictos) {
            break;
        }
    }

    if ($conflictos) {
        echo "<script>alert('No se pudo generar el horario debido a conflictos de asignación.');</script>";
        echo "<script>window.location = 'asignar_horario.php';</script>";
    } else {
        // Redirigir a la página para mostrar el horario generado
        $_SESSION['horario_generado'] = $horarioGenerado;
        header("Location: mostrar_horario.php");
        exit();
    }
}
?>