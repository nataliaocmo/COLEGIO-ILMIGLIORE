<?php
// Conexión a la base de datos
$serverName="LAPTOP-3H9G1TCV";
$connectionInfo=array("Database"=>"Proyecto","UID"=>"NATALIA","PWD"=>"root");
$conn=sqlsrv_connect($serverName,$connectionInfo);

if($conn){
	echo "Conexion establecida.<br/>";
}else{
	echo"Conexion no se pudo establecer.<br/>";
	die(print_r(sqlsrv_erors(),true));
}

// Obtener datos del formulario
$rol = $_POST['rol'];
$Id = $_POST['IdIngreso'];

// Consultar la base de datos para verificar el usuario y la contraseña
if($rol=='Acudiente'){
    $query = "SELECT ID_ACUDIENTE, FROM ADCUDIENTE WHERE ID_ACUDIENTE = ?";
    $params = array($rol);
    $result = sqlsrv_query($conn, $query, $params);
}

if($rol=='Profesor'){
    $query = "SELECT ID_PROFESOR, FROM PROFESOR WHERE ID_PROFESOR = ?";
    $params = array($username);
    $result = sqlsrv_query($conn, $query, $params);
}

if($rol=='Estudiante'){
    $query = "SELECT ID_ESTUDIANTE, FROM ESTUDIANTE WHERE ID_ESTUDIANTE = ?";
    $params = array($username);
    $result = sqlsrv_query($conn, $query, $params);
}

if($rol=='Administrativo'){
    $query = "SELECT ID_ADMINISTRATIVO, FROM ADMINISTRATIVO WHERE ID_ADMINISTRATIVO = ?";
    $params = array($username);
    $result = sqlsrv_query($conn, $query, $params);
}

if ($result === false) {
    die("Error al ejecutar la consulta: " . sqlsrv_errors());
}

if (sqlsrv_has_rows($result)) {
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    $storedPassword = $row['ID_ACUDIENTE'];

    // Verificar si la contraseña ingresada coincide con la contraseña almacenada
    if ($rol === $storedPassword) {
        // Contraseña válida, iniciar sesión
        session_start();
        $_SESSION['loggedin'] = true;
        $_SESSION['rol'] = $rol;
        $_SESSION['IdIngreso'] = $row['IdUser'];
        $_SESSION['permissions'] = $row['Permisos'];
        
        // Redireccionar según el tipo de usuario
        switch ($row['rol']) {
            case 'Administrativo':
                header('Location: admin_home.php');
                break;
            case 'Profesor':
                header('Location: profesor_home.php');
                break;
            case 'Estudiante':
                header('Location: estudiante_home.php');
                break;
            case 'Acudiente':
                header('Location: acudiente_home.php');
                break;
            default:
                // Si el tipo de usuario no coincide con ninguno de los anteriores, redireccionar a una página de error
                header('Location: error_page.php');
                break;
        }
        exit();
    } else {
        // Contraseña incorrecta
        echo "Contraseña incorrecta";
    }
} else {
    // Usuario no encontrado
    echo "Usuario no encontrado";
}

// Cerrar la conexión
sqlsrv_close($conn);