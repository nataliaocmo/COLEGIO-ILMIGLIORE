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
$FechaDeContratacion = $_POST["fechaDeContratacion"];
$Salario = $_POST["salario"];
$Departamento = $_POST["departamento"];

$sql = "SELECT COUNT(*) AS total FROM ADMINISTRATIVO";
$result = sqlsrv_query($conn,$sql);
$row = sqlsrv_fetch($result);
//Obtener el valor numerico
$total = sqlsrv_get_field($result, 0); 

// Generar el nuevo ID
$nuevo_numero = $total + 1;
$IdAdministrativo = "ADMI".$nuevo_numero;
$fechaFormateada = date('Y-m-d H:i:s', strtotime($FechaDeContratacion));

$CorreoInstitucional=$Nombre.$Apellido.'@ims.edu.co';

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

//contraseña random

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
    $query = "INSERT INTO ADMINISTRATIVO(ID_ADMINISTRATIVO,CEDULA,NOMBRE,APELLIDO,GENERO,CARGO,CORREO,TELEFONO,EPS,RH,DIRECCION,FECHA_DE_CONTRATACION,SALARIO,ID_DEPARTAMENTO,CONTRASENA) VALUES ('$IdAdministrativo','$DocId','$Nombre','$Apellido','$Genero','$Cargo','$CorreoInstitucional','$Telefono','$EPS','$RH','$Direccion','$fechaFormateada','$Salario','$IdDepartamento','$contrasena')";
    echo $query;
    $res=sqlsrv_prepare($conn,$query);
 
        if (sqlsrv_execute($res)){
            header("Location: /sql/log_in.html");
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