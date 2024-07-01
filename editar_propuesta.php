<?php
session_start();

// Variables de conexión a la base de datos
$hostname = "localhost";
$username = "id22374583_carproe3t";
$password = "carpro-E3T";
$database = "id22374583_proyecto";
$port = 21;

// Obtener el ID de la propuesta desde la URL
$id = isset($_GET['id']) ? $_GET['id'] : null;
if ($id === null) {
    die("ID no proporcionado.");
}

// Conexión a la base de datos
$conn = new mysqli($hostname, $username, $password, $database, $port);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Procesar la actualización o eliminación si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['accion']) && $_POST['accion'] == 'Eliminar Propuesta') {
        $sql = "DELETE FROM proyectos WHERE id = $id";
    } else {
        $nombreProyecto = mysqli_real_escape_string($conn, $_POST['nombreProyecto']);
        $descripcion = mysqli_real_escape_string($conn, $_POST['descripcion']);
        $numeroEstudiantes = mysqli_real_escape_string($conn, $_POST['numeroEstudiantes']);
        $emailProfesor = mysqli_real_escape_string($conn, $_POST['emailProfesor']);
        $datosad = mysqli_real_escape_string($conn, $_POST['datosad']);
        $disponible = isset($_POST['disponible']) ? 1 : 0;

        $sql = "UPDATE proyectos SET 
                nombre_proyecto = '$nombreProyecto', 
                descripcion = '$descripcion', 
                numero_estudiantes = '$numeroEstudiantes', 
                email_profesor = '$emailProfesor', 
                datosad = '$datosad', 
                disponible = $disponible 
                WHERE id = $id";
    }

    if ($conn->query($sql) === TRUE) {
        echo $sql == "DELETE FROM proyectos WHERE id = $id" ? "Propuesta eliminada con éxito." : "Propuesta actualizada con éxito.";
        echo "<script>setTimeout(function() { window.location.href = 'index.php'; }, 3000);</script>";
    } else {
        echo "Error: " . $conn->error;
    }
    $conn->close();
    exit;
}

// Cargar y mostrar el formulario de edición
$sql = "SELECT * FROM proyectos WHERE id = $id";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Propuesta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 50px;
            background-color: #f2f2f2;
        }

        .container {
            width: 80%;
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .boton {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            margin: 10px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .boton:hover {
            background-color: #45a049;
        }

        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .input-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .input-group input, .input-group textarea {
            width: calc(100% - 22px);
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .input-group textarea {
            height: 100px;
        }
    </style>
</head>
<body>

<div class="container">
    <?php
    if ($result && $row = $result->fetch_assoc()) {
        echo "<h1>Editar Propuesta</h1>";
        echo "<form action='editar_propuesta.php?id=$id' method='post'>";
        echo "<div class='input-group'>";
        echo "    <label for='nombreProyecto'>Nombre del Proyecto:</label>";
        echo "    <input type='text' id='nombreProyecto' name='nombreProyecto' value='" . htmlspecialchars($row["nombre_proyecto"]) . "' required>";
        echo "</div>";
        echo "<div class='input-group'>";
        echo "    <label for='descripcion'>Descripción:</label>";
        echo "    <textarea id='descripcion' name='descripcion' required>" . htmlspecialchars($row["descripcion"]) . "</textarea>";
        echo "</div>";
        echo "<div class='input-group'>";
        echo "    <label for='numeroEstudiantes'>Número de Estudiantes:</label>";
        echo "    <input type='number' id='numeroEstudiantes' name='numeroEstudiantes' value='" . htmlspecialchars($row["numero_estudiantes"]) . "' required>";
        echo "</div>";
        echo "<div class='input-group'>";
        echo "    <label for='emailProfesor'>Email del Profesor:</label>";
        echo "    <input type='email' id='emailProfesor' name='emailProfesor' value='" . htmlspecialchars($row["email_profesor"]) . "' required>";
        echo "</div>";
        echo "<div class='input-group'>";
        echo "    <label for='datosad'>Datos adicionales de contacto:</label>";
        echo "    <textarea id='datosad' name='datosad' required>" . htmlspecialchars($row["datosad"]) . "</textarea>";
        echo "</div>";
        echo "<div class='input-group'>";
        echo "    <label for='disponible'>Disponible:</label>";
        echo "    <input type='checkbox' id='disponible' name='disponible'" . ($row['disponible'] ? " checked" : "") . ">";
        echo "</div>";
        echo "<input type='submit' value='Guardar Cambios' class='boton'>";
        echo "<input type='submit' name='accion' value='Eliminar Propuesta' class='boton' onclick='return confirm(\"¿Estás seguro de que deseas eliminar esta propuesta?\");'>";
        echo "</form>";
    } else {
        echo "<p>Propuesta no encontrada.</p>";
    }
    ?>
</div>

</body>
</html>
