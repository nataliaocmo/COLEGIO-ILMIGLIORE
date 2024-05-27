<?php
session_start();
include("conexion.php");

// Función para generar un nuevo ID
function generarNuevoID($conn, $prefix, $table, $column) {
    $query = "SELECT COUNT(*) AS total FROM $table";
    $result = sqlsrv_query($conn, $query);
    $row = sqlsrv_fetch_array($result);
    $total = $row['total'];
    $nuevo_numero = $total + 1;
    $IdHorario = $prefix . str_pad($nuevo_numero, 4, "0", STR_PAD_LEFT);
    return $IdHorario;
}

// Obtener el grado seleccionado
$idGrado = $_POST['id_grado'];

// Obtener la escolaridad del grado
$query2 = "SELECT ESCOLARIDAD FROM GRADO WHERE ID_GRADO = '$idGrado'";
$result2 = sqlsrv_query($conn, $query2);
$escolaridadRow = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC);
$escolaridad = $escolaridadRow['ESCOLARIDAD'];

// Obtener las asignaturas para el grado
$query = "SELECT ID_ASIGNATURA, INTENSIDAD_HORARIO FROM ASIGNATURA WHERE ESCOLARIDAD = '$escolaridad'";
$result = sqlsrv_query($conn, $query);
$asignaturas = [];
while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    $asignaturas[] = $row;
}

// Definir los límites de horario según la escolaridad
$limitesHorario = [
    'PREESCOLAR' => '13:30:00',
    'PRIMARIA' => '15:45:00',
    'SECUNDARIA' => '15:45:00',
    'PREPARATORIA' => '18:00:00'
];

// Obtener los horarios únicos disponibles hasta el límite correspondiente
$query = "SELECT * FROM HORARIO_UNICO WHERE HORA_DE_FIN <= '{$limitesHorario[$escolaridad]}'";
$result = sqlsrv_query($conn, $query);

$horariosUnicos = [];
while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    $horariosUnicos[] = $row;
}

// Generar el horario
$horarioGenerado = [];
$horarioConflicto = false;

foreach ($asignaturas as $asignatura) {
    $intensidad = $asignatura['INTENSIDAD_HORARIO'];
    $idAsignatura = $asignatura['ID_ASIGNATURA'];
    $query = "SELECT ID_RELACION_PROFESOR_ASIGNATURA FROM RELACION_PROFESOR_ASIGNATURA WHERE ID_ASIGNATURA = '$idAsignatura'";
    $result = sqlsrv_query($conn, $query);
    $relacionProfesorAsignatura = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)['ID_RELACION_PROFESOR_ASIGNATURA'];

    $horasAsignadas = 0;
    $diasAsignados = [];

    foreach ($horariosUnicos as $horarioUnico) {
        if ($horasAsignadas >= $intensidad) {
            break;
        }

        $idHorarioUnico = $horarioUnico['ID_HORARIO_UNICO'];

        // Verificar conflictos con otros grados y profesores
        $queryConflictoGrado = "SELECT COUNT(*) AS total FROM HORARIO WHERE ID_GRADO = '$idGrado' AND ID_HORARIO_UNICO = '$idHorarioUnico'";
        $resultConflictoGrado = sqlsrv_query($conn, $queryConflictoGrado);
        $totalConflictoGrado = sqlsrv_fetch_array($resultConflictoGrado)['total'];

        $queryConflictoProfesor = "SELECT COUNT(*) AS total FROM HORARIO WHERE ID_RELACION_PROFESOR_ASIGNATURA = '$relacionProfesorAsignatura' AND ID_HORARIO_UNICO = '$idHorarioUnico'";
        $resultConflictoProfesor = sqlsrv_query($conn, $queryConflictoProfesor);
        $totalConflictoProfesor = sqlsrv_fetch_array($resultConflictoProfesor)['total'];

        $diaHorario = $horarioUnico['DIA'];
        if (!in_array($diaHorario, $diasAsignados) && $totalConflictoGrado == 0 && $totalConflictoProfesor == 0) {
            $idHorario = generarNuevoID($conn, 'HOR', 'HORARIO', 'ID_HORARIO');
            $queryInsert = "INSERT INTO HORARIO (ID_HORARIO, ID_RELACION_PROFESOR_ASIGNATURA, ID_GRADO, ID_HORARIO_UNICO) VALUES ('$idHorario', '$relacionProfesorAsignatura', '$idGrado', '$idHorarioUnico')";
            if (sqlsrv_query($conn, $queryInsert)) {
                $horarioGenerado[] = [
                    'ID_HORARIO' => $idHorario,
                    'ID_RELACION_PROFESOR_ASIGNATURA' => $relacionProfesorAsignatura,
                    'ID_GRADO' => $idGrado,
                    'ID_HORARIO_UNICO' => $idHorarioUnico,
                ];
                $horasAsignadas += 2; // Asumimos que cada "INTENSIDAD_HORARIO" representa 2 horas.
                $diasAsignados[] = $diaHorario; // Registrar el día en el que se asignó esta asignatura
            } else {
                echo "<script>alert('Error al insertar horario: " . print_r(sqlsrv_errors(), true) . "');</script>";
                $horarioConflicto = true;
                break;
            }
        }
    }

    if ($horasAsignadas < $intensidad) {
        $horarioConflicto = true;
        break;
    }
}

if ($horarioConflicto) {
    echo "<script>alert('No se pudo generar el horario debido a conflictos de asignación.'); window.location.href='asignar_horario.php';</script>";
    exit();
} else {
    $_SESSION['horario_generado'] = $horarioGenerado;
    echo "<script>alert('Horario generado exitosamente para el grado $idGrado'); window.location.href='mostrar_horario.php';</script>";
}
?>