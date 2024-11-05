<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACTIVIDADES - Hotel Paradise</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../ESTILOS/actividades-estilo.css">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container contenedor-principal">
        <h1 class="display-1 text-center"><b>PANEL<b> DE ACTIVIDADES</h1>
        <div class="contenedor-actividades d-flex flex-wrap">
            <?php include '../SCRIPT/generar_actividad.php'; ?>
        </div>
    </div>


<style>
   
    .display-1{
        
        font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;    
    }

    .card {
        max-width: 250px;
        /* Tamaño máximo para la tarjeta */
        height: 250px;
        /* Altura fija para todas las tarjetas */
        margin: auto;
        /* Centrar la tarjeta */
        background-color:white;
    }

    .card-img-top {
        min-height: 170px;
        /* Altura fija para la imagen */
        width: 100%;
        /* Ancho completo */
        object-fit: cover;
        /* Mantener proporción y cubrir el área */
        transition:1s;
    }

    .card-img-top:hover{
        min-height: 200px;
        transition:1s;
    }

    .card-body {
        color:#4bbbf2;
        display: flex;
        flex-direction: column;
        justify-content: center;
        /* Centrar el contenido verticalmente */
        height: 100%;
        /* Hacer que el cuerpo de la tarjeta ocupe todo el alto disponible */
    }
    
    .contenedor-principal {

        background-color: #fae5da;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;

    }


    body {
    background-color:#fae5da;
    }

</style>







    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>