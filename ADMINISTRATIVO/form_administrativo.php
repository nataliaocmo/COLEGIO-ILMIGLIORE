<?php
include("conexion.php");

$DocId = $_POST["docId"];
$Nombre = $_POST['nombre'];
$Apellido = $_POST['apellido'];
$Genero = $_POST["genero"];
$Cargo = $_POST["cargo"];
$Telefono = $_POST["telefono"];
$EPS = $_POST["eps"];
$RH = $_POST["rh"];
$Direccion = $_POST["direccion"];
$Departamento = $_POST["departamento"];

$Salario = '2000000';

$sql = "SELECT COUNT(*) AS total FROM ADMINISTRATIVO";
$result = sqlsrv_query($conn,$sql);
$row = sqlsrv_fetch($result);
//Obtener el valor numerico
$total = sqlsrv_get_field($result, 0); 

// Generar el nuevo ID
$nuevo_numero = $total + 1;
$IdAdministrativo = "ADMI".$nuevo_numero;

// Establecer la zona horaria, si es necesario
date_default_timezone_set('America/Bogota');
// Obtener la fecha y hora actual
$fecha = date("Y-m-d H:i:s");

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


$sql2 = "SELECT * FROM DEPARTAMENTO WHERE NOMBRE='$Departamento'";
$result2 = sqlsrv_query($conn, $sql2);
if ($result2 === false) {
    die(print_r(sqlsrv_errors(), true)); // Imprime errores SQL si los hay
}
$row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC);
if ($row2 === false) {
    echo "No se encontraron registros para el departamento: $Departamento";
} else {
    if (isset($row2['ID_DEPARTAMENTO'])) {
        $IdDepartamento = $row2['ID_DEPARTAMENTO'];
        echo "El ID del departamento '$Departamento' es: $IdDepartamento";
    } else {
        echo "No se encontró la columna ID_DEPARTAMENTO en el resultado";
    }
}

$sql3="SELECT * FROM ADMINISTRATIVO WHERE DOCUMENTO_DE_IDENTIDAD = '$DocId'";
echo $sql3;
$result3 = sqlsrv_query($conn,$sql3);
$row3 = sqlsrv_fetch($result3);
echo $row3;
echo $IdDepartamento;

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

if ($row3==0){
    $query = "INSERT INTO ADMINISTRATIVO(ID_ADMINISTRATIVO,DOCUMENTO_DE_IDENTIDAD,NOMBRE,APELLIDO,GENERO,CARGO,CORREO_INSTITUCIONAL,TELEFONO,EPS,RH,DIRECCION,FECHA_DE_CONTRATACION,SALARIO,ID_DEPARTAMENTO,CONTRASENA) VALUES ('$IdAdministrativo','$DocId','$Nombre','$Apellido','$Genero','$Cargo','$CorreoInstitucional','$Telefono','$EPS','$RH','$Direccion','$fechaFormateada','$Salario','$IdDepartamento','$contrasena')";
    echo $query;
    $res=sqlsrv_prepare($conn,$query);
 
        if (sqlsrv_execute($res)){
            echo "<script>alert('Su usuario ha sido creado de manera exitosa, ID:$IdAdministrativo CONTRASEÑA:$contrasena.');</script>";
            echo "<script>window.location = '/sql/ADMINISTRATIVO/administrativo.php';</script>";
            exit();
        }else{
            if( ($errors = sqlsrv_errors() ) != null) {
                foreach( $errors as $error ) {
                    echo "ERROR AL INGRESAR LOS DATOS: " . $error['message'];
                }
            }
        }
    }else if ($row3==1){
        header("Location: form_administrativo.html");
        exit();
    }
?>