<?php
session_start();

// Verifica si el usuario está autenticado como acudiente
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== "administrativo") {
    header("Location: /sql/log_in.html");
    exit();
}

// Accede a los datos del acudiente desde la sesión
$administrativo = $_SESSION['administrativo'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrativo</title>
    <link rel="stylesheet" href="administrativo.css">
</head>

<body>
    <div class="container">
        <div class="left-side">
            <div class="top-bar">
                <div class="icons">
                    <a href="Principal_IMS.html"><img src="/sql/IMG/inicio.png" alt="Home"></a>
                    <img src="/sql/IMG/menu_.png" alt="Menu">
                </div>
                <br>
                <div class="red-bar">ADMINISTRATIVO<button class="circular-button">&#8594;</button></div>

            </div>
        </div>
        <div class="right-side">
            <div class="content">
                <ul>
                    <li><span>Admisiones</span><a href="form_acudiente.html"><button class="circular-button">&#8594;</button></li>
                    <br>
                    <li><span>Pago de Matriculas</span><button class="circular-button">&#8594;</button></li>
                    <br>
                    <li><span>Gestión de Horarios</span><button class="circular-button">&#8594;</button></li>
                    <br>
                    <li><span>Gestión de Grados</span><button class="circular-button">&#8594;</button></li>
                    <br>
                    <li><span>Contrataciones</span><a href="form_administrativo.html"><button class="circular-button">&#8594;</button></li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>