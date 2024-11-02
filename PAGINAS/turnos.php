<?php
// Incluir archivo de conexión a la base de datos
include '../SCRIPT/conexion.php';

// Verificar que se haya recibido la ID de la actividad
if (isset($_GET['id'])) {
    $actividadId = intval($_GET['id']); // Convertir a entero para evitar inyección SQL

    // Consulta para obtener la información de la actividad
    $query = "SELECT nombre, descripcion, horario_inicio, horario_cierre, dia_inicio, dia_fin, formato, capacidad_turno, duracion, imagen 
              FROM actividades 
              WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $actividadId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontró la actividad
    if ($result->num_rows > 0) {
        $actividad = $result->fetch_assoc();
    } else {
        echo "<p>Actividad no encontrada.</p>";
        exit;
    }
} else {
    echo "<p>ID de actividad no proporcionada.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMINISTRACIÓN - Hotel Paradise</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../ESTILOS/panel-administrador-estilo.css">
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div class="container contenedor-principal">
        <div class="container mt-3">
            <button onclick="window.history.back();" class="btn btn-secondary">Volver</button>
        </div>
        <div class="row">
            <div class="col-3 h-100vh seccion-panel gap-2">
                <h1 class="display-6 text-center">ACTIVIDAD</h1>
                <style>
                    .actividad-detalle {
                        max-width: 600px;
                        margin: auto;
                    }

                    .actividad-imagen {
                        width: 100%;
                        max-height: 300px;
                        object-fit: cover;
                    }
                </style>
                <div class="container mt-5">
                    <div class="actividad-detalle">
                        <h1><?php echo htmlspecialchars($actividad['nombre']); ?></h1>

                        <?php if ($actividad['imagen']): ?>
                            <img src="<?php echo htmlspecialchars($actividad['imagen']); ?>" alt="Imagen de la actividad" class="actividad-imagen mb-3">
                        <?php else: ?>
                            <p><i>Sin imagen disponible</i></p>
                        <?php endif; ?>

                        <p><strong>Descripción:</strong> <?php echo htmlspecialchars($actividad['descripcion']); ?></p>
                        <p><strong>Horario:</strong> <?php echo htmlspecialchars($actividad['horario_inicio']) . " - " . htmlspecialchars($actividad['horario_cierre']); ?></p>
                        <p><strong>Días:</strong> <?php echo htmlspecialchars($actividad['dia_inicio']) . " - " . htmlspecialchars($actividad['dia_fin']); ?></p>
                        <p><strong>Formato:</strong> <?php echo htmlspecialchars($actividad['formato']); ?></p>
                        <p><strong>Capacidad por turno:</strong> <?php echo htmlspecialchars($actividad['capacidad_turno']); ?> personas</p>
                        <p><strong>Duración del turno:</strong> <?php echo htmlspecialchars($actividad['duracion']); ?> minutos</p>
                    </div>
                </div>
            </div>
            <div class="col-9 seccion-elegida">
                <h2 class="display-6 text-center">AGENDA DE TURNOS</h1>

                    <?php include '../SCRIPT/generar_turnos.php' ?>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>