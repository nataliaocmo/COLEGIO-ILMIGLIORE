<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transferencia externa de Estudiantes</title>
    <link rel="stylesheet" href="cargas.css">
</head>
<body>
    <div class="container">
        <h2>Transferencia externa de Estudiantes</h2>
        <form action="cargas_acudientes.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="fileToUpload">Archivo de Estudiantes</label>
                <label class="custom-file-upload">
                    <input type="file" name="fileToUpload" id="fileToUpload" accept=".csv">
                    Seleccionar Archivo
                </label>
                <span class="file-name"></span>
            </div>
            <button type="submit" class="btn-submit" name="submit">Subir Archivo</button>
        </form>
    </div>

    <?php
// Procesar el archivo CSV subido
if (isset($_POST["submit"])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Verificar si el archivo es CSV
    if ($fileType != "csv") {
        echo "<p style='color: red;'>Solo se permiten archivos CSV.</p>";
        $uploadOk = 0;
    }

    // Subir archivo si todo está bien
    if ($uploadOk == 0) {
        echo "<p style='color: red;'>Tu archivo no fue subido.</p>";
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "<p style='color: green;'>El archivo ". basename($_FILES["fileToUpload"]["name"]). " ha sido subido correctamente.</p>";

            // Abre el archivo CSV
            $file = fopen($target_file, "r");
            // Saltar la primera fila (cabecera)
            fgetcsv($file, 1000, ";");

            include("conexion.php");

            echo "<p style='color: green;'>Voy a cargar la tabla.</p>";

            // Definir los grados válidos
            $gradosValidos = ['PREESCOLAR', 'PRIMERO', 'SEGUNDO', 'TERCERO', 'CUARTO', 'QUINTO', 'SEXTO', 'SEPTIMO', 'OCTAVO', 'NOVENO', 'DECIMO', 'UNDECIMO'];

            // Leer cada línea del archivo CSV y cargar los datos en la tabla
            while (($row = fgetcsv($file, 1000, ";")) !== FALSE) {
                $nombre = $row[0];
                $apellido = $row[1];
                $genero = strtolower($row[2]);
                $fechaNacimiento = $row[3];
                $telefono = $row[4];
                $eps = $row[5];
                $rh = $row[6];
                $direccion = $row[7];
                $docIdAcudiente = $row[8];
                $grado = strtoupper($row[9]);

                // Validaciones
                if (empty($nombre) || empty($apellido) || empty($genero) || empty($fechaNacimiento) || empty($telefono) || empty($eps) || empty($rh) || empty($direccion) || empty($docIdAcudiente) || empty($grado)) {
                    echo "<p style='color: red;'>Todos los campos son obligatorios.</p>";
                    continue;
                }

                if (!in_array($genero, ['femenino', 'masculino', 'otro'])) {
                    echo "<p style='color: red;'>El género debe ser Femenino, Masculino u Otro.</p>";
                    continue;
                }

                if (!ctype_digit($telefono)) {
                    echo "<p style='color: red;'>El teléfono solo debe contener números.</p>";
                    continue;
                }

                if (!in_array($grado, $gradosValidos)) {
                    echo "<p style='color: red;'>El grado ingresado no es válido.</p>";
                    continue;
                }

                // Verificar si el acudiente existe
                $queryAcudiente = "SELECT ID_ACUDIENTE FROM ACUDIENTE WHERE DOCUMENTO = '$docIdAcudiente'";
                $resultAcudiente = sqlsrv_query($conn, $queryAcudiente);
                $rowAcudiente = sqlsrv_fetch_array($resultAcudiente, SQLSRV_FETCH_ASSOC);

                if (!$rowAcudiente) {
                    echo "<p style='color: red;'>No se encontró un acudiente con el documento $docIdAcudiente.</p>";
                    continue;
                }

                $idAcudiente = $rowAcudiente['ID_ACUDIENTE'];

                // Generar ID_ESTUDIANTE y CONTRASENA
                $idEstudiante = "EST" . str_pad(rand(0, 99999), 5, "0", STR_PAD_LEFT);
                $contrasena = bin2hex(random_bytes(4)); // Contraseña aleatoria de 8 caracteres

                // Insertar el estudiante en la base de datos
                $queryInsert = "INSERT INTO ESTUDIANTE (ID_ESTUDIANTE, NOMBRE, APELLIDO, GENERO, FECHA_DE_NACIMIENTO, TELEFONO, EPS, RH, DIRECCION, ID_ACUDIENTE, GRADO, CONTRASENA) 
                                VALUES ('$idEstudiante', '$nombre', '$apellido', '$genero', '$fechaNacimiento', '$telefono', '$eps', '$rh', '$direccion', '$idAcudiente', '$grado', '$contrasena')";
                $res = sqlsrv_prepare($conn, $queryInsert);

                if ($res) {
                    if (sqlsrv_execute($res)) {
                        echo "<p style='color: green;'>Datos subidos correctamente para el estudiante $nombre $apellido.</p>";
                    } else {
                        echo "<p style='color: red;'>Error al insertar datos en la tabla ESTUDIANTE.</p>";
                    }
                } else {
                    echo "<p style='color: red;'>Error al preparar la consulta.</p>";
                }
            }
            fclose($file);

            echo "<p style='color: green;'>Datos cargados correctamente.</p>";
        } else {
            echo "<p style='color: red;'>Hubo un error al subir tu archivo.</p>";
        }
    }
}
?>

<script>
    // Actualizar el nombre del archivo seleccionado en el formulario
    document.getElementById("fileToUpload").addEventListener("change", function() {
        var fileName = this.files[0].name;
        document.querySelector(".file-name").textContent = fileName;
    });
</script>
</body>
</html>