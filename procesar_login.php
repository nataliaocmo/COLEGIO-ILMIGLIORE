<?php
session_start();
// Verifica si se envió un rol y un ID de ingreso
if(isset($_POST['rol'])) {
    $rol = $_POST['rol'];
    echo $rol;
    $IdIngreso = $_POST['IdIngreso'];
    echo $IdIngreso;
    switch ($rol) {
        case 'acudiente':
            include("ACUDIENTE/conexion_acudiente.php");
            if(isset($_POST['IdIngreso'])) {
                // Realiza la consulta para buscar al acudiente por su ID
                $query = "SELECT * FROM ACUDIENTE WHERE ID_ACUDIENTE = '$IdIngreso'";
                $result = sqlsrv_query($conn, $query);
                // Verifica si se encontró algún acudiente con el ID ingresado
                if($result && sqlsrv_has_rows($result)) {
                    // Obtiene los datos del acudiente
                    $acudiente = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
                    // Guarda los datos del acudiente en la sesión
                    $_SESSION['rol'] = "acudiente";
                    $_SESSION['acudiente'] = $acudiente;
                    // Redirige al usuario a la página de acudiente
                    header("Location: ACUDIENTE/acudiente.php");
                    exit();
                } else {
                    // Si no se encontró ningún acudiente con el ID ingresado, redirige de vuelta al formulario de inicio de sesión con un mensaje de error
                    header("Location: log_in.html"); // Por ejemplo, error 2 puede indicar un ID de acudiente incorrecto
                    exit();
                }
            } else {
                // Si no se enviaron datos de inicio de sesión, redirige de vuelta al formulario de inicio de sesión
                header("Location: log_in.html");
                exit();
            }
            break;
        case 'estudiante':
            include("ESTUDIANTE/ConexionEstudiante.php");
            if(isset($_POST['IdIngreso'])) {
                $query = "SELECT * FROM ESTUDIANTE WHERE ID_ESTUDIANTE = '$IdIngreso'";
                $result = sqlsrv_query($conn, $query);
                if($result && sqlsrv_has_rows($result)) {
                    $estudiante = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
                    $_SESSION['rol'] = "estudiante";
                    $_SESSION['estudiante'] = $estudiante;
                    header("Location: ESTUDIANTE/estudiante.php");
                    exit();
                } else {
                    header("Location: log_in.html");
                    exit();
                }
            } else {
                header("Location: log_in.html");
                exit();
            }
            break;
        case 'profesor':
            include("PROFESOR/ConexionProfesor.php");
            if(isset($_POST['IdIngreso'])) {
                $query = "SELECT * FROM PROFESOR WHERE ID_PROFESOR = '$IdIngreso'";
                $result = sqlsrv_query($conn, $query);
                if($result && sqlsrv_has_rows($result)) {
                    $profesor = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
                    $_SESSION['rol'] = "profesor";
                    $_SESSION['profesor'] = $profesor;
                    header("Location: PROFESOR/profesor.php");
                    exit();
                } else {
                    header("Location: log_in.html");
                    exit();
                }
            } else {
                header("Location: log_in.html");
                exit();
            }
            break;
        case 'administrativo':
            include("ADMINISTRATIVO/ConexionAdministrativo.php");
            if(isset($_POST['IdIngreso'])) {
                $query = "SELECT * FROM ADMINISTRATIVO WHERE ID_ADMINISTRATIVO = '$IdIngreso'";
                $result = sqlsrv_query($conn, $query);
                if($result && sqlsrv_has_rows($result)) {
                    $administrativo = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
                    $_SESSION['rol'] = "administrativo";
                    $_SESSION['administrativo'] = $administrativo;
                    header("Location: ADMINISTRATIVO/administrativo.php");
                    exit();
                } else {
                    header("Location: log_in.html");
                    exit();
                }
            } else {
                header("Location: log_in.html");
                exit();
            }
            break;
        default:
            // Si se selecciona un rol no válido, redirige al formulario de inicio de sesión
            header("Location: log_in.html");
            exit();
    }
} else {
    // Si no se enviaron datos de inicio de sesión, redirige al formulario de inicio de sesión
    header("Location: log_in.html");
    exit();
}
?>