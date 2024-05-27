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
        include "conexion.php";
            foreach ($grado_array as $grado): ?>
                <option value="<?php echo htmlspecialchars($grado); ?>">
                    <?php echo htmlspecialchars($grado); ?>
                </option>
            <?php endforeach; ?>
    </select>
    
</body>
</html>
<?php
        // Incluir el archivo de conexión a la base de datos
        include("conexion.php");
    
        // Realizar la consulta SQL para obtener los grados
        $id_profesor=htmlspecialchars($_GET['id_profesor']);
        echo "el id es:".$id_profesor;

        $query = "SELECT ID_RELACION_PROFESOR_ASIGNATURA FROM RELACION_PROFESOR_ASIGNATURA WHERE ID_PROFESOR = ?";
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
            $id_array2[] = $row2['ID_GRADO'];
        }


        foreach($id_array2 as $id_grado){

            $query3="SELECT NOMBRE FROM GRADO WHERE ID_GRADO = $id_grado";
            $params3 = array($id_grado);
            $result3 = sqlsrv_query($conn, $query, $params);

        }

        $grado_array=array();
        while ($row3 = sqlsrv_fetch_array($result3, SQLSRV_FETCH_ASSOC)) {
            $grado_array[] = $row3['NOMBRE'];
        }

        print_r($grado_array);

        ?>