<?php
include("conexionEstudiante.php");
 
$DocId=strtoupper($_POST["docId"]);
$Nombre=strtoupper($_POST["nombre"]);
$Apellido=strtoupper($_POST["apellido"]);
$Genero=strtoupper($_POST["genero"]);
$FechaDeNacimiento=strtoupper($_POST["fechaDeNacimiento"]);
$Grado=strtoupper($_POST["grado"]);
$Telefono=strtoupper($_POST["telefono"]);
$EPS=strtoupper($_POST["eps"]);
$RH=strtoupper($_POST["rh"]);
$Direccion=strtoupper($_POST["direccion"]);

//CREADOR ID ESTUDIANTE
$sql = "SELECT COUNT(*) AS total FROM ESTUDIANTE";
$result = sqlsrv_query($conn,$sql);
$row = sqlsrv_fetch($result);
//Obtener el valor numerico
$total = sqlsrv_get_field($result, 0); 
// Generar el nuevo ID
$nuevo_numero = $total + 1;
$IdEstudiante= "EST".str_pad($nuevo_numero, 4, "0", STR_PAD_LEFT);

//BUSCAR EL ID DEL ACUDIENTE
$sql2 = "SELECT COUNT(*) AS total FROM ACUDIENTE";
$result2 = sqlsrv_query($conn,$sql2);
$row2 = sqlsrv_fetch($result2);
//Obtener el valor numerico
$total2 = sqlsrv_get_field($result2, 0); 
// ID DEL ACUDIENTE
$IdAcudiente = "ACU".str_pad($total2, 4, "0", STR_PAD_LEFT);

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
        echo "No se encontró la columna ID_GRADO en el resultado";
        echo "Este es el curso: ".$Curso;
    }
}

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



$sql4="SELECT * FROM ESTUDIANTE WHERE DOCUMENTO_DE_IDENTIDAD = '$DocId'";
$result4 = sqlsrv_query($conn,$sql4);
$row4 = sqlsrv_fetch($result4);

if ($row4==0){
    $query="INSERT INTO ESTUDIANTE(ID_ESTUDIANTE,DOCUMENTO_DE_IDENTIDAD,NOMBRE,APELLIDO,GENERO,FECHA_DE_NACIMIENTO,ID_GRADO,CORREO_INSTITUCIONAL,TELEFONO,EPS,RH,DIRECCION,ID_ACUDIENTE,CONTRASENA) VALUES('$IdEstudiante','$DocId','$Nombre','$Apellido','$Genero','$fechaFormateada','$IdGrado','$CorreoInstitucional','$Telefono','$EPS','$RH','$Direccion','$IdAcudiente','$contrasena')";
    $res=sqlsrv_prepare($conn,$query);
 
        if (sqlsrv_execute($res)){
            echo "<script>alert('Su usuario ha sido creado de manera exitosa, ID:$IdEstudiante CONTRASEÑA:$contrasena.');</script>";
            echo "<script>window.location = '/sql/ADMINISTRATIVO/HISTORIAL_ACADEMICO/form_historial.html';</script>";
            exit();
        }else{
            if( ($errors = sqlsrv_errors() ) != null) {
                foreach( $errors as $error ) {
                    echo "ERROR AL INGRESAR LOS DATOS: " . $error['message'];
                }
            }
        }
    }else if ($row4==1){
        header("Location: form_acudiente.html");
        exit();
    }
?>