
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notas Académicas</title>
</head>
<body>
    <h2>Tabla de tu periodo academicoo</h2>
    <label for="periodo">Nombre:</label>
    <input type="text" id="nombre" name="nombre" value="<?php echo $estudiante['NOMBRE'] . ' ' . $estudiante['APELLIDO']; ?>" readonly><br><br>

    <label for="year">Año Académico:</label>
    <select id="year" name="year">
        <option value="2022">2010</option>
        <option value="2023">2011</option>
        <option value="2024">2012</option>
        <option value="2022">2013</option>
        <option value="2023">2014</option>
        <option value="2024">2015</option>
        <option value="2022">2016</option>
        <option value="2023">2017</option>
        <option value="2024">2018</option>
        <option value="2022">2019</option>
        <option value="2023">2020</option>
        <option value="2024">2021</option>
        <option value="2022">2022</option>
        <option value="2023">2023</option>
        <option value="2024">2024</option>
    </select><br><br>

    <table>
        <thead>
            <tr>
                <th>Periodo Académico</th>
                <th>Primer Periodo</th>
                <th>Segundo Periodo</th>
                <th>Tercer Periodo</th>
                <th>Cuarto Periodo</th>
                <th>Fallas</th>
                <th>Nota Final</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>