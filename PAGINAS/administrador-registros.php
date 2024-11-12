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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
                <a class="btn btn-primary w-100 btnPanel active" href="administrador-registros.php" role="button">Registro de turnos</a>
            </div>
            <div class="col-9 seccion-elegida">
                <h1 class="display-1 text-center">REGISTROS DE TURNOS</h1>
                <?php include '../SCRIPT/generar_registros.php' ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
        /* Estilos de los botones */
        .btn-primary {
            color: black;
            background-color: #62bfbd;
            border: 2px solid #34a09e;
        }

        .btn-primary:focus,
        .btn-primary:hover,
        .btn-primary:active {
            background-color: #34a09e;
            border: 2px solid #34a09e;
        }

        .btn-warning {
            color: black;
            background-color: #4bbbf2;
            border: 2px solid #2aa9e8;
        }

        .btn-warning:focus,
        .btn-warning:hover,
        .btn-warning:active {
            color: white;
            background-color: #2aa9e8;
            border: 2px solid #2aa9e8;
        }

        .btn-danger {
            color: black;
            background-color: #ffb5ba;
            border: 2px solid #f36f78;
        }

        .btn-danger:focus,
        .btn-danger:hover,
        .btn-danger:active {
            background-color: #f36f78;
            border: 2px solid #f36f78;
        }

        .btn-secondary {
            color: black;
            background-color: #4bbbf2;
            border: 2px solid #2aa9e8;
        }

        .btn-secondary:focus,
        .btn-secondary:hover,
        .btn-secondary:active {
            background-color: #2aa9e8;
            border: 2px solid #2aa9e8;
        }

        .btn-success {
            color: black;
            background-color: #62bfbd;
            border: 2px solid #34a09e;
        }

        .btn-success:focus,
        .btn-success:hover,
        .btn-success:active {
            background-color: #34a09e;
            border: 2px solid #34a09e;
        }

        .modal-header {
            background-color: #62bfbd;
        }

        .table {
            border: 2px solid #34a09e;
            /* Borde externo */
            border-collapse: collapse;
            /* Colapsa los bordes */
            width: 100%;
        }

        td,
        th {
            border: none;
            /* Sin borde interno */
            padding: 10px;
            text-align: left;
        }

        .modal-footer {
            background-color: #b8f4f3;
        }
    </style>
</body>

</html>