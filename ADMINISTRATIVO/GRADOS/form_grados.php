<?php
include("conexion.php");

$IdGrado =strtoupper( $_POST["IdGrado"]); 
$Escolaridad = strtoupper($_POST["Escolaridad"]); 
$IdDirector = strtoupper($_POST["IdDirector"]); 
$Administrativo = strtoupper($_POST["Administrativo"]); 

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
