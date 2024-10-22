<?php
// Incluir la conexión a la base de datos
include '../SCRIPT/conexion.php';

// Verificar si se ha enviado un ID de actividad
if (isset($_GET['id'])) {
    $actividadId = intval($_GET['id']); // Asegurarse de que el ID es un número entero

    // Consulta para obtener la actividad
    $stmt = $conn->prepare("SELECT * FROM actividades WHERE id = ?");
    $stmt->bind_param("i", $actividadId);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Verificar si se encontró la actividad
    if ($resultado->num_rows > 0) {
        $actividad = $resultado->fetch_assoc();
    } else {
        // Si no se encuentra la actividad, redirigir o mostrar un mensaje
        echo "Actividad no encontrada.";
        exit();
    }
} else {
    // Si no se proporciona un ID, redirigir o mostrar un mensaje
    echo "No se proporcionó ID de actividad.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TURNOS - Hotel Paradise</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../ESTILOS/turnos-estilo.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container contenedor-principal">
        <div class="row">
            <div class="col-3 seccion-info">
                <img src="../IMAGENES/actividad-gimnasio.jpg" class="imagen-actividad">
                <p class="h3 text-center"><?php echo htmlspecialchars($actividad['nombre']); ?></p>
                <p class="h6 text-center"><?php echo htmlspecialchars($actividad['descripcion']); ?></p>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Días: <?php echo htmlspecialchars($actividad['dias']); ?></li>
                    <li class="list-group-item">Horario: <?php echo htmlspecialchars($actividad['horarios']); ?></li>
                    <li class="list-group-item">Formato: <?php echo $actividad['individual'] ? 'Individual' : 'Grupal'; ?></li>
                    <li class="list-group-item">Capacidad del turno: <?php echo htmlspecialchars($actividad['capacidad']); ?> personas</li>
                    <li class="list-group-item">Cantidad: <?php echo htmlspecialchars($actividad['cantidad']); ?></li>
                    <li class="list-group-item">Duración de turno: <?php echo htmlspecialchars($actividad['duracion']); ?> minutos</li>
                </ul>
            </div>
            <div class="col-9 seccion-agenda">
                <h1 class="display-4 text-center">AGENDA DE TURNOS</h1>
                <div class="accordion" id="accordionPanelsStayOpenExample">
                    <?php include('../SCRIPT/generar-turno.php'); ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
















<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TURNOS - Hotel Paradise</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../ESTILOS/turnos-estilo.css">
</head>
<body> -->
<!-- include 'navbar.php'; -->

<!-- <div class="container contenedor-principal">
        <div class="row"> -->

<!-- SECCION DE INFORMACION DE ACTIVIDAD -->

<!-- <div class="col-3 seccion-info">
                <img src="../IMAGENES/actividad-gimnasio.jpg" class="imagen-actividad">
                <p class="h3 text-center">Nombre Actividad</p>
                <p class="h6 text-center">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Asperiores, atque dolorum. Reiciendis voluptatibus, nihil temporibus id facilis exercitationem quod impedit, qui modi deserunt vitae nam maiores ex soluta cumque? Obcaecati.</p>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Días: Lunes a Viernes</li>
                    <li class="list-group-item">Horario: 09:00 a 18:00</li>
                    <li class="list-group-item">Duración de turno: 30 minutos</li>
                    <li class="list-group-item">Capacidad del turno: 2 personas</li>
                    <li class="list-group-item">Cantidad de turnos: 7</li>
                </ul>
            </div> -->
<!-- </div>
</div> -->


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>


<!-- <div class="accordion-item">
                        <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                            <div class="row">
                                <div class="col-2">
                                    09:00
                                </div>
                                <div class="col-3">
                                    Turno disponible
                                </div>
                                <div class="col-3">
                                    Ocupado: 0 / 0
                                </div>
                                <div class="col-4">
                                </div>
                            </div>
                        </button>
                        </h2>
                        <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-2">
                                        1 / 2
                                    </div>
                                    <div class="col-3">
                                        Ocupado
                                    </div>
                                    <div class="col-3">
                                        Nombre de huésped
                                    </div>
                                    <div class="col-4">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="true" aria-controls="panelsStayOpen-collapseTwo">
                            <div class="row">
                                <div class="col-2">
                                    09:00
                                </div>
                                <div class="col-3">
                                    Turno disponible
                                </div>
                                <div class="col-3">
                                    Ocupado: 0 / 0
                                </div>
                                <div class="col-4">
                                </div>
                            </div>
                        </button>
                        </h2>
                        <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-2">
                                        1 / 2
                                    </div>
                                    <div class="col-3">
                                        Ocupado
                                    </div>
                                    <div class="col-3">
                                        Nombre de huésped
                                    </div>
                                    <div class="col-4">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="true" aria-controls="panelsStayOpen-collapseThree">
                            <div class="row">
                                <div class="col-2">
                                    09:00
                                </div>
                                <div class="col-3">
                                    Turno disponible
                                </div>
                                <div class="col-3">
                                    Ocupado: 0 / 0
                                </div>
                                <div class="col-4">
                                </div>
                            </div>
                        </button>
                        </h2>
                        <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-2">
                                        1 / 2
                                    </div>
                                    <div class="col-3">
                                        Ocupado
                                    </div>
                                    <div class="col-3">
                                        Nombre de huésped
                                    </div>
                                    <div class="col-4">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFour" aria-expanded="true" aria-controls="panelsStayOpen-collapseFour">
                            <div class="row">
                                <div class="col-2">
                                    09:00
                                </div>
                                <div class="col-3">
                                    Turno disponible
                                </div>
                                <div class="col-3">
                                    Ocupado: 0 / 0
                                </div>
                                <div class="col-4">
                                </div>
                            </div>
                        </button>
                        </h2>
                        <div id="panelsStayOpen-collapseFour" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-2">
                                        1 / 2
                                    </div>
                                    <div class="col-3">
                                        Ocupado
                                    </div>
                                    <div class="col-3">
                                        Nombre de huésped
                                    </div>
                                    <div class="col-4">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFive" aria-expanded="true" aria-controls="panelsStayOpen-collapseFive">
                            <div class="row">
                                <div class="col-2">
                                    09:00
                                </div>
                                <div class="col-3">
                                    Turno disponible
                                </div>
                                <div class="col-3">
                                    Ocupado: 0 / 0
                                </div>
                                <div class="col-4">
                                </div>
                            </div>
                        </button>
                        </h2>
                        <div id="panelsStayOpen-collapseFive" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-2">
                                        1 / 2
                                    </div>
                                    <div class="col-3">
                                        Ocupado
                                    </div>
                                    <div class="col-3">
                                        Nombre de huésped
                                    </div>
                                    <div class="col-4">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->