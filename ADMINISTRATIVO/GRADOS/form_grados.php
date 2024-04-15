<?php
include("conexion.php");

$IdGrado = $_POST["IdGrado"]; 
$Escolaridad = $_POST["Escolaridad"]; 
$IdDirector = $_POST["IdDirector"]; 
$Administrativo = $_POST["Administrativo"]; 

$IdAcudiente = 'ACU' . $IdAcu;

$query = "INSERT INTO GRADOS(ID_GRADO, ESCOLARIDAD, ID_DIRECTOR, ADMINISTRATIVO) VALUES ('$IdGrado', '$Escolaridad', '$IdDirector', '$Administrativo')";

// Ejecutar consulta
if (mysqli_query($conexion, $query)) {
    echo "¡Acudiente registrado correctamente!";
} else {
    echo "Error al registrar el acudiente: " . mysqli_error($conexion);
}

// Cerrar conexión
mysqli_close($conexion);
?>
