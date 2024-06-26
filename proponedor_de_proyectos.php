<!DOCTYPE html>
<html>
<head>
    <title>Opciones del proponente</title>
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
        table.disponibilidad {
            margin-left: auto;
            margin-right: auto;
            border-collapse: collapse; /* Elimina los espacios entre las celdas */
            width: 80%; /* Ancho de la tabla */
        }
        table.disponibilidad th, table.disponibilidad td {
            border: 1px solid #ddd; /* Bordes para las celdas */
            padding: 8px; /* Espaciado interno */
        }
        table.disponibilidad th {
            background-color: #4CAF50; /* Color de fondo para encabezados */
            color: white; /* Color de texto para encabezados */
        }
        table.disponibilidad tr:nth-child(even) {
            background-color: #f2f2f2; /* Color de fondo para filas pares */
        }
        table.disponibilidad tr:hover {
            background-color: #ddd; /* Color de fondo al pasar el mouse */
        }
    </style>
</head>
<body>
    <?php
    session_start();
    $usuarioValido = "admin";
    $claveValida = "admin123";
    $mensajeError = "";
    $accion = isset($_GET['accion']) ? $_GET['accion'] : '';
    $sesionIniciada = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['usuario']) && isset($_POST['clave'])) {
            $usuario = $_POST['usuario'];
            $clave = $_POST['clave'];

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
    function mostrarOpciones() {
        // Lógica para mostrar las opciones
        echo "<h1>Opciones del proponente</h1>";
        echo "<a href='?accion=crear' class='boton'>Crear propuesta</a>";
        echo "<a href='?accion=editar' class='boton'>Editar propuesta</a>";
        echo "<a href='?accion=ver' class='boton'>Ver disponibilidad de propuesta</a>";
        echo "<a href='index.php' class='boton'>Volver a la Página de Inicio</a>";
    }

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
    } else {
        mostrarFormularioLogin($mensajeError);
    }

    function mostrarCrearPropuesta() {
        echo "<h1>Crear Propuesta</h1>";
        echo "<form action='procesar_registro.php' method='POST'>";
        echo "    <input type='text' name='nombreProyecto' placeholder='Nombre del Proyecto' required><br>";
        echo "    <textarea name='descripcion' placeholder='Descripción del proyecto' required></textarea><br>";
        echo "    <input type='number' name='numeroEstudiantes' placeholder='Número de estudiantes' required><br>";
        echo "    <input type='email' name='emailProfesor' placeholder='Email del profesor' required><br>";
        echo "    <input type='hidden' name='fecha' value='" . date("Y-m-d") . "'>";
        echo "    <label for='disponible'>Disponible:</label>";
        echo "    <input type='checkbox' id='disponible' name='disponible' value='1' checked><br>";
        echo "    <textarea name='datosad' placeholder='Datos de contacto adicional(Telefono, Oficina en la universiad)' required></textarea><br>";
        echo "    <input type='submit' value='Registrar' class='boton'>";
        echo "</form>";
        echo "<a href='index.php' class='boton'>Cancelar</a>";
    }
    
    


    
        function mostrarEditarPropuesta() {

            // Conexión a la base de datos
            // (Asegúrate de que estas variables estén definidas)
            $hostname = "127.0.0.1";
            $port = 3307;
            $username = "root";
            $password = "4241640";
            $database = "proyecto";
            $conn = new mysqli($hostname, $username, $password, $database, $port);
        
            if ($conn->connect_error) {
                die("Conexión fallida: " . $conn->connect_error);
            }
        
            $sql = "SELECT id, nombre_proyecto FROM proyectos";
            $result = $conn->query($sql);
        
            if ($result->num_rows > 0) {
                echo "<h1>Editar Propuestas</h1>";
                echo "<ul class='lista-propuestas'>";
                echo "<ul>";
                while($row = $result->fetch_assoc()) {
                    echo "<li><a href='editar_propuesta.php?id=" . $row["id"] . "'>" . htmlspecialchars($row["nombre_proyecto"]) . "</a></li>";
                }
                echo "</ul>";
                echo "<a href='index.php' class='boton'>Cancelar</a>";
            } else {
                echo "No hay propuestas disponibles para editar.";
                echo "<a href='index.php' class='boton'>Cancelar</a>"; // Enlace para cancelar y volver a la página de inicio
            }
        
            $conn->close();
            
        }
        

    function mostrarVerDisponibilidad() {
        // Lógica para mostrar la disponibilidad de propuestas
        
        
            // Conexión a la base de datos
            // ...
            $hostname = "127.0.0.1";
            $port = 3307;
            $username = "root";
            $password = "4241640";
            $database = "proyecto";
            $conn = new mysqli($hostname, $username, $password, $database, $port);
        
            $sql = "SELECT id, nombre_proyecto, disponible FROM proyectos";
            $result = $conn->query($sql);
        
            if ($result->num_rows > 0) {
                echo "<h1>Disponibilidad de Propuestas</h1>";
                echo "<table class='disponibilidad'>";
        echo "<tr><th>Nombre del Proyecto</th><th>Disponibilidad</th></tr>";
        while($row = $result->fetch_assoc()) {
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
        
        
    

    function mostrarFormularioLogin($mensajeError) {
        echo "<h1>Iniciar Sesión</h1>";
        if ($mensajeError != "") {
            echo "<p class='error'>$mensajeError</p>";
        }
        echo "<form method='post'>";
        echo "<input type='text' name='usuario' placeholder='Usuario'><br>";
        echo "<input type='password' name='clave' placeholder='Contraseña'><br>";
        echo "<input type='submit' value='Iniciar Sesión' class='boton'>";
        echo "</form>";
    }
    ?>
</body>
</html>
