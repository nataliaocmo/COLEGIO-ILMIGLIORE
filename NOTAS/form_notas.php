<?php
include("conexion.php");

// $IdProf = $_POST["IdProf"];
$Numerodefallas = $_POST['numerofallas'];
$Nota1 = $_POST['Nota1'];
$Nota2 = $_POST["Nota3"];
$Nota3 = $_POST["Nota3"];
$Nota4= $_POST["Nota4"];
$Notafinal = $_POST["Notafinal"];
$Aprobo = $_POST["Aprobo"];


$sql = "SELECT COUNT(*) AS total FROM NOTA";
$result = sqlsrv_query($conn,$sql);
$row = sqlsrv_fetch($result);
//Obtener el valor numerico
$total = sqlsrv_get_field($result, 0); 


// Generar el nuevo ID
$nuevo_numero = $total + 1;
$IdNota = "NOTA".str_pad($nuevo_numero, 4, "0", STR_PAD_LEFT);

$query = "INSERT INTO NOTA(ID_NOTA,NUMERO_DE_FALLAS,NOTA_P1,NOTA_P2,NOTA_P3,NOTA_P4,NOTA_FINAL,APROBO/NO_APROBO) VALUES ('$IdNota','$Numerodefallas','$Nota1','$Nota2','$Nota3','$Nota4','$Notafinal','$Aprobo')";
echo $query;
$res = sqlsrv_prepare($conn, $query);

if (sqlsrv_execute($res)) {
    echo "DATOS INGRESADOS";
} else {
    if (($errors = sqlsrv_errors()) != null) {
        foreach ($errors as $error) {
            echo "ERROR AL INGRESAR LOS DATOS: " . $error['message'];
        }
    }
}
?>