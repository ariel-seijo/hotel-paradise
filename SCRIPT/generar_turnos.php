<?php
// Incluir archivo de conexión a la base de datos
include '../SCRIPT/conexion.php';

// Verificar que se haya recibido la ID de la actividad
if (isset($_GET['id'])) {
    $actividadId = intval($_GET['id']); // Convertir a entero para evitar inyección SQL

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

    // Asegúrate de que ya has obtenido la actividad previamente
    if (isset($actividad)) {
        $diaInicio = $actividad['dia_inicio'];
        $diaFin = $actividad['dia_fin'];
    }
    // Obtener los horarios de la actividad
    $horariosQuery = "SELECT id, horario FROM turnos_horarios WHERE actividad_id = ?";
    $horariosStmt = $conn->prepare($horariosQuery);
    $horariosStmt->bind_param("i", $actividadId);
    $horariosStmt->execute();
    $horariosResult = $horariosStmt->get_result();
} else {
    echo "<p>ID de actividad no proporcionada.</p>";
    exit;
}
?>

<h2 class="mt-5">Horarios Disponibles</h2>
<div class="accordion" id="horariosAccordion">
    <?php while ($horario = $horariosResult->fetch_assoc()): ?>
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading<?php echo $horario['id']; ?>">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $horario['id']; ?>" aria-expanded="true" aria-controls="collapse<?php echo $horario['id']; ?>">
                    Horario: <?php echo htmlspecialchars($horario['horario']); ?> | Estado: | Cupos ocupados: <span id="ocupados-<?php echo $horario['id']; ?>">0</span>/<span><?php echo htmlspecialchars($actividad['capacidad_turno']); ?></span>
                </button>
            </h2>
            <div id="collapse<?php echo $horario['id']; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $horario['id']; ?>" data-bs-parent="#horariosAccordion">
                <div class="accordion-body">
                    <h5>Turnos</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Número de Turno</th>
                                <th>ID</th>
                                <th>Estado</th>
                                <th>Huésped</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($i = 1; $i <= $actividad['capacidad_turno']; $i++): ?>
                                <?php
                                // Crear una ID única para cada turno
                                $turnoId = $horario['id'] . '-' . $i;

                                // Inicializar variables para estado y huesped
                                $estado = 'Libre'; // Inicializar el estado como 'Libre'
                                $huespedDNI = ''; // Inicializar el DNI como vacío

                                // Consulta para obtener el estado y el huesped_dni para este turno
                                $query = "SELECT huesped_dni FROM reservas WHERE id = ?"; // Seleccionar la ID de la reserva
                                $stmt = $conn->prepare($query);
                                $stmt->bind_param("s", $turnoId); // Cambia el tipo de parámetro a "s" si id es un string (ejemplo: '51-1')
                                $stmt->execute();
                                $result = $stmt->get_result();

                                // Verificar si hay reservas para este turno
                                if ($row = $result->fetch_assoc()) {
                                    $estado = 'Reservado'; // Cambiar el estado a 'Reservado'
                                    $huespedDNI = $row['huesped_dni']; // Obtener el DNI del huésped
                                }
                                $stmt->close();
                                ?>

                                <tr id="<?php echo $turnoId; ?>">
                                    <td><?php echo $i . '/' . $actividad['capacidad_turno']; ?></td>
                                    <td><?php echo $turnoId; ?></td>
                                    <td id="estado-<?php echo $turnoId; ?>"><?php echo $estado; ?></td>
                                    <td id="huesped-<?php echo $turnoId; ?>"><?php echo $huespedDNI; ?></td>
                                    <td>
                                        <?php if ($estado === 'Libre'): ?>
                                            <button class="btn btn-primary btn-sm" onclick="reservarTurno('<?php echo htmlspecialchars($turnoId); ?>', '<?php echo htmlspecialchars($horario['horario']); ?>')">Reservar</button>
                                        <?php else: ?>
                                            <button class="btn btn-danger btn-sm" onclick="cancelarReserva('<?php echo htmlspecialchars($turnoId); ?>')">Cancelar Reserva</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endfor; ?>
                        </tbody>



                    </table>
                </div>
            </div>


        </div>
    <?php endwhile; ?>
</div>

