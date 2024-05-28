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
        <form action="cargar_acudientes.php" method="post" enctype="multipart/form-data">
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
if (isset($_POST["submit"])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if ($fileType != "csv") {
        echo "<p style='color: red;'>Solo se permiten archivos CSV.</p>";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "<p style='color: red;'>Tu archivo no fue subido.</p>";
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "<p style='color: green;'>El archivo " . basename($_FILES["fileToUpload"]["name"]) . " ha sido subido correctamente.</p>";

            $file = fopen($target_file, "r");
            fgetcsv($file);

            include("conexion.php");

            while (($row = fgetcsv($file, 1000, ";")) !== FALSE) {
                $nombre_estudiante = $row[0];
                $apellido_estudiante = $row[1];
                $genero_estudiante = $row[2];
                $fecha_nacimiento_estudiante = $row[3];
                $telefono_estudiante = $row[4];
                $eps_estudiante = $row[5];
                $rh_estudiante = $row[6];
                $direccion_estudiante = $row[7];
                $documento_acudiente = $row[8];
                $grado = $row[9];

                if (empty($nombre_estudiante) || empty($apellido_estudiante) || empty($genero_estudiante) || empty($fecha_nacimiento_estudiante) || empty($telefono_estudiante) || empty($eps_estudiante) || empty($rh_estudiante) || empty($direccion_estudiante) || empty($documento_acudiente) || empty($grado)) {
                    echo "<p style='color: red;'>Faltan campos obligatorios. Registro omitido.</p>";
                    continue;
                }

                $queryAcudiente = "SELECT ID_ACUDIENTE FROM ACUDIENTE WHERE DOCUMENTO_IDENTIDAD = '$documento_acudiente'";
                $resultAcudiente = sqlsrv_query($conn, $queryAcudiente);
                if ($acudienteRow = sqlsrv_fetch_array($resultAcudiente, SQLSRV_FETCH_ASSOC)) {
                    $id_acudiente = $acudienteRow['ID_ACUDIENTE'];

                    $id_estudiante = "EST" . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
                    $correo_institucional = strtolower($nombre_estudiante . "." . $apellido_estudiante . "@colegio.edu");
                    $contrasena = bin2hex(random_bytes(4));

                    $queryInsert = "INSERT INTO ESTUDIANTE (ID_ESTUDIANTE, NOMBRE, APELLIDO, GENERO, FECHA_DE_NACIMIENTO, TELEFONO, EPS, RH, DIRECCION, ID_ACUDIENTE, ID_GRADO, CORREO_INSTITUCIONAL, CONTRASENA) 
                                    VALUES ('$id_estudiante', '$nombre_estudiante', '$apellido_estudiante', '$genero_estudiante', '$fecha_nacimiento_estudiante', '$telefono_estudiante', '$eps_estudiante', '$rh_estudiante', '$direccion_estudiante', '$id_acudiente', '$grado', '$correo_institucional', '$contrasena')";
                    $stmtInsert = sqlsrv_prepare($conn, $queryInsert);

                    if ($stmtInsert) {
                        if (sqlsrv_execute($stmtInsert)) {
                            echo "<p style='color: green;'>Estudiante insertado correctamente.</p>";
                        } else {
                            echo "<p style='color: red;'>Error al insertar estudiante: " . print_r(sqlsrv_errors(), true) . "</p>";
                        }
                    } else {
                        echo "<p style='color: red;'>Error al preparar la consulta del estudiante.</p>";
                    }
                } else {
                    echo "<p style='color: red;'>Acudiente con documento de identidad $documento_acudiente no encontrado. Registro omitido.</p>";
                }
            }

            fclose($file);
            echo "<p style='color: green;'>DATOS CARGADOS CORRECTAMENTE.</p>";
        } else {
            echo "<p style='color: red;'>Hubo un error al subir tu archivo.</p>";
        }
    }
}
?>

</body>
</html>