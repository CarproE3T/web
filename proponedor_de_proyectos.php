<?php
session_start();
$usuarioValido = "admin";
$claveValida = "admin123";
$mensajeError = "";
$accion = isset($_GET['accion']) ? $_GET['accion'] : '';
$sesionIniciada = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['usuario']) && isset($_POST['clave'])) {
        $usuario = htmlspecialchars($_POST['usuario']);
        $clave = htmlspecialchars($_POST['clave']);

        if ($usuario === $usuarioValido && $clave === $claveValida) {
            $_SESSION['sesionIniciada'] = true;
            $sesionIniciada = true;
        } else {
            $mensajeError = "Usuario o contraseña incorrectos.";
        }
    }
} elseif (isset($_SESSION['sesionIniciada']) && $_SESSION['sesionIniciada']) {
    $sesionIniciada = true;
}

function mostrarFormularioLogin($mensajeError) {
    echo "<!DOCTYPE html>";
    echo "<html>";
    echo "<head>";
    echo "<title>Iniciar Sesión</title>";
    echo "<style>";
    echo "body { font-family: Arial, sans-serif; text-align: center; padding-top: 50px; background-color: #f0f0f0; }";
    echo "h1 { color: #333; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6); }";
    echo "form { display: inline-block; }";
    echo ".boton { background-color: green; color: white; padding: 10px 20px; margin: 10px 0; border: none; cursor: pointer; width: 150px; display: block; margin-left: auto; margin-right: auto; }";
    echo "input[type='text'], input[type='password'] { margin: 10px 0; padding: 10px; width: 300px; }";
    echo ".error { color: red; }";
    echo "</style>";
    echo "</head>";
    echo "<body>";
    echo "<h1>Iniciar Sesión</h1>";
    if ($mensajeError != "") {
        echo "<p class='error'>$mensajeError</p>";
    }
    echo "<form method='post'>";
    echo "<input type='text' name='usuario' placeholder='Usuario'><br>";
    echo "<input type='password' name='clave' placeholder='Contraseña'><br>";
    echo "<input type='submit' value='Iniciar Sesión' class='boton'>";
    echo "</form>";
    echo "</body>";
    echo "</html>";
}

if (!$sesionIniciada) {
    mostrarFormularioLogin($mensajeError);
    exit;
}

function mostrarOpciones() {
    echo "<h1>Opciones del proponente</h1>";
    echo "<a href='?accion=crear' class='boton'>Crear propuesta</a>";
    echo "<a href='?accion=editar' class='boton'>Editar propuesta</a>";
    echo "<a href='?accion=ver' class='boton'>Ver disponibilidad de propuesta</a>";
    echo "<a href='index.php' class='boton'>Volver a la Página de Inicio</a>";
}

function mostrarCrearPropuesta() {
    echo "<h1>Crear Propuesta</h1>";
    echo "<form action='procesar_registro.php' method='POST'>";
    echo "<input type='text' name='nombreProyecto' placeholder='Nombre del Proyecto' required><br>";
    echo "<textarea name='descripcion' placeholder='Descripción del proyecto' required></textarea><br>";
    echo "<input type='number' name='numeroEstudiantes' placeholder='Número de estudiantes' required><br>";
    echo "<input type='email' name='emailProfesor' placeholder='Email del profesor' required><br>";
    echo "<input type='hidden' name='fecha' value='" . date("Y-m-d") . "'>";
    echo "<label for='disponible'>Disponible:</label>";
    echo "<input type='checkbox' id='disponible' name='disponible' value='1' checked><br>";
    echo "<textarea name='datosad' placeholder='Datos de contacto adicional(Telefono, Oficina en la universiad)' required></textarea><br>";
    echo "<input type='submit' value='Registrar' class='boton'>";
    echo "</form>";
    echo "<a href='index.php' class='boton'>Cancelar</a>";
}

function mostrarEditarPropuesta() {
    $hostname = "viaduct.proxy.rlwy.net";
    $username = "root";
    $password = "MFdYmSkvfMkCeWhPjQuXfHGwgMzPcZRb";
    $database = "railway";
    $port = 49066;
    $conn = new mysqli($hostname, $username, $password, $database, $port);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $sql = "SELECT id, nombre_proyecto FROM proyectos";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h1>Editar Propuestas</h1>";
        echo "<ul class='lista-propuestas'>";
        while ($row = $result->fetch_assoc()) {
            echo "<li><a href='editar_propuesta.php?id=" . $row["id"] . "' class='boton'>" . htmlspecialchars($row["nombre_proyecto"]) . "</a></li>";
        }
        echo "</ul>";
        echo "<a href='index.php' class='boton'>Cancelar</a>";
    } else {
        echo "No hay propuestas disponibles para editar.";
        echo "<a href='index.php' class='boton'>Cancelar</a>";
    }

    $conn->close();
}

function mostrarVerDisponibilidad() {
    $hostname = "viaduct.proxy.rlwy.net";
    $username = "root";
    $password = "MFdYmSkvfMkCeWhPjQuXfHGwgMzPcZRb";
    $database = "railway";
    $port = 49066;
    $conn = new mysqli($hostname, $username, $password, $database, $port);

    $sql = "SELECT nombre_proyecto, disponible FROM proyectos";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h1>Disponibilidad de Propuestas</h1>";
        echo "<table class='disponibilidad'>";
        echo "<tr><th>Nombre del Proyecto</th><th>Disponibilidad</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["nombre_proyecto"]) . "</td>";
            echo "<td>" . ($row["disponible"] ? "Disponible" : "No Disponible") . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No hay propuestas disponibles.";
    }
    echo "<a href='index.php' class='boton'>Volver a la Página de Inicio</a>";
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Opciones del Proponente</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 50px;
            background-color: #f0f0f0;
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
            font-size: 1em;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .boton:hover {
            background-color: darkgreen;
            transform: translateY(-5px);
        }
        input[type="text"], input[type="password"], input[type="email"], input[type="number"], textarea {
            margin: 10px 0;
            padding: 10px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }
        textarea {
            height: 100px;
        }
        .lista-propuestas {
            list-style-type: none;
            padding: 0;
            text-align: center;
            display: inline-block;
        }
        .lista-propuestas li {
            margin-bottom: 10px;
        }
        .lista-propuestas a {
            color: white;
            text-decoration: none;
            background-color: #4CAF50;
            padding: 10px 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
            transition: background-color 0.3s ease, transform 0.3s ease;
            display: block;
        }
        .lista-propuestas a:hover {
            background-color: darkgreen;
            transform: translateY(-5px);
        }
        table.disponibilidad {
            margin-left: auto;
            margin-right: auto;
            border-collapse: collapse;
            width: 80%;
        }
        table.disponibilidad th, table.disponibilidad td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        table.disponibilidad th {
            background-color: #4CAF50;
            color: white;
        }
        table.disponibilidad tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        table.disponibilidad tr:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <?php
    if ($sesionIniciada) {
        switch ($accion) {
            case 'crear':
                mostrarCrearPropuesta();
                break;
            case 'editar':
                mostrarEditarPropuesta();
                break;
            case 'ver':
                mostrarVerDisponibilidad();
                break;
            default:
                mostrarOpciones();
                break;
        }
    }
    ?>
</body>
</html>
