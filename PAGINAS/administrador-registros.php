<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMINISTRACIÓN - Hotel Paradise</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../ESTILOS/panel-administrador-estilo.css">
    <link rel="stylesheet" href="../ESTILOS/administrador-actividades-estilo.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div class="container contenedor-principal">
        <div class="row">
            <div class="col-3 h-100vh seccion-panel gap-2">
                <h1 class="display-6 text-center">PANEL DE ADMINISTRACIÓN</h1>
                <a class="btn btn-primary w-100 btnPanel" href="administrador-actividades.php" role="button">Actividades</a>
                <a class="btn btn-primary w-100 btnPanel" href="administrador-usuarios.php" role="button">Usuarios</a>
                <a class="btn btn-primary w-100 btnPanel" href="administrador-registros.php" role="button">Registro de turnos</a>
            </div>
            <div class="col-9 seccion-elegida">
                <h1 class="display-1 text-center">REGISTROS</h1>
                <?php include '../SCRIPT/generar_registros.php' ?>
            </div>
        </div>
    </div>

    <style>
            .col-3{
                background-color:#fae5da;
            }
            .col-9, body{
                background-color:white;
            }
            .btnPanel{
                background-color:#4bbbf2;
                transition:.5s;
            }
            .btnPanel:hover{
                background-color: #3aa3d4;
                color:black;
                transition:.5s;
            }
            .display-6{
               
            }


    </style>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>