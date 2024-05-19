<?php
include("conexion.php");

// $IdProf = $_POST["IdProf"];
$Nombre = $_POST['nombre'];
$Apellido = $_POST['apellido'];
$Genero = $_POST["genero"];
$Telefono = $_POST["telefono"];
$EPS = $_POST["eps"];
$RH = $_POST["rh"];
$Direccion = $_POST["direccion"];
$FechaDeContratacion = $_POST["fechaDeContratacion"];
$DocId = $_POST["docId"];
$Area = $_POST["area"];

$Salario = '3000000';

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
$IdProfesor = "PROF".$nuevo_numero;
$fechaFormateada = date('Y-m-d H:i:s', strtotime($FechaDeContratacion));

$CorreoInstitucional=$Nombre.$Apellido.'@ims.edu.co';

$query = "INSERT INTO PROFESOR(ID_PROFESOR,NOMBRE,APELLIDO,GENERO,TELEFONO,EPS,RH,DIRECCION,FECHA_DE_CONTRATACION,SALARIO,CORREO_INSTITUCIONAL,DOCUMENTO_IDENTIDAD,AREA,CONTRASENA) VALUES ('$IdProfesor','$Nombre','$Apellido','$Genero','$Telefono','$EPS','$RH','$Direccion','$fechaFormateada','$Salario','$CorreoInstitucional','$DocId','$Area','$contrasena')";
echo $query;
$res = sqlsrv_prepare($conn, $query);

if (sqlsrv_execute($res)) {
    echo "<script>alert('Su usuario ha sido creado de manera exitosa, ID:$IdProfesor CONTRASEÑA:$contrasena.');</script>";
    echo "<script>window.location = '/sql/ADMINISTRATIVO/administrativo.php';</script>";
    exit();
} else {
    if (($errors = sqlsrv_errors()) != null) {
        foreach ($errors as $error) {
            echo "ERROR AL INGRESAR LOS DATOS: " . $error['message'];
        }
    }
}
?>