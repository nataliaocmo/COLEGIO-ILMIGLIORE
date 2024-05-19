<?php
session_start();
// Verifica si se envió un rol y un ID de ingreso
if(isset($_POST['rol'])) {
    $rol = $_POST['rol'];
    $IdIngreso = $_POST['IdIngreso'];
    $contrasena =$_POST['contrasena'];
    switch ($rol) {
        case 'acudiente':
            include("ACUDIENTE/conexion_acudiente.php");
            if(isset($_POST['IdIngreso']) && isset($_POST['contrasena'])) {
                $IdIngreso = $_POST['IdIngreso'];
                $contrasena = $_POST['contrasena'];
                
                // Query to retrieve the professor with the given ID
                $query = "SELECT * FROM ACUDIENTE WHERE ID_ACUDIENTE = ?";
                $params = array($IdIngreso);
                $result = sqlsrv_query($conn, $query, $params);
        
                if($result && sqlsrv_has_rows($result)) {
                    // Fetch the professor's data
                    $acudiente = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
                    // Check if the password matches
                    if ($acudiente['CONTRASENA'] === $contrasena) {
                        $_SESSION['rol'] = "acudiente";
                        $_SESSION['acudiente'] = $acudiente;
                        $_SESSION['contrasena'] = $contrasena;
                        header("Location: ACUDIENTE/acudiente.php");
                        exit();
                    } else{
                        echo '<script type="text/javascript">window.onload = function () { alert("ID o Contraseña incorrectas"); }</script>';
                        echo "<script>window.location = 'log_in.html';</script>"; 
                    }
                 }
            } else {
                // Si no se enviaron datos de inicio de sesión, redirige de vuelta al formulario de inicio de sesión
                header("Location: log_in.html");
                exit();
            }
            break;
        case 'estudiante':
            include("ESTUDIANTE/ConexionEstudiante.php");
            if(isset($_POST['IdIngreso']) && isset($_POST['contrasena'])) {
                $IdIngreso = $_POST['IdIngreso'];
                $contrasena = $_POST['contrasena'];
                
                // Query to retrieve the professor with the given ID
                $query = "SELECT * FROM ESTUDIANTE WHERE ID_ESTUDIANTE = ?";
                $params = array($IdIngreso);
                $result = sqlsrv_query($conn, $query, $params);
        
                if($result && sqlsrv_has_rows($result)) {
                    // Fetch the professor's data
                    $estudiante = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
        
                    // Check if the password matches
                    if ($estudiante['CONTRASENA'] === $contrasena) {
                        $_SESSION['rol'] = "estudiante";
                        $_SESSION['estudiante'] = $estudiante;
                        $_SESSION['contrasena'] = $contrasena;
                        header("Location: ESTUDIANTE/estudiante.php");
                        exit();
                    }else{
                        echo '<script type="text/javascript">window.onload = function () { alert("ID o Contraseña incorrectas"); }</script>';
                        echo "<script>window.location = 'log_in.html';</script>"; 
                    }
                }
            } else {
                header("Location: log_in.html");
                exit();
            }
            break;
            case 'profesor':
                include("PROFESOR/ConexionProfesor.php");
                if(isset($_POST['IdIngreso']) && isset($_POST['contrasena'])) {
                    $IdIngreso = $_POST['IdIngreso'];
                    $contrasena = $_POST['contrasena'];
                    
                    // Query to retrieve the professor with the given ID
                    $query = "SELECT * FROM PROFESOR WHERE ID_PROFESOR = ?";
                    $params = array($IdIngreso);
                    $result = sqlsrv_query($conn, $query, $params);
            
                    if($result && sqlsrv_has_rows($result)) {
                        // Fetch the professor's data
                        $profesor = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
            
                        // Check if the password matches
                        if ($profesor['CONTRASENA'] === $contrasena) {
                            $_SESSION['rol'] = "profesor";
                            $_SESSION['profesor'] = $profesor;
                            $_SESSION['contrasena'] = $contrasena;
                            header("Location: PROFESOR/profesor.php");
                            exit();
                        }else{
                            echo '<script type="text/javascript">window.onload = function () { alert("ID o Contraseña incorrectas"); }</script>';
                            echo "<script>window.location = 'log_in.html';</script>"; 
                        }
                    }
                }
                // If authentication fails or data is missing, redirect to login page
                header("Location: log_in.html");
                exit();
                break;
            
        case 'administrativo':
            include("ADMINISTRATIVO/ConexionAdministrativo.php");
            if(isset($_POST['IdIngreso']) && isset($_POST['contrasena'])) {
                $IdIngreso = $_POST['IdIngreso'];
                $contrasena = $_POST['contrasena'];
                
                // Query to retrieve the professor with the given ID
                $query = "SELECT * FROM ADMINISTRATIVO WHERE ID_ADMINISTRATIVO = ?";
                $params = array($IdIngreso);
                $result = sqlsrv_query($conn, $query, $params);
        
                if($result && sqlsrv_has_rows($result)) {
                    // Fetch the professor's data
                    $administrativo = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
        
                    // Check if the password matches
                    if ($administrativo['CONTRASENA'] === $contrasena) {
                        $_SESSION['rol'] = "administrativo";
                        $_SESSION['administrativo'] = $administrativo;
                        $_SESSION['contrasena'] = $contrasena;
                        header("Location: ADMINISTRATIVO/administrativo.php");
                        exit();
                    }else{
                        echo '<script type="text/javascript">window.onload = function () { alert("ID o Contraseña incorrectas"); }</script>';
                        echo "<script>window.location = 'log_in.html';</script>"; 
                    }
                }
            } else {
                header("Location: log_in.html");
                exit();
            }
            break;
        default:
            // Si se selecciona un rol no válido, redirige al formulario de inicio de sesión
            header("Location: log_in.html");
            echo  '<p>alert("el usuario no se encuentra registrado")<p>'; 
            exit();
    }
} else {
    // Si no se enviaron datos de inicio de sesión, redirige al formulario de inicio de sesión
    echo '<script type="text/javascript">window.onload = function () { alert("el usuario no se encuentra registrado"); }</script>'; 
    echo "<script>window.location = 'log_in.html';</script>";
    exit();
}
?>