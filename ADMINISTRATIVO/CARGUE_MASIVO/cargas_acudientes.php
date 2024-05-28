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
        <form action="cargas_acudientes.php" method="post" enctype="multipart/form-data">
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
// Procesar el archivo CSV subido
if (isset($_POST["submit"])) {
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
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

            // Leer cada línea del archivo CSV y cargar los datos en la tabla
            while (($row = fgetcsv($file, 1000, ";")) !== FALSE) {
                $nombre = $row[0];
                $apellido = $row[1];
                $genero = strtolower($row[2]);
                $correo = $row[3];
                $telefono = $row[4];
                $direccion = $row[5];
                $documento = $row[6];

                // Validaciones
                if (empty($nombre) || empty($apellido) || empty($genero) || empty($correo) || empty($telefono) || empty($direccion) || empty($documento)) {
                    echo "<p style='color: red;'>Todos los campos son obligatorios para el registro de $nombre $apellido.</p>";
                    continue;
                }
                if (!in_array($genero, ['femenino', 'masculino', 'otro'])) {
                    echo "<p style='color: red;'>El género debe ser Femenino, Masculino u Otro para el registro de $nombre $apellido.</p>";
                    continue;
                }
                if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                    echo "<p style='color: red;'>El formato del correo electrónico es inválido para $correo.</p>";
                    continue;
                }
                if (!ctype_digit($telefono)) {
                    echo "<p style='color: red;'>El teléfono solo debe contener números para el registro de $telefono.</p>";
                    continue;
                }

                // Verificar si el acudiente ya existe
                $sql2 = "SELECT COUNT(*) AS total FROM ACUDIENTE WHERE DOCUMENTO_DE_IDENTIDAD = '$documento'";
                $result2 = sqlsrv_query($conn, $sql2);
                $row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC);

                if ($row2['total'] > 0) {
                    echo "<p style='color: red;'>El acudiente con documento $documento ya existe.</p>";
                    continue;
                }

                // Generar el nuevo ID de Acudiente
                $sql = "SELECT COUNT(*) AS total FROM ACUDIENTE";
                $result = sqlsrv_query($conn, $sql);
                $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
                $total = $row['total'];
                $nuevo_numero = $total + 1;
                $IdAcudiente = "ACU" . str_pad($nuevo_numero, 4, "0", STR_PAD_LEFT);

                // Generar contraseña aleatoria
                $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+{}|:<>?-=[];,./';
                $longitudCaracteres = strlen($caracteres);
                $contrasena = '';
                for ($i = 0; $i < 10; $i++) {
                    $indiceAleatorio = rand(0, $longitudCaracteres - 1);
                    $contrasena .= $caracteres[$indiceAleatorio];
                }
                
                // Insertar el acudiente en la base de datos
                $queryInsert = "INSERT INTO ACUDIENTE (ID_ACUDIENTE, DOCUMENTO_DE_IDENTIDAD, NOMBRE, APELLIDO, GENERO, CORREO, TELEFONO, DIRECCION, CONTRASENA) VALUES ('$IdAcudiente', '$documento', '$nombre', '$apellido', '$genero', '$correo', '$telefono', '$direccion', '$contrasena')";
                $res = sqlsrv_prepare($conn, $queryInsert);
                if ($res) {
                    if (sqlsrv_execute($res)) {
                        echo "<p style='color: green;'>Datos subidos correctamente para el acudiente $nombre $apellido.</p>";
                    } else {
                        echo "<p style='color: red;'>Error al insertar datos en la tabla ACUDIENTE.</p>";
                    }
                } else {
                    echo "<p style='color: red;'>Error al preparar la consulta para el registro de $nombre $apellido.</p>";
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