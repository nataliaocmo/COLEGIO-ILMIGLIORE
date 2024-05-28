<?php
$id_administrativo = isset($_GET['id_administrativo']) ? $_GET['id_administrativo'] : null;
$contrasena_administrativo = isset($_GET['contrasena']) ? $_GET['contrasena'] : null;
?>
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
        <form action="cargas_estudiantes.php" method="get">
            <input type="hidden" name="id_administrativo" value="<?php echo $id_administrativo; ?>">
            <input type="hidden" name="contrasena" value="<?php echo $contrasena_administrativo; ?>">
            <button type="submit" class="btn-submit">Siguiente</button>
        </form>
        <form id="login-form" action="http://localhost:8081/sql/procesar_login.php" method="post">
            <input type="hidden" name="rol" value="administrativo">
            <input type="hidden" name="IdIngreso" value="<?php echo $_GET['id_administrativo']; ?>">
            <input type="hidden" name="contrasena" value="<?php echo $_GET['contrasena']; ?>">
            <button id="volver" type="submit">Volver</button>
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
        echo "<script>alert('Tu archivo no fue subido.'); window.location.href='cargas_acudientes.php';</script>";
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $successCount = 0;
            $errorCount = 0;

            // Abre el archivo CSV
            $file = fopen($target_file, "r");
            // Saltar la primera fila (cabecera)
            fgetcsv($file, 1000, ";");

            include("conexion.php");

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
                    $errorCount++;
                    continue;
                }
                if (!in_array($genero, ['femenino', 'masculino', 'otro'])) {
                    $errorCount++;
                    continue;
                }
                if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                    $errorCount++;
                    continue;
                }
                if (!ctype_digit($telefono)) {
                    $errorCount++;
                    continue;
                }

                // Verificar si el acudiente ya existe
                $sql2 = "SELECT COUNT(*) AS total FROM ACUDIENTE WHERE DOCUMENTO_DE_IDENTIDAD = '$documento'";
                $result2 = sqlsrv_query($conn, $sql2);
                $row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC);

                if ($row2['total'] > 0) {
                    $errorCount++;
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
                        $successCount++;
                    } else {
                        $errorCount++;
                    }
                } else {
                    $errorCount++;
                }
            }
            fclose($file);

            if ($successCount > 0 && $errorCount == 0) {
                echo "<script>alert('Todos los datos se subieron correctamente.'); window.location.href='cargas_estudiantes.php?id_administrativo=<?php echo $id_administrativo; ?>&contrasena=<?php echo $contrasena_administrativo; ?>';</script>";
            } elseif ($successCount > 0 && $errorCount > 0) {
                echo "<script>alert('Hubo algunos datos que no subimos por errores.'); window.location.href='cargas_estudiantes.php?id_administrativo=<?php echo $id_administrativo; ?>&contrasena=<?php echo $contrasena_administrativo; ?>';</script>";
            } else {
                echo "<script>alert('No se subio ningun archivo vuelve a intentarlo'); window.location.href='cargas_acudientes.php?id_administrativo=<?php echo $id_administrativo; ?>&contrasena=<?php echo $contrasena_administrativo; ?>';</script>";
            }
        } else {
            echo "<script>alert('Hubo un error al subir tu archivo.'); window.location.href='cargas_acudientes.php?id_administrativo=<?php echo $id_administrativo; ?>&contrasena=<?php echo $contrasena_administrativo; ?>';</script>";
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