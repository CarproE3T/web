<!DOCTYPE html>
<html>
<head>
    <title>Opciones del proponedor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 100px;
        }

        .boton {
            background-color: green;
            color: white;
            padding: 10px 20px;
            margin: 10px 0;
            border: none;
            cursor: pointer;
            width: 150px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .lista-propuestas {
        list-style-type: none; /* Elimina las viñetas predeterminadas */
        padding: 0;
        text-align: center;
        display: inline-block; /* Centra la lista en la página */
    }
    .lista-propuestas li {
        margin-bottom: 10px; /* Espacio entre elementos de la lista */
    }
    .lista-propuestas a {
        color: black;
        text-decoration: none; /* Elimina el subrayado de los enlaces */
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
<?php
session_start();

// Variables de conexión a la base de datos
$hostname = "127.0.0.1";
$port = 3307;
$username = "root";
$password = "4241640";
$database = "proyecto";

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

if ($result && $row = $result->fetch_assoc()) {
    echo "<h1>Editar Propuesta</h1>";
    echo "<form action='editar_propuesta.php?id=$id' method='post'>";
    echo "    <label for='nombreProyecto'>Nombre del Proyecto:</label><br>";
    echo "    <input type='text' id='nombreProyecto' name='nombreProyecto' value='" . htmlspecialchars($row["nombre_proyecto"]) . "' required><br>";
    echo "    <label for='descripcion'>Descripción:</label><br>";
    echo "    <textarea id='descripcion' name='descripcion' required>" . htmlspecialchars($row["descripcion"]) . "</textarea><br>";
    echo "    <label for='numeroEstudiantes'>Número de Estudiantes:</label><br>";
    echo "    <input type='number' id='numeroEstudiantes' name='numeroEstudiantes' value='" . htmlspecialchars($row["numero_estudiantes"]) . "' required><br>"; 
    echo "    <label for='emailProfesor'>Email del Profesor:</label><br>";
    echo "    <input type='email' id='emailProfesor' name='emailProfesor' value='" . htmlspecialchars($row["email_profesor"]) . "' required><br>";
    echo "    <label for='datosad'>Datos adicionales de contacto:</label><br>";
    echo "    <textarea id='datosad' name='datosad' required>" . htmlspecialchars($row["datosad"]) . "</textarea><br>";
    echo "    <label for='disponible'>Disponible:</label><br>";
    echo "    <input type='checkbox' id='disponible' name='disponible'" . ($row['disponible'] ? " checked" : "") . "><br>";
    echo "    <input type='submit' value='Guardar Cambios' class='boton'>";
    echo "    <input type='submit' name='accion' value='Eliminar Propuesta' class='boton' onclick='return confirm(\"¿Estás seguro de que deseas eliminar esta propuesta?\");'>";
    echo "</form>";
} else {
    echo "Propuesta no encontrada.";
}

$conn->close();
?>

</body>
</html>