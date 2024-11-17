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
    <link rel="icon" href="../IMAGENES/paradise-icono.png" type="image/png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $actividad['nombre']?> | Turnos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <?php include 'navbar.php'; ?>
    <style>
        body {
            background-color: #baddd6;
            color: #333;
            font-weight: normal;
        }

        .contenedor-formulario-fecha {
            display: flex;
            justify-content: space-around;
            align-items: end;
        }


        .contenedor-fecha .form-group {
            width: 80%;
        }

        .contenedor-fecha .form-group input {
            border: 2px solid #62bfbd;
        }

        .contenedor-principal {
            background-color: #baddd6;
            padding: 20px;
        }

        .contenedor-secciones {
            display: flex;
            justify-content: space-evenly;
            background-color: #baddd6;
        }

        .seccion-panel,
        .seccion-elegida {
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .info-cuadro {
            background-color: #62bfbd4a;
            padding: 15px;
            margin-bottom: 10px;
            color: #4bbbf2;
            border-radius: 5px;
            transition: background-color 0.4s ease;
        }

        .info-cuadro:hover {
            background-color: #62bfbd;
            cursor: pointer;
        }

        .info-cuadro strong {
            color: black;
            font-weight: bold;
        }

        .info-cuadro span {
            color: #333;
        }

        .actividad-imagen {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 8px;
        }

        .volver .btn {
            width: 185px;
        }

        .volver .btn:hover {
            color: white;
            background-color: #34a09e;
        }

        .actividad-nombre {
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
            font-size: 2.5rem;
            color: #62bfbd;
            font-weight: bold;
        }

        .btn-primary {
            background-color: #4bbbf2;
            color: white;
            border: none;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #3aa3d4;
            color: #333;
        }

        .btn-secondary {
            background-color: #62bfbd;
            color: white;
            border: none;
            transition: background-color 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #4bbbf2;
            color: #333;
        }


        .contenedor-formulario-fecha button {
            height: 40px;
            width: 15%;
            background-color: #62bfbd;
        }

        .contenedor-formulario-fecha button:hover {
            color: white;
            background-color: #399e9c;
        }

        .contenedor-formulario-fecha button:active {
            color: white;
            background-color: black;
        }


        .accordion-button:not(.collapsed) {
            background-color: #62bfbd;
        }

        .accordion-button {
            background-color: rgb(179, 238, 237);
        }

        /* Responsividad */
        @media (max-width: 768px) {

            .seccion-panel,
            .seccion-elegida {
                padding: 15px;
            }

            .volver {
                display: flex;
                justify-content: center;
                margin-bottom: 10px;
            }
        }

        @media (max-width: 576px) {
            .info-cuadro {
                flex-direction: column;
                text-align: left;
            }

            h1,
            h2 {
                font-size: 1.5rem;
                font-weight: normal;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid contenedor-principal">
        <div class="row contenedor-secciones">
            <div class="col-lg-3 mx-3 seccion-panel">
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

            <div class="col-lg-8 mx-3 seccion-elegida">
                <div class="row">
                    <div class="col-10">
                        <h2 class="display-6 text-center">AGENDA DE TURNOS</h2>
                    </div>
                    <div class="col-2 volver">
                        <button onclick="window.history.back();" class="btn btn-secondary">Volver</button>
                    </div>
                    <style>
                        .volver {
                            display:flex;
                            align-items: center;
                            justify-content: end;
                        }

                        .col-4 {
                            width: 33%;
                        }
                    </style>
                </row>
                <?php include '../SCRIPT/generar_turnos.php' ?>
            </div>
        </div>
    </div>

    <!-- Scripts de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>