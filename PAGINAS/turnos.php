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
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Responsive</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        .contenedor-principal {
            background-color: #fae5da;
            padding: 20px;
        }

        .seccion-panel, .seccion-elegida {
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

       
        .info-cuadro {
                background-color: #fae5da;
                padding: 15px;
                margin-bottom: 10px;
                color: #4bbbf2;
                font-weight: bold;
                justify-content: space-between;
                transition:.4s;   
        }
        .info-cuadro:hover{
            background-color:#e7d3c9;
            transition:.4s;
            cursor: pointer;
        }

        .info-cuadro span {
             color: #333;
        }

       
        .actividad-imagen {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
        }

        .volver{
          margin-bottom:10px;
          display:block;
          text-align:right;
        }
        .actividad-nombre{
            margin-bottom:20px;
            display:flex;
            justify-content:center;
            font-size:3rem;
        }
        .btn-primary{
                background-color:#4bbbf2;
                transition:.5s;
            }
        .btn-primary:hover{
                background-color: #3aa3d4;
                color:black;
                transition:.5s;
            }


        @media (max-width: 768px) {
            .seccion-panel, .seccion-elegida {
                padding: 15px;
            }
            .volver{
            display:flex;
            justify-content:center;
            margin-bottom:10px;
        }
        }

       
        @media (max-width: 576px) {
            .info-cuadro {
                flex-direction: column; /* Cambia a columna en pantallas pequeñas */
                text-align: left;
            }

            h1, h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    
    <?php include 'navbar.php'; ?>
    <div class="container-fluid contenedor-principal">
        <div class="row">
            <div class="volver container mt-3">
                <button onclick="window.history.back();" class="btn btn-secondary">Volver</button>
            </div>
            <div class="col-12 col-lg-3 seccion-panel">
                <h1 class="display-6 text-center">ACTIVIDAD</h1>
                
                <div class="actividad-detalle">
                    <h1 class="actividad-nombre"><?php echo htmlspecialchars($actividad['nombre']); ?></h1>

                    <?php if ($actividad['imagen']): ?>
                        <img src="<?php echo htmlspecialchars($actividad['imagen']); ?>" alt="Imagen de la actividad" class="actividad-imagen mb-3">
                    <?php else: ?>
                        <p><i>Sin imagen disponible</i></p>
                    <?php endif; ?>

                    <div class="info-cuadro">
                        <strong>Descripción:</strong> <span><?php echo htmlspecialchars($actividad['descripcion']); ?></span>
                    </div>

                    <div class="info-cuadro">
                        <strong>Horario:</strong> <span><?php echo htmlspecialchars($actividad['horario_inicio']) . " a " . htmlspecialchars($actividad['horario_cierre']); ?></span>
                    </div>

                    <div class="info-cuadro">
                        <strong>Días:</strong> <span><?php echo htmlspecialchars($actividad['dia_inicio']) . " a " . htmlspecialchars($actividad['dia_fin']); ?></span>
                    </div>

                    <div class="info-cuadro">
                        <strong>Formato:</strong> <span><?php echo htmlspecialchars($actividad['formato']); ?></span>
                    </div>

                    <div class="info-cuadro">
                        <strong>Capacidad por turno:</strong> <span><?php echo htmlspecialchars($actividad['capacidad_turno']); ?> personas</span>
                    </div>

                    <div class="info-cuadro">
                        <strong>Duración del turno:</strong> <span><?php echo htmlspecialchars($actividad['duracion']); ?> minutos</span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-9 seccion-elegida">
                <h2 class="display-6 text-center">AGENDA DE TURNOS</h2>
                <?php include '../SCRIPT/generar_turnos.php' ?>
            </div>
        </div>
    </div>

    <!-- Scripts de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
