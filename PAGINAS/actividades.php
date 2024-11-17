<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="../IMAGENES/paradise-icono.png" type="image/png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACTIVIDADES - Hotel Paradise</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../ESTILOS/actividades-estilo.css">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container contenedor-principal">
        <h1 class="display-1 text-center">Panel de Actividades</h1>
        <div class="contenedor-actividades d-flex flex-wrap">
            <?php include '../SCRIPT/generar_actividad.php'; ?>
        </div>
    </div>


    <style>
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.15);
            /* Sombra para dar profundidad */
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: scale(1.02);
            /* Efecto de escala al hacer hover */
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.2);
            /* Sombra más intensa en hover */
        }

        .card-img-top {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            height: 200px;
            object-fit: cover;
        }

        .card-body {
            background-color: #3e99c6;
            /* Color secundario */
            padding: 20px;
            text-align: center;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        .card-title {
            color: white;
            font-size: 1.2rem;
            font-weight: bold;
            margin: 0;
            transition: color 0.3s ease;
        }

        .card:hover .card-title {
            color: #ffb5ba;
            /* Color en hover para el título */
        }

        .card a {
            text-decoration: none;
            color: inherit;
        }

        .card a:hover .card-title {
            color: white;
        }




        .contenedor-principal {

            background-color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;

        }


        body, .container {
            background-color: white;
        }
    </style>







    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>