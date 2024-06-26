<!DOCTYPE html>
<html>
<head>
    <title>Cartera de Trabajos de Grado</title>
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
            margin: 80px 0;
            border: none;
            cursor: pointer;
            width: 800px; /* Ancho fijo para los botones */
            display: block; /* Asegura que cada botón esté en su propia línea */
            margin-left: auto;
            margin-right: auto;
        }

        h1 {
            color: black;
        }
    </style>
</head>
<body>

<h1>Ingenieria Eléctrica</h1>

<form action="estudiante.php" method="post">
    <input type="submit" value="Estudiante" class="boton">
</form>

<form action="proponedor_de_proyectos.php" method="post">
    <input type="submit" value="Proponente de Proyectos" class="boton">
</form>

</body>
</html>
