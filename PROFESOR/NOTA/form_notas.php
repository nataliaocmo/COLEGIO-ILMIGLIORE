<?php
include("conexion.php");

// $IdProf = $_POST["IdProf"];
$Numerodefallas = strtoupper($_POST['Numerofallas']);
$Nota1 = strtoupper($_POST['Nota1']);
$Nota2 = strtoupper($_POST["Nota3"]);
$Nota3 = strtoupper($_POST["Nota3"]);
$Nota4= strtoupper($_POST["Nota4"]);
$Notafinal = strtoupper($_POST["Notafinal"]);
$Aprobo =strtoupper($_POST["Aprobo"]) ;

$sql = "SELECT COUNT(*) AS total FROM NOTA";
$result = sqlsrv_query($conn,$sql);
$row = sqlsrv_fetch($result);
//Obtener el valor numerico
$total = sqlsrv_get_field($result, 0); 


// Generar el nuevo ID
$nuevo_numero = $total + 1;
$IdNota = "NOTA".str_pad($nuevo_numero, 4, "0", STR_PAD_LEFT);

$query = "INSERT INTO NOTA(ID_NOTA,NUMERO_DE_FALLAS,NOTA_P1,NOTA_P2,NOTA_P3,NOTA_P4,NOTA_FINAL,APROBO) VALUES ('$IdNota','$Numerodefallas','$Nota1','$Nota2','$Nota3','$Nota4','$Notafinal','$Aprobo')";
echo $query;
$res = sqlsrv_prepare($conn, $query);

if (sqlsrv_execute($res)) {
    echo "DATOS INGRESADOS";
    exit();

} else {
    if (($errors = sqlsrv_errors()) != null) {
        foreach ($errors as $error) {
            echo "ERROR AL INGRESAR LOS DATOS: " . $error['message'];
        }
    }
}
?>