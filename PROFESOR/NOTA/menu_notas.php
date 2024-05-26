<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu grados para notas</title>
</head>
<body>
    <select name="grado" id="grado">
        <?php
        include("conexion.php");
        $query = "SELECT NOMBRE FROM GRADO";
        $result = sqlsrv_query($conn, $query);
    
        // Verificar si se obtuvieron resultados de la consulta
        if ($result === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    
        // Iterar sobre los resultados y generar las opciones del select
        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
            echo "<option>" . htmlspecialchars($row['NOMBRE']) ."</option>";
        }
    
        // Liberar los recursos y cerrar la conexión a la base de datos
        sqlsrv_free_stmt($result);
        sqlsrv_close($conn);
        ?>
    </select>
    
</body>
</html>
<?php
        // Incluir el archivo de conexión a la base de datos
        include("conexion.php");
    
        // Realizar la consulta SQL para obtener los grados
        $id_profesor=htmlspecialchars($_GET['id_profesor']);
        echo "el id es:".$id_profesor;

        $query = "SELECT ID_RELACION_PROFESOR_ASIGNATURA FROM RELACION_PROFESOR_ASIGNATURA WHERE ID_PROFESOR = $id_profesor";
        $params = array($id_profesor);
        $result = sqlsrv_query($conn, $query, $params);

        echo $result;

        $id_array = array();

        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
            $id_array[] = $row['ID_RELACION_PROFESOR_ASIGNATURA'];
            print_r($id_array);
        }

        foreach ($id_array as $id_relacion) {
            $query2 = "SELECT ID_GRADO FROM HORARIO WHERE ID_RELACION_PROFESOR_ASIGNATURA = $id_relacion";
            $params2 = array($id_relacion);
            $result2 = sqlsrv_query($conn, $query2,$params2);
        }
        $id_array2 = array();
        while ($row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC)) {
            $id_array2[] = $row2['ID_HORARIO'];
        }


        

        $query2="SELECT * FROM HORARIO WHERE ID_RELACION_PROFESOR_ASIGNATURA = $id_relacion";
        $result2 = sqlsrv_query($conn, $query);

        while ($row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC)) {
            $id_array[] = $row2['ID_HORARIO'];
        }

        print_r($id_array2);

        ?>
