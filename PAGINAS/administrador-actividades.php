<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMINISTRACIÓN - Hotel Paradise</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../ESTILOS/panel-administrador-estilo.css">
    <link rel="stylesheet" href="../ESTILOS/administrador-actividades-estilo.css">
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div class="container contenedor-principal">
        <div class="row">
            <div class="col-3 h-100vh seccion-panel gap-2">
                <h1 class="display-6 text-center">PANEL DE ADMINISTRACIÓN</h1>
                <a class="btn btn-primary w-100 btnPanel" href="#" role="button">Actividades</a>
                <a class="btn btn-primary w-100 btnPanel" href="#" role="button">Usuarios</a>
                <a class="btn btn-primary w-100 btnPanel" href="#" role="button">Registro de turnos</a>
            </div>
            <div class="col-9 seccion-elegida">
                <h1 class="display-1 text-center">PANEL DE ACTIVIDADES</h1>
                <div class="contenedor-actividades d-flex flex-wrap">
                    <?php include '../SCRIPT/generar-actividad.php'; ?>
                    <a class="btn btn-light btn-agregar-actividad" href="agregar-actividad.php" role="button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
