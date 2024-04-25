<?php
include("conexion.php");
 
$DocId=$_POST["docId"];
$Nombre=$_POST["nombre"];
$Apellido=$_POST["apellido"];
$Genero=$_POST["genero"];
$FechaDeNacimiento=$_POST["fechaDeNacimiento"];
$Grado=$_POST["grado"];
$Telefono=$_POST["telefono"];
$EPS=$_POST["eps"];
$RH=$_POST["rh"];
$Direccion=$_POST["direccion"];

//CREADOR ID ESTUDIANTE
$sql = "SELECT COUNT(*) AS total FROM ESTUDIANTE";
$result = sqlsrv_query($conn,$sql);
$row = sqlsrv_fetch($result);
//Obtener el valor numerico
$total = sqlsrv_get_field($result, 0); 
// Generar el nuevo ID
$nuevo_numero = $total + 1;
$IdEstudiante= "EST".$nuevo_numero;

//BUSCADOR ID ACUDIENTE
$sql3 = "SELECT COUNT(*) AS total FROM ACUDIENTE";
$result3 = sqlsrv_query($conn,$sql3);
$row3 = sqlsrv_fetch($result3);
//Obtener el valor numerico
$total2 = sqlsrv_get_field($result3, 0); 
// ID ACUDIENTE
$IdAcudiente = "ACU".str_pad($nuevo_numero, 10, "0", STR_PAD_LEFT);

//Generar Correo Institucional
$CorreoInstitucional=$Nombre.$Apellido.'@ims.edu.co';

//FECHA DE NACIMIENTO
$fechaFormateada = date('Y-m-d H:i:s', strtotime($FechaDeNacimiento));

// asignarle un curso al estudiante
$letra = ['A', 'B', 'C']; // Array con las letras posibles
$indice = array_rand($letra); // Obtener un índice aleatorio del array
$letraAleatoria = $letra[$indice]; // Obtener la letra aleatoria
$Curso = $Grado . ' ' . $letraAleatoria;

//buscar id del curso
$sql3 = "SELECT * FROM GRADO WHERE NOMBRE='$Curso'";
$result3 = sqlsrv_query($conn, $sql3);
if ($result3 === false) {
    die(print_r(sqlsrv_errors(), true)); // Imprime errores SQL si los hay
}
$row3 = sqlsrv_fetch_array($result3, SQLSRV_FETCH_ASSOC);
if ($row3 === false) {
    echo "No se encontraron registros para el grado: $Curso";
} else {
    if (isset($row3['ID_GRADO'])) {
        $IdGrado = $row3['ID_GRADO'];
        echo "El ID del grado '$Curso' es: $IdGrado";
    } else {
        echo "No se encontró la columna ID_DEPARTAMENTO en el resultado";
    }
}

$sql2="SELECT * FROM ESTUDIANTE WHERE DOCUMENTO_DE_IDENTIDAD = '$DocId'";
$result2 = sqlsrv_query($conn,$sql2);
$row2 = sqlsrv_fetch($result2);

if ($row2==0){
    $query="INSERT INTO ESTUDIANTE(ID_ESTUDIANTE,DOCUMENTO_DE_IDENTIDAD,NOMBRE,APELLIDO,GENERO,FECHA_DE_NACIMIENTO,ID_GRADO,CORREO_INSTITUCIONAL,TELEFONO,EPS,RH,DIRECCION,ID_ACUDIENTE) VALUES('$IdEstudiante','$DocId','$Nombre','$Apellido','$Genero','$fechaFormateada','$IdGrado','$CorreoInstitucional','$Telefono','$EPS','$RH','$Direccion','$IdAcudiente')";
        echo $query;
    $res=sqlsrv_prepare($conn,$query);
 
        if (sqlsrv_execute($res)){
            header("Location: /sql/ADMINISTRATIVO/HISTORIAL_ACADEMICO/form_historial.html");
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
