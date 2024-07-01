<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesamiento de Registros</title>
    <style>
         body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 100px;
        }

        .boton {
            background-color: green;
            color: white;
            padding: 45px 55px;
            margin: 20px 0;
            border: none;
            cursor: pointer;
            width: 300px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        input[type="text"], input[type="password"] {
            margin: 10px 0;
            padding: 10px;
            width: 300px;
        }

        h1 {
            color: black;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>
<div class="message-container">
    <?php
    // Conexión a la base de datos
    $hostname = "localhost";
    $username = "id22374583_carproe3t";
    $password = "carpro-E3T";
    $database = "id22374583_proyecto";
    $port = 21;
    $conn = new mysqli($hostname, $username, $password, $database, $port);

    if ($conn->connect_error) {
        echo "<div class='error-message'>Error en la conexión: " . $conn->connect_error . "</div>";
    } else {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $disponible = isset($_POST['disponible']) ? 1 : 0;

// El resto de tu código para procesar los datos del formulario...
$nombreProyecto = mysqli_real_escape_string($conn, $_POST['nombreProyecto']);
$descripcion = mysqli_real_escape_string($conn, $_POST['descripcion']);
$numeroEstudiantes = mysqli_real_escape_string($conn, $_POST['numeroEstudiantes']);
$emailProfesor = mysqli_real_escape_string($conn, $_POST['emailProfesor']);
$fecha = mysqli_real_escape_string($conn, $_POST['fecha']);
$datosad = mysqli_real_escape_string($conn, $_POST['datosad']);

$sql = "INSERT INTO proyectos (nombre_proyecto, descripcion, numero_estudiantes, email_profesor, fecha, datosad, disponible) VALUES ('$nombreProyecto', '$descripcion', $numeroEstudiantes, '$emailProfesor', '$fecha','$datosad', $disponible)";
            if ($conn->query($sql) === TRUE) {
                echo "<div class='success-message'>Datos guardados exitosamente.</div>";
            } else {
                echo "<div class='error-message'>Error al guardar los datos: " . $conn->error . "</div>";
            }
        }

        $conn->close();
    }
    ?>

    <script>
        setTimeout(function () {
            window.location.href = 'index.php';
        }, 3000);
    </script>
</div>
</body>
</html>
