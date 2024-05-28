<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transferencia externa de Acudientes</title>
    <link rel="stylesheet" href="cargas.css">
</head>
<body>
    <div class="container">
        <h2>Transferencia externa de Acudientes</h2>
        <form action="cargar_acudientes.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="fileToUpload">Archivo de Acudientes</label>
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

            $allRecordsValid = true;

            while (($row = fgetcsv($file, 1000, ";")) !== FALSE) {
                $documento_identidad = $row[0];
                $nombre = $row[1];
                $apellido = $row[2];
                $genero = $row[3];
                $correo = $row[4];
                $telefono = $row[5];
                $direccion = $row[6];

                if (empty($documento_identidad) || empty($nombre) || empty($apellido) || empty($genero) || empty($correo) || empty($telefono) || empty($direccion)) {
                    echo "<p style='color: red;'>Faltan campos obligatorios. Registro omitido.</p>";
                    $allRecordsValid = false;
                    continue;
                }

                $id_acudiente = "ACU" . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
                $contrasena = bin2hex(random_bytes(4));

                $query = "INSERT INTO ACUDIENTE (ID_ACUDIENTE, DOCUMENTO_IDENTIDAD, NOMBRE, APELLIDO, GENERO, CORREO, TELEFONO, DIRECCION, CONTRASENA) 
                          VALUES ('$id_acudiente', '$documento_identidad', '$nombre', '$apellido', '$genero', '$correo', '$telefono', '$direccion', '$contrasena')";
                $stmt = sqlsrv_prepare($conn, $query);

                if ($stmt) {
                    if (sqlsrv_execute($stmt)) {
                        echo "<p style='color: green;'>Acudiente insertado correctamente.</p>";
                    } else {
                        echo "<p style='color: red;'>Error al insertar acudiente: " . print_r(sqlsrv_errors(), true) . "</p>";
                        $allRecordsValid = false;
                    }
                } else {
                    echo "<p style='color: red;'>Error al preparar la consulta del acudiente.</p>";
                    $allRecordsValid = false;
                }
            }

            fclose($file);
            if ($allRecordsValid) {
                echo "<p style='color: green;'>DATOS CARGADOS CORRECTAMENTE.</p>";
                echo "<script>window.location.href='cargas_estudiantes.php';</script>";
            } else {
                echo "<p style='color: red;'>Algunos registros no se pudieron cargar. Revise los errores y vuelva a intentarlo.</p>";
            }
        } else {
            echo "<p style='color: red;'>Hubo un error al subir tu archivo.</p>";
        }
    }
}
?>

</body>
</html>