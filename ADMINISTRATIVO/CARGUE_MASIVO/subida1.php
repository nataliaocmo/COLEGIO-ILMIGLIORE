<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Archivo CSV</title>
</head>
<body>
    <div class="container">
        <h2>Subir Archivo CSV</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="fileToUpload">Seleccionar Archivo CSV:</label>
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
if(isset($_POST["submit"])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    
    // Verificar si el archivo es CSV
    if($fileType != "csv") {
        echo "<p style='color: red;'>Solo se permiten archivos CSV.</p>";
        $uploadOk = 0;
    }

    // Subir archivo si todo está bien
    if ($uploadOk == 0) {
        echo "<p style='color: red;'>Tu archivo no fue subido.</p>";
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "<p style='color: green;'>El archivo ". basename( $_FILES["fileToUpload"]["name"]). " ha sido subido correctamente.</p>";

            // Abre el archivo CSV
            $file = fopen($target_file, "r");
            // Saltar la primera fila (cabecera)
            fgetcsv($file);

        // Conexión a la base de datos MySQL
        $serverName = "DESKTOP-07UTCOR"; //serverName\instanceName
	    $connectionInfo = array( "Database"=>"PrubasCargueMasivo", "UID"=>"Danna", "PWD"=>"root");
	    $conn = sqlsrv_connect( $serverName, $connectionInfo);

		if( $conn ) {
     			echo "Conexión establecida.<br />";
		}else{
     			echo "Conexión no se pudo establecer.<br />";
     		die( print_r( sqlsrv_errors(), true));
		}

		echo "<p style='color: green;'>voy a cargar la tabla.</p>";
        // Leer cada línea del archivo CSV y cargar los datos en la tabla
        while (($row = fgetcsv($file,1000,";"))!==FALSE) {
            $id = $row[0];
            $cedula = $row[1];
            $nombre = $row[2];
            $apellido = $row[3];
            $email = $row[4];
            $institution = $row[5];
            $costo = $row[6];
            $curso = $row[7];

            $query = "INSERT INTO semilleros(id,cedula, nombre, apellido, email, institution, costo, curso) VALUES ('$id','$cedula', '$nombre', '$apellido', '$email', '$institution', '$costo', '$curso')";
            echo $query;
            $res = sqlsrv_prepare($conn, $query);
            
            if ($res) {
                if (sqlsrv_execute($res)) {
                    echo "<p style='color: green;'>DATOS SUBIDO CORRECTAMENTE.</p>";
                } else {
                    echo "<p style='color: red;'>Error al insertar datos en la tabla semilleros.</p>";
                }
            } else {
                echo "<p style='color: red;'>Error al preparar la consulta.</p>";
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

<script>
    // Actualizar el nombre del archivo seleccionado en el formulario
    document.getElementById("fileToUpload").addEventListener("change", function() {
        var fileName = this.files[0].name;
        document.querySelector(".file-name").textContent = fileName;
    });
</script>
</body>
</html>