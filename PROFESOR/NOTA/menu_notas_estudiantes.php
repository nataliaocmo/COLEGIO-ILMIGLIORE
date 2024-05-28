



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>menu notas de estudiante</title>
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