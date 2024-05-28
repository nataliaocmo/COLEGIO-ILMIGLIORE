<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carga Masiva de Estudiantes y Acudientes</title>
    <link rel="stylesheet" href="transferencias_externas.css">
</head>
<body>
    <div class="container">
        <h2>Carga Masiva de Estudiantes y Acudientes</h2>
        <form action="cargar_datos.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="fileToUpload">Archivo de Estudiantes y Acudientes</label>
                <input type="file" name="fileToUpload" id="fileToUpload" accept=".csv">
            </div>
            <button type="submit" name="submit">Subir Archivo</button>
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

            $serverName = "DESKTOP-07UTCOR";
            $connectionInfo = array("Database" => "PrubasCargueMasivo", "UID" => "Danna", "PWD" => "root");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            if ($conn) {
                echo "Conexión establecida.<br />";
            } else {
                echo "Conexión no se pudo establecer.<br />";
                die(print_r(sqlsrv_errors(), true));
            }

            $documentosAcudientes = [];

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
                $nombre_acudiente = $row[9];
                $apellido_acudiente = $row[10];
                $genero_acudiente = $row[11];
                $correo_acudiente = $row[12];
                $telefono_acudiente = $row[13];
                $direccion_acudiente = $row[14];
                $grado = $row[15];

                if (empty($nombre_estudiante) || empty($apellido_estudiante) || empty($genero_estudiante) || empty($fecha_nacimiento_estudiante) || empty($telefono_estudiante) || empty($eps_estudiante) || empty($rh_estudiante) || empty($direccion_estudiante) || empty($documento_acudiente) || empty($nombre_acudiente) || empty($apellido_acudiente) || empty($genero_acudiente) || empty($correo_acudiente) || empty($telefono_acudiente) || empty($direccion_acudiente) || empty($grado)) {
                    echo "<p style='color: red;'>Faltan campos obligatorios. Registro omitido.</p>";
                    continue;
                }

                if (!in_array($documento_acudiente, $documentosAcudientes)) {
                    $queryAcudiente = "SELECT ID_ACUDIENTE FROM ACUDIENTE WHERE DOCUMENTO_IDENTIDAD = '$documento_acudiente'";
                    $resultAcudiente = sqlsrv_query($conn, $queryAcudiente);
                    if ($acudienteRow = sqlsrv_fetch_array($resultAcudiente, SQLSRV_FETCH_ASSOC)) {
                        $id_acudiente = $acudienteRow['ID_ACUDIENTE'];
                    } else {
                        $id_acudiente = "ACU" . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
                        $contrasena_acudiente = bin2hex(random_bytes(4));
                        $queryInsertAcudiente = "INSERT INTO ACUDIENTE (ID_ACUDIENTE, DOCUMENTO_IDENTIDAD, NOMBRE, APELLIDO, GENERO, CORREO, TELEFONO, DIRECCION, CONTRASENA) VALUES ('$id_acudiente', '$documento_acudiente', '$nombre_acudiente', '$apellido_acudiente', '$genero_acudiente', '$correo_acudiente', '$telefono_acudiente', '$direccion_acudiente', '$contrasena_acudiente')";
                        $stmtAcudiente = sqlsrv_prepare($conn, $queryInsertAcudiente);

                        if ($stmtAcudiente) {
                            if (sqlsrv_execute($stmtAcudiente)) {
                                echo "<p style='color: green;'>Acudiente insertado correctamente.</p>";
                                $documentosAcudientes[] = $documento_acudiente;
                            } else {
                                echo "<p style='color: red;'>Error al insertar acudiente: " . print_r(sqlsrv_errors(), true) . "</p>";
                                continue;
                            }
                        } else {
                            echo "<p style='color: red;'>Error al preparar la consulta del acudiente.</p>";
                            continue;
                        }
                    }
                }

                $id_estudiante = "EST" . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
                $correo_institucional = strtolower("$nombre_estudiante.$apellido_estudiante@institucion.edu");
                $contrasena_estudiante = bin2hex(random_bytes(4));

                $queryInsertEstudiante = "INSERT INTO ESTUDIANTES (ID_ESTUDIANTE, NOMBRE, APELLIDO, GENERO, FECHA_DE_NACIMIENTO, TELEFONO, EPS, RH, DIRECCION, ID_ACUDIENTE, ID_GRADO, CORREO_INSTITUCIONAL, CONTRASENA) VALUES ('$id_estudiante', '$nombre_estudiante', '$apellido_estudiante', '$genero_estudiante', '$fecha_nacimiento_estudiante', '$telefono_estudiante', '$eps_estudiante', '$rh_estudiante', '$direccion_estudiante', '$id_acudiente', '$grado', '$correo_institucional', '$contrasena_estudiante')";
                $stmtEstudiante = sqlsrv_prepare($conn, $queryInsertEstudiante);

                if ($stmtEstudiante) {
                    if (sqlsrv_execute($stmtEstudiante)) {
                        echo "<p style='color: green;'>Estudiante insertado correctamente.</p>";
                    } else {
                        echo "<p style='color: red;'>Error al insertar estudiante: " . print_r(sqlsrv_errors(), true) . "</p>";
                    }
                } else {
                    echo "<p style='color: red;'>Error al preparar la consulta del estudiante.</p>";
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