<!-- Modal para Reservar -->
<div class="modal fade" id="reservarModal" tabindex="-1" aria-labelledby="reservarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reservarModalLabel">Reservar Turno</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="reservarForm">
                    <div hidden class="mb-3">
                        <label for="turnoId" class="form-label"></label>
                        <input type="text" class="form-control" id="turnoId" required value="<?php echo $turnoId ?>">
                    </div>
                    <div class="mb-3">
                        <label for="dniHuesped" class="form-label">DNI Huésped</label>
                        <input type="text" class="form-control" id="dniHuesped" required>
                        <button type="button" class="btn btn-secondary mt-2" onclick="verificarDNI()">Verificar</button>
                    </div>
                    <div class="mb-3">
                        <label for="nombreHuesped" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombreHuesped" required>
                    </div>
                    <div class="mb-3">
                        <label for="correoHuesped" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="correoHuesped" required>
                    </div>
                    <div class="mb-3">
                        <label for="nombreActividad" class="form-label">Nombre de Actividad</label>
                        <input type="text" class="form-control" id="nombreActividad" value="<?php echo htmlspecialchars($actividad['nombre']); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="fechaActividad" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="fechaActividad" required>
                    </div>
                    <div class="mb-3">
                        <label for="horarioActividad" class="form-label">Horario</label>
                        <input type="text" class="form-control" id="horarioActividad" value="<?php echo htmlspecialchars($horario['horario']); ?>" readonly>
                    </div>
                    <button type="submit" class="btn btn-primary">Reservar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function cancelarReserva(turnoId) {
        if (confirm("¿Estás seguro de que deseas cancelar la reserva para el turno " + turnoId + "?")) {
            fetch('../SCRIPT/cancelar_reserva.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        turnoId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Reserva cancelada con éxito.');
                        // Actualiza la tabla para reflejar la cancelación
                        document.getElementById('estado-' + turnoId).innerText = 'Libre';
                        document.getElementById('huesped-' + turnoId).innerText = '';
                        const button = document.querySelector(`tr#${turnoId} button`);
                        button.innerText = 'Reservar';
                        button.classList.remove('btn-danger');
                        button.classList.add('btn-primary');
                    } else {
                        alert('Error al cancelar la reserva: ' + data.error);
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    }


    function reservarTurno(turnoId, horario) {
        // Establecer los valores en el modal
        document.getElementById('horarioActividad').value = horario; // Ahora aquí se establecerá el horario correcto
        document.getElementById('turnoId').value = turnoId;
        // Abrir el modal
        $('#reservarModal').modal('show');
    }

    document.getElementById('reservarForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevenir el envío normal del formulario

        const dni = document.getElementById('dniHuesped').value;
        const actividadId = <?php echo $actividadId ?>;
        const fecha = document.getElementById('fechaActividad').value;
        const horario = document.getElementById('horarioActividad').value;
        const turnoId = document.getElementById('turnoId').value;
        const cupoId = turnoId.split('-')[0];

        // Console log all values
        console.log('DNI:', dni);
        console.log('ActividadId:', actividadId);
        console.log('Fecha:', fecha);
        console.log('Horario:', horario);
        console.log('Cupo ID:', cupoId);
        console.log('Turno ID:', turnoId);

        // Enviar los datos al archivo de guardar reserva
        fetch('../SCRIPT/guardar_reserva.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    dni,
                    actividadId,
                    fecha,
                    horario,
                    cupoId, // Enviar el cupo_id
                    turnoId // Enviar el turnoId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Reserva realizada con éxito.');
                    $('#reservarModal').modal('hide'); // Cerrar el modal
                } else {
                    alert('Error al realizar la reserva: ' + data.error);
                }
            })
            .catch(error => console.error('Error:', error));
    });



    document.getElementById('fechaActividad').addEventListener('change', function() {
        const fechaSeleccionada = new Date(this.value + "T00:00:00Z"); // Fuerza UTC para evitar desfase de zona horaria

        // Validar que la fecha sea válida
        if (isNaN(fechaSeleccionada)) {
            alert("Fecha inválida. Por favor, selecciona una fecha válida.");
            this.value = ''; // Limpiar el campo de fecha
            return;
        }

        // Obtener el día de la semana en español
        const diasSemana = ["domingo", "lunes", "martes", "miércoles", "jueves", "viernes", "sábado"];
        const diaSeleccionado = diasSemana[fechaSeleccionada.getUTCDay()];
        console.log("Día seleccionado:", diaSeleccionado);

        // Obtener los días permitidos para la actividad
        const diasPermitidos = obtenerDiasPermitidos();

        // Verificar si el día seleccionado está dentro de los permitidos
        if (!diasPermitidos.includes(diaSeleccionado)) {
            alert('La fecha seleccionada no es válida. Debe ser un día entre ' + diasPermitidos.join(', '));
            this.value = ''; // Limpiar el campo de fecha
        }
    });

    // Función para obtener días permitidos en el intervalo
    function obtenerDiasPermitidos() {
        const diaInicio = "<?php echo strtolower($actividad['dia_inicio']); ?>";
        const diaFin = "<?php echo strtolower($actividad['dia_fin']); ?>";

        const diasDeLaSemana = ["lunes", "martes", "miércoles", "jueves", "viernes", "sábado", "domingo"];
        const diasPermitidos = [];

        let agregarDias = false;
        // Agregar días desde diaInicio hasta diaFin
        for (const dia of diasDeLaSemana) {
            if (dia === diaInicio) agregarDias = true;
            if (agregarDias) diasPermitidos.push(dia);
            if (dia === diaFin) break;
        }

        // Si no incluimos todos los días (caso cuando diaInicio está después de diaFin)
        if (!diasPermitidos.includes(diaFin)) {
            for (const dia of diasDeLaSemana) {
                diasPermitidos.push(dia);
                if (dia === diaFin) break;
            }
        }

        console.log("Días permitidos:", diasPermitidos); // Muestra los días en la consola
        return diasPermitidos;
    }

    function verificarDNI() {
        const dni = document.getElementById('dniHuesped').value;

        // Hacer la petición al archivo PHP
        fetch(`../SCRIPT/verificar-dni.php?dni=${dni}`)
            .then(response => response.json())
            .then(data => {
                if (data.encontrado) {
                    document.getElementById('nombreHuesped').value = data.nombre; // Asigna el nombre encontrado
                    document.getElementById('correoHuesped').value = data.correo; // Asigna el correo encontrado
                } else {
                    alert('DNI no encontrado, por favor complete los datos.');
                }
            })
            .catch(error => console.error('Error:', error));
    }
</script>