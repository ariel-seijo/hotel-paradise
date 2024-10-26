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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../ESTILOS/turnos-estilo.css">
    <style>
        /* Colores pastel */
        .bg-disponible {
            background-color: greenyellow;
            /* Verde pastel */
        }

        .bg-ocupado {
            background-color: pink;
            /* Rojo pastel */
        }

        /* Altura personalizada para las filas de cupos */
        .row {
            min-height: 30px;
            /* Altura mínima */
        }

        /* Margen entre filas */
        .border-bottom {
            border-bottom: 1px solid black;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <!-- Button trigger modal -->

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Realizar Reserva</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="reservaForm">
                        <input type="hidden" id="actividadId" value="<?php echo htmlspecialchars($actividadId); ?>"> <!-- ID de actividad -->
                        <div class="mb-3">
                            <label for="dni" class="form-label">DNI</label>
                            <input type="text" class="form-control" id="dni" required>
                            <button type="button" class="btn btn-secondary mt-2" id="btnVerificar">Verificar</button>
                            <div class="text-danger mt-2" id="mensajeError" style="display: none;">NO SE HA ENCONTRADO EL DNI</div>
                        </div>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="correo" readonly>
                        </div>
                        <hr>
                        <h5>DATOS DE LA RESERVA</h5>
                        <div class="mb-3">
                            <label for="actividad" class="form-label">Nombre de la Actividad</label>
                            <input type="text" class="form-control" id="actividad" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="horario" class="form-label">Horario del Turno</label>
                            <input type="text" class="form-control" id="horario" readonly>
                        </div>
                        <input type="hidden" id="cupoId" value=""> <!-- ID del cupo -->
                        <button type="submit" class="btn btn-primary d-none" id="btnGuardarReserva">Guardar Reserva</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Al presionar el botón de verificar
        document.getElementById('btnVerificar').addEventListener('click', function() {
            var dni = document.getElementById('dni').value;
            var mensajeError = document.getElementById('mensajeError');
            var btnGuardarReserva = document.getElementById('btnGuardarReserva'); // Obtiene el botón

            // Limpia el mensaje de error
            mensajeError.style.display = 'none';
            btnGuardarReserva.classList.add('d-none'); // Oculta el botón al inicio

            // Verifica el DNI en la base de datos
            fetch('../SCRIPT/verificar-dni.php?dni=' + dni)
                .then(response => response.json())
                .then(data => {
                    if (data.encontrado) {
                        // Autocompletar campos
                        document.getElementById('nombre').value = data.nombre;
                        document.getElementById('correo').value = data.correo;
                        btnGuardarReserva.classList.remove('d-none'); // Muestra el botón si se encuentra el DNI
                    } else {
                        mensajeError.style.display = 'block';
                    }
                });
        });

        // Al abrir el modal, autocompletar los campos de actividad, horario y cupo
        $('#exampleModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Botón que abrió el modal
            var actividad = button.data('actividad'); // Obtener el nombre de la actividad
            var horario = button.data('horario'); // Obtener el horario del turno
            var cupoId = button.data('cupo-id'); // Obtener el ID del cupo

            // Asignar los valores a los campos correspondientes
            document.getElementById('actividad').value = actividad;
            document.getElementById('horario').value = horario;

            // Guardar el ID del cupo en un campo oculto
            document.getElementById('cupoId').value = cupoId;
        });


        // Confirmación al guardar la reserva
        document.getElementById('reservaForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevenir el envío inmediato del formulario
            if (confirm('¿Está seguro de que desea guardar esta reserva?')) {
                // Preparar datos para enviar
                var datosReserva = new FormData();
                datosReserva.append('huesped_dni', document.getElementById('dni').value);
                datosReserva.append('actividad_id', document.getElementById('actividadId').value);
                datosReserva.append('cupo_id', document.getElementById('cupoId').value); // Usar el ID del cupo

                // Enviar datos a guardar-reserva.php
                fetch('../SCRIPT/guardar-reserva.php', {
                        method: 'POST',
                        body: datosReserva
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            location.reload();
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Ocurrió un error al guardar la reserva.');
                    });
            }
        });
    </script>

    <div class="container contenedor-principal">
        <div class="row">
            <div class="col-3 seccion-info">
                <img src="../IMAGENES/actividad-gimnasio.jpg" class="imagen-actividad" alt="Imagen de la actividad">
                <p class="h3 text-center"><?php echo htmlspecialchars($actividad['nombre']); ?></p>
                <p class="h6 text-center"><?php echo htmlspecialchars($actividad['descripcion']); ?></p>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Días: <?php echo htmlspecialchars($actividad['dias']); ?></li>
                    <li class="list-group-item">Horario de Inicio: <?php echo htmlspecialchars($actividad['horario_inicio']); ?></li>
                    <li class="list-group-item">Horario de Cierre: <?php echo htmlspecialchars($actividad['horario_cierre']); ?></li>
                    <li class="list-group-item">Formato: <?php echo htmlspecialchars($actividad['formato']); ?></li>
                    <li class="list-group-item">Capacidad del turno: <?php echo htmlspecialchars($actividad['capacidad_turno']); ?> personas</li>
                    <li class="list-group-item">Cantidad de turnos: <?php echo htmlspecialchars($actividad['cantidad_turnos']); ?></li>
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
    <!-- Modal para Confirmar Eliminación -->
    <div class="modal fade" id="eliminarModal" tabindex="-1" aria-labelledby="eliminarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eliminarModalLabel">Eliminar Reserva</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Está seguro de que desea eliminar esta reserva?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Variables para almacenar datos de la reserva a eliminar
        let huespedDniEliminar, cupoIdEliminar;

        // Al abrir el modal de eliminar, asignar los datos
        $('#eliminarModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            huespedDniEliminar = button.data('huesped-dni');
            cupoIdEliminar = button.data('cupo-id');
        });

        // Al presionar el botón de confirmar eliminación
        document.getElementById('btnConfirmarEliminar').addEventListener('click', function() {
            // Preparar datos para enviar
            var datosEliminar = new FormData();
            datosEliminar.append('huesped_dni', huespedDniEliminar);
            datosEliminar.append('cupo_id', cupoIdEliminar);

            // Enviar datos a eliminar-reserva.php
            fetch('../SCRIPT/eliminar-reserva.php', {
                    method: 'POST',
                    body: datosEliminar
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        // Opcional: Refrescar la página o actualizar la interfaz
                        location.reload(); // Esto recargará la página
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Ocurrió un error al eliminar la reserva.');
                });

            // Cerrar el modal
            $('#eliminarModal').modal('hide');
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>