<?php
session_start();

// Verifica si se envió un rol y un ID de ingreso
if(isset($_POST['rol'])) {
    $rol = $_POST['rol'];
    $IdIngreso = $_POST['IdIngreso'];
    $contrasena = $_POST['contrasena'];
    

    echo "<script>
        if (!sessionStorage.getItem('login_attempts')) {
            sessionStorage.setItem('login_attempts', 0);
        }
        var loginAttempts = parseInt(sessionStorage.getItem('login_attempts'));
    </script>";

    echo "<script>
        if (loginAttempts >= 3) {
            alert('Ha alcanzado el número máximo de intentos. Intente más tarde.');
            window.location.href = 'log_in.html';
        }
    </script>";

    switch ($rol) {
        case 'acudiente':
            include("ACUDIENTE/conexion_acudiente.php");
            if(isset($IdIngreso) && isset($contrasena)) {
                $query = "SELECT * FROM ACUDIENTE WHERE ID_ACUDIENTE = ?";
                $params = array($IdIngreso);
                $result = sqlsrv_query($conn, $query, $params);
        
                if($result && sqlsrv_has_rows($result)) {
                    $acudiente = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
                    if ($acudiente['CONTRASENA'] === $contrasena) {

                        echo "<script>sessionStorage.setItem('login_attempts', 0);</script>";
                        header("Location: ACUDIENTE/acudiente.php");
                        exit();
                    }
                }
                echo "<script>
                    sessionStorage.setItem('login_attempts', loginAttempts + 1);
                    window.location.href = 'log_in.html';
                </script>";
            }
            exit();
            break;
        case 'estudiante':
            include("ESTUDIANTE/ConexionEstudiante.php");
            if(isset($IdIngreso) && isset($contrasena)) {
                $query = "SELECT * FROM ESTUDIANTE WHERE ID_ESTUDIANTE = ?";
                $params = array($IdIngreso);
                $result = sqlsrv_query($conn, $query, $params);
        
                if($result && sqlsrv_has_rows($result)) {
                    $estudiante = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
                    if ($estudiante['CONTRASENA'] === $contrasena) {
                        $_SESSION['rol'] = "estudiante";
                        $_SESSION['estudiante'] = $estudiante;
                        $_SESSION['contrasena'] = $contrasena;
                        echo "<script>sessionStorage.setItem('login_attempts', 0);</script>";
                        header("Location: ESTUDIANTE/estudiante.php");
                        exit();
                    }
                }
                echo "<script>
                    sessionStorage.setItem('login_attempts', loginAttempts + 1);
                    window.location.href = 'log_in.html';
                </script>";
            }
            exit();
            break;
        case 'profesor':
            include("PROFESOR/ConexionProfesor.php");
            if(isset($IdIngreso) && isset($contrasena)) {
                $query = "SELECT * FROM PROFESOR WHERE ID_PROFESOR = ?";
                $params = array($IdIngreso);
                $result = sqlsrv_query($conn, $query, $params);
        
                if($result && sqlsrv_has_rows($result)) {
                    $profesor = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
                    if ($profesor['CONTRASENA'] === $contrasena) {
                        $_SESSION['rol'] = "profesor";
                        $_SESSION['profesor'] = $profesor;
                        $_SESSION['contrasena'] = $contrasena;
                        echo "<script>sessionStorage.setItem('login_attempts', 0);</script>";
                        header("Location: PROFESOR/profesor.php");
                        exit();
                    }
                }
                echo "<script>
                    sessionStorage.setItem('login_attempts', loginAttempts + 1);
                    window.location.href = 'log_in.html';
                </script>";
            }
            exit();
            break;
        case 'administrativo':
            include("ADMINISTRATIVO/ConexionAdministrativo.php");
            if(isset($IdIngreso) && isset($contrasena)) {
                $query = "SELECT * FROM ADMINISTRATIVO WHERE ID_ADMINISTRATIVO = ?";
                $params = array($IdIngreso);
                $result = sqlsrv_query($conn, $query, $params);
        
                if($result && sqlsrv_has_rows($result)) {
                    $administrativo = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
                    if ($administrativo['CONTRASENA'] === $contrasena) {
                        $_SESSION['rol'] = "administrativo";
                        $_SESSION['administrativo'] = $administrativo;
                        $_SESSION['contrasena'] = $contrasena;
                        echo "<script>sessionStorage.setItem('login_attempts', 0);</script>";
                        header("Location: ADMINISTRATIVO/administrativo.php");
                        exit();
                    }
                }
                echo "<script>
                    sessionStorage.setItem('login_attempts', loginAttempts + 1);
                    window.location.href = 'log_in.html';
                </script>";
            }
            exit();
            break;
        default:
            header("Location: log_in.html");
            echo '<script>alert("El usuario no se encuentra registrado");</script>';
            exit();
    }
} else {
    header("Location: log_in.html");
    echo '<script>alert("El usuario no se encuentra registrado");</script>';
    exit();
}
?>
