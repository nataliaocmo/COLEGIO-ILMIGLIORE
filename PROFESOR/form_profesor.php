<?php
include("conexionProfesor.php");

// $IdProf = $_POST["IdProf"];
$Nombre = strtoupper($_POST['nombre']);
$Apellido = strtoupper($_POST['apellido']);
$Genero = strtoupper($_POST["genero"]);
$Telefono = strtoupper($_POST["telefono"]);
$EPS = strtoupper($_POST["eps"]);
$RH = strtoupper($_POST["rh"]);
$Direccion = strtoupper($_POST["direccion"]);
$DocId = strtoupper($_POST["docId"]);
$Area = strtoupper($_POST["area"]);

$Salario = '3000000';

function normalizar($cadena) {
    // Eliminar espacios
    $cadena = str_replace(' ', '', $cadena);
    // Reemplazar caracteres con tildes
    $originales = 'ÁÉÍÓÚáéíóúñÑ';
    $modificadas = 'AEIOUaeiounN';
    $cadena = strtr($cadena, $originales, $modificadas);
    return $cadena;
}
// Normalizar nombre y apellido
$NombreNormalizado = normalizar($Nombre);
$ApellidoNormalizado = normalizar($Apellido);
// Crear el correo institucional
$CorreoInstitucional = $NombreNormalizado . $ApellidoNormalizado . '@ims.edu.co';

$sql = "SELECT COUNT(*) AS total FROM PROFESOR";
$result = sqlsrv_query($conn,$sql);
$row = sqlsrv_fetch($result);
//Obtener el valor numerico
$total = sqlsrv_get_field($result, 0); 

//Contraseña random
// Caracteres disponibles para la contraseña
$caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+{}|:<>?-=[];,./';
$longitudCaracteres = strlen($caracteres);
$contrasena = '';
// Generar la contraseña aleatoria
for ($i = 0; $i < 10; $i++) {
    // Seleccionar un carácter aleatorio del conjunto de caracteres
    $indiceAleatorio = rand(0, $longitudCaracteres - 1);
    // Agregar el carácter aleatorio a la contraseña
    $contrasena .= $caracteres[$indiceAleatorio];
}

// Generar el nuevo ID
$nuevo_numero = $total + 1;
$IdProfesor = "PROF".str_pad($nuevo_numero, 4, "0", STR_PAD_LEFT);
$fechaFormateada = date('Y-m-d H:i:s', strtotime($FechaDeContratacion));

// Establecer la zona horaria, si es necesario
date_default_timezone_set('America/Bogota');
// Obtener la fecha y hora actual
$fecha = date("Y-m-d H:i:s");

$query = "INSERT INTO PROFESOR(ID_PROFESOR,NOMBRE,APELLIDO,GENERO,TELEFONO,EPS,RH,DIRECCION,FECHA_DE_CONTRATACION,SALARIO,CORREO_INSTITUCIONAL,DOCUMENTO_DE_IDENTIDAD,AREA,CONTRASENA) VALUES ('$IdProfesor','$Nombre','$Apellido','$Genero','$Telefono','$EPS','$RH','$Direccion','$fecha','$Salario','$CorreoInstitucional','$DocId','$Area','$contrasena')";
echo $query;
$res = sqlsrv_prepare($conn, $query);

if (sqlsrv_execute($res)) {
    // Obtener los ID_ASIGNATURA correspondientes al área especificada
    $query_asignaturas = "SELECT ID_ASIGNATURA FROM ASIGNATURA WHERE AREA = ?";
    $params_asignaturas = array($Area);
    $result_asignaturas = sqlsrv_query($conn, $query_asignaturas, $params_asignaturas);

    $id_asignaturas = array();
    while ($row = sqlsrv_fetch_array($result_asignaturas, SQLSRV_FETCH_ASSOC)) {
        $id_asignaturas[] = $row['ID_ASIGNATURA'];
    }

    // Seleccionar un ID_ASIGNATURA aleatorio
    if (!empty($id_asignaturas)) {
        $id_asignatura_aleatoria = $id_asignaturas[array_rand($id_asignaturas)];

        // Obtener el nombre de la asignatura seleccionada
        $query_nombre_asignatura = "SELECT NOMBRE FROM ASIGNATURA WHERE ID_ASIGNATURA = ?";
        $params_nombre_asignatura = array($id_asignatura_aleatoria);
        $result_nombre_asignatura = sqlsrv_query($conn, $query_nombre_asignatura, $params_nombre_asignatura);
        $row_nombre_asignatura = sqlsrv_fetch_array($result_nombre_asignatura, SQLSRV_FETCH_ASSOC);
        $nombre_asignatura = $row_nombre_asignatura['NOMBRE'];

        // Obtener el total de relaciones en RELACION_PROFESOR_ASIGNATURA
        $sql_rpa = "SELECT COUNT(*) AS total FROM RELACION_PROFESOR_ASIGNATURA";
        $result_rpa = sqlsrv_query($conn, $sql_rpa);
        $row_rpa = sqlsrv_fetch_array($result_rpa);
        $total_rpa = $row_rpa['total'];

        // Generar el nuevo ID_RELACION_PROFESOR_ASIGNATURA
        $nuevo_numero_rpa = $total_rpa + 1;
        $IdRelacionProfesorAsignatura = "RPA" . str_pad($nuevo_numero_rpa, 4, "0", STR_PAD_LEFT);

        // Insertar la nueva relación en RELACION_PROFESOR_ASIGNATURA
        $query_relacion = "INSERT INTO RELACION_PROFESOR_ASIGNATURA(ID_RELACION_PROFESOR_ASIGNATURA, ID_PROFESOR, ID_ASIGNATURA) VALUES (?, ?, ?)";
        $params_relacion = array($IdRelacionProfesorAsignatura, $IdProfesor, $id_asignatura_aleatoria);
        $res_relacion = sqlsrv_prepare($conn, $query_relacion, $params_relacion);

        if (sqlsrv_execute($res_relacion)) {
            echo "<script>alert('Su usuario ha sido creado de manera exitosa, ID: $IdProfesor, CONTRASEÑA: $contrasena, CORREO INSTITUCIONAL: $CorreoInstitucional, ASIGNATURA: $nombre_asignatura.');</script>";
            echo "<script>window.location = '/sql/ADMINISTRATIVO/administrativo.php';</script>";
        } else {
            echo "ERROR AL INGRESAR LA RELACION: " . print_r(sqlsrv_errors(), true);
        }
    } else {
        echo "No se encontraron asignaturas para el área especificada.";
    }
} else {
    if (($errors = sqlsrv_errors()) != null) {
        foreach ($errors as $error) {
            echo "ERROR AL INGRESAR LOS DATOS: " . $error['message'];
        }
    }
}
?>