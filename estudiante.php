<?php
session_start();

$usuarioValido = "usuario";
$claveValida = "usuario123";
$sesionIniciada = false;
$mensajeError = "";

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

// Conexión a la base de datos
$hostname = "monorail.proxy.rlwy.net";
$username = "root";
$password = "nvKULvnVnNuzhZZkxEpgIEtqcDpvakFq";
$database = "carpro";
$port = 46038;
$conn = new mysqli($hostname, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

$sql = "SELECT * FROM proyectos";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Estudiante - Visualización de Propuestas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 50px;
            background-color: #f0f0f0;
        }
        h1 {
            color: #333;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
        }
        table {
            margin-left: auto;
            margin-right: auto;
            border-collapse: collapse;
            width: 80%;
            background-color: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
        }
        th {
            background-color: #4CAF50;
            color: white;
            white-space: nowrap;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        .boton {
            background-color: green;
            color: white;
            padding: 10px 20px;
            margin: 20px 0;
            border: none;
            cursor: pointer;
            width: 200px;
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
        input[type="text"], input[type="password"] {
            margin: 10px 0;
            padding: 10px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }
        .error {
            color: red;
        }
        .boton[disabled] {
            background-color: grey;
            cursor: not-allowed;
        }
        .volver {
            background-color: #555;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        function mostrarMensaje(emailLink) {
            Swal.fire({
                title: 'Confirmación',
                text: "El siguiente correo es un modelo que puede seguir para mostrar su interés en el proyecto, pero usted puede modificarlo a su antojo (Recuerde que tiene más información de contacto del proponente del proyecto).",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.open(emailLink, '_blank');
                }
            });
        }
    </script>
</head>
<body>
<?php
if ($result->num_rows > 0) {
    echo "<h1>Propuestas Disponibles</h1>";
    echo "<table>";
    echo "<tr><th>Nombre del Proyecto</th><th>Descripción</th><th>Número de Estudiantes</th><th>Datos Adicionales</th><th>Disponibilidad</th><th>Acción</th></tr>";

    while ($row = $result->fetch_assoc()) {
        $nombreProyecto = htmlspecialchars($row["nombre_proyecto"]);
        $emailProfesor = htmlspecialchars($row["email_profesor"]);
        $asuntoEmail = urlencode("Interés en el proyecto " . $nombreProyecto);
        $cuerpoEmail = urlencode("Hola [Nombre del Profesor o Entidad],\n\nEspero que este mensaje le encuentre bien. Me dirijo a usted con gran entusiasmo para expresar mi interés en el proyecto '" . $nombreProyecto . "'.\n\nSoy estudiante de Ingeniería Eléctrica y, a lo largo de mi carrera, he adquirido habilidades y conocimientos que considero extremadamente valiosos para contribuir de manera significativa a este proyecto. Mi experiencia en [habilidad relevante] me ha preparado para enfrentar desafíos como los que presenta este proyecto.\n\nEstoy convencido de que, al colaborar en este proyecto, no solo podré aplicar mis conocimientos técnicos, sino también aprender y crecer profesionalmente. Estoy deseoso de discutir cómo puedo aportar al éxito de este proyecto y cómo podemos trabajar juntos para lograr resultados sobresalientes.\n\nGracias por considerar mi solicitud. Quedo a la espera de su respuesta y de la oportunidad de colaborar en este emocionante proyecto.\n\nSaludos cordiales,\n\n[Tu Nombre]");
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row["nombre_proyecto"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["descripcion"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["numero_estudiantes"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["datosad"]) . "</td>";
        echo "<td>" . ($row["disponible"] ? "Disponible" : "No Disponible") . "</td>";

        if ($row["disponible"]) {
            $emailLink = "https://mail.google.com/mail/?view=cm&fs=1&to=" . $emailProfesor . "&su=" . $asuntoEmail . "&body=" . $cuerpoEmail;
            echo "<td><button onclick=\"mostrarMensaje('$emailLink')\" class='boton'>Estoy interesado</button></td>";
        } else {
            echo "<td><button class='boton' disabled>No Disponible</button></td>";
        }
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No hay propuestas disponibles.</p>";
}

$conn->close();

echo "<a href='index.php' class='boton volver'>Volver a la Página de Inicio</a>";
?>
</body>
</html>


    
