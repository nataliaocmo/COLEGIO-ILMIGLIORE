<?php
include("conexion.php");
 
$DocId=$_POST["docId"];
$Nombre=$_POST["nombre"];
$Apellido=$_POST["apellido"];
$Genero=$_POST["genero"];
$Correo=$_POST["correo"];
$Telefono=$_POST["telefono"];
$Direccion=$_POST["direccion"];

$sql = "SELECT COUNT(*) AS total FROM ACUDIENTE";
$result = sqlsrv_query($conn,$sql);
$row = sqlsrv_fetch($result);
//Obtener el valor numerico
$total = sqlsrv_get_field($result, 0); 
// Generar el nuevo ID
$nuevo_numero = $total + 1;
$IdAcudiente = "ACU".str_pad($nuevo_numero, 4, "0", STR_PAD_LEFT);

$sql2="SELECT * FROM ACUDIENTE WHERE DOCUMENTO_DE_IDENTIDAD = '$DocId'";
$result2 = sqlsrv_query($conn,$sql2);
$row2 = sqlsrv_fetch($result2);

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

if ($row2==0){
    $query="INSERT INTO ACUDIENTE(ID_ACUDIENTE,DOCUMENTO_DE_IDENTIDAD,NOMBRE,APELLIDO,GENERO,CORREO,TELEFONO,DIRECCION,CONTRASENA) VALUES('$IdAcudiente','$DocId','$Nombre','$Apellido','$Genero','$Correo','$Telefono','$Direccion','$contrasena')";
        $res=sqlsrv_prepare($conn,$query);
        if (sqlsrv_execute($res)){
            echo "<script>alert('Su usuario ha sido creado de manera exitosa, ID:$IdAcudiente CONTRASEÑA:$contrasena.');</script>";
            echo "<script>window.location = '/sql/ESTUDIANTE/form_estudiante.html';</script>";
            exit();
        }else{
            if( ($errors = sqlsrv_errors() ) != null) {
                foreach( $errors as $error ) {
                    echo "ERROR AL INGRESAR LOS DATOS: " . $error['message'];
                }
            }
        }
    }else if ($row2==1){
        header("Location: form_acudiente.html");
        exit();
    }
?>