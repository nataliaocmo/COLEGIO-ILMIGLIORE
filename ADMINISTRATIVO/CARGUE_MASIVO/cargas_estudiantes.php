<?php
$id_administrativo = isset($_GET['id_administrativo']) ? $_GET['id_administrativo'] : null;
$contrasena_administrativo = isset($_GET['contrasena']) ? $_GET['contrasena'] : null;
?>
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
        <form action="cargas_estudiantes.php" method="post" enctype="multipart/form-data">
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

            // Definir los grados válidos
            $gradosValidos = ['PREESCOLAR', 'PRIMERO', 'SEGUNDO', 'TERCERO', 'CUARTO', 'QUINTO', 'SEXTO', 'SEPTIMO', 'OCTAVO', 'NOVENO', 'DECIMO', 'UNDECIMO'];

            $successCount = 0;
            $errorCount = 0;

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
                $docId = $row[10];

                // Validaciones
                if (empty($nombre) || empty($apellido) || empty($genero) || empty($fechaNacimiento) || empty($telefono) || empty($eps) || empty($rh) || empty($direccion) || empty($docIdAcudiente) || empty($grado)) {
                    echo "<p style='color: red;'>Todos los campos son obligatorios para el estudiante $nombre $apellido.</p>";
                    $errorCount++;
                    continue;
                }

                if (!in_array($genero, ['femenino', 'masculino', 'otro'])) {
                    echo "<p style='color: red;'>El género debe ser Femenino, Masculino u Otro para el estudiante $nombre $apellido.</p>";
                    $errorCount++;
                    continue;
                }

                if (!ctype_digit($telefono)) {
                    echo "<p style='color: red;'>El teléfono solo debe contener números para el estudiante $nombre $apellido.</p>";
                    $errorCount++;
                    continue;
                }

                if (!in_array($grado, $gradosValidos)) {
                    echo "<p style='color: red;'>El grado ingresado no es válido para el estudiante $nombre $apellido.</p>";
                    $errorCount++;
                    continue;
                }

                // Verificar si el acudiente existe
                $queryAcudiente = "SELECT ID_ACUDIENTE FROM ACUDIENTE WHERE DOCUMENTO_DE_IDENTIDAD = '$docIdAcudiente'";
                $resultAcudiente = sqlsrv_query($conn, $queryAcudiente);
                $rowAcudiente = sqlsrv_fetch_array($resultAcudiente, SQLSRV_FETCH_ASSOC);
                if (!$rowAcudiente) {
                    echo "<p style='color: red;'>No se encontró un acudiente con el documento $docIdAcudiente para el estudiante $nombre $apellido.</p>";
                    $errorCount++;
                    continue;
                }
                $idAcudiente = $rowAcudiente['ID_ACUDIENTE'];

                // Verificar si el estudiante ya existe
                $sql2 = "SELECT COUNT(*) AS total FROM ESTUDIANTE WHERE DOCUMENTO_DE_IDENTIDAD = '$docId'";
                $result2 = sqlsrv_query($conn, $sql2);
                $row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC);
                if ($row2['total'] > 0) {
                    $errorCount++;
                    continue;
                }

                // Generar el nuevo ID del Estudiante
                $sql = "SELECT COUNT(*) AS total FROM ACUDIENTE";
                $result = sqlsrv_query($conn, $sql);
                $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
                $total = $row['total'];
                $nuevo_numero = $total + 1;
                $idEstudiante = "EST" . str_pad($nuevo_numero, 4, "0", STR_PAD_LEFT);

                // Generar contraseña aleatoria
                $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+{}|:<>?-=[];,./';
                $longitudCaracteres = strlen($caracteres);
                $contrasena = '';
                for ($i = 0; $i < 10; $i++) {
                    $indiceAleatorio = rand(0, $longitudCaracteres - 1);
                    $contrasena .= $caracteres[$indiceAleatorio];
                }

                // ID del grado al cual va a entrar
                // Asignar un curso al estudiante
                $letra = ['A', 'B', 'C']; // Array con las letras posibles
                $indice = array_rand($letra); // Obtener un índice aleatorio del array
                $letraAleatoria = $letra[$indice]; // Obtener la letra aleatoria
                $Curso = $grado . ' ' . $letraAleatoria;
                $queryGrado = "SELECT ID_GRADO FROM GRADO WHERE NOMBRE='$Curso'"; // Buscar ID del curso
                $resultGrado = sqlsrv_query($conn, $queryGrado);
                $rowGrado = sqlsrv_fetch_array($resultGrado, SQLSRV_FETCH_ASSOC);
                $idGrado = $rowGrado['ID_GRADO'];

                $fechaCorregida = $fechaNacimiento . " 00:00:00.000";

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
                $NombreNormalizado = normalizar($nombre);
                $ApellidoNormalizado = normalizar($apellido);
                // Crear el correo institucional
                $CorreoInstitucional = $NombreNormalizado . $ApellidoNormalizado . '@ims.edu.co';

                // Contraseña random
                $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+{}|:<>?-=[];,./';
                $longitudCaracteres = strlen($caracteres);
                $contrasena = '';
                for ($i = 0; $i < 10; $i++) {
                    $indiceAleatorio = rand(0, $longitudCaracteres - 1);
                    $contrasena .= $caracteres[$indiceAleatorio];
                }

                // Insertar el estudiante en la base de datos
                $queryInsert = "INSERT INTO ESTUDIANTE (ID_ESTUDIANTE, DOCUMENTO_DE_IDENTIDAD, NOMBRE, APELLIDO, GENERO, FECHA_DE_NACIMIENTO, ID_GRADO, CORREO_INSTITUCIONAL, TELEFONO, EPS, RH, DIRECCION, ID_ACUDIENTE, CONTRASENA) VALUES ('$idEstudiante', '$docId', '$nombre', '$apellido', '$genero', '$fechaCorregida', '$idGrado', '$CorreoInstitucional', '$telefono', '$eps', '$rh', '$direccion', '$idAcudiente', '$contrasena')";
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
                echo "<script>alert('No se subio ningun archivo vuelve a intentarlo'); window.location.href='cargas_estudiantes.php?id_administrativo=<?php echo $id_administrativo; ?>&contrasena=<?php echo $contrasena_administrativo; ?>';</script>";
            }
        } else {
            echo "<script>alert('Hubo un error al subir tu archivo.'); window.location.href='cargas_estudiantes.php?id_administrativo=<?php echo $id_administrativo; ?>&contrasena=<?php echo $contrasena_administrativo; ?>';</script>";
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