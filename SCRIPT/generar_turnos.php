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
                                ?>
                                <tr>
                                    <td><?php echo $i . '/' . $actividad['capacidad_turno']; ?></td>
                                    <td id="estado-<?php echo $turnoId; ?>">Libre</td>
                                    <td id="huesped-<?php echo $turnoId; ?>"></td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" onclick="reservarTurno('<?php echo $turnoId; ?>', '<?php echo htmlspecialchars($actividad['nombre']); ?>', '<?php echo htmlspecialchars($horario['horario']); ?>')">Reservar</button>

                                        <button class="btn btn-danger btn-sm" style="display:none;" onclick="cancelarReserva('<?php echo $turnoId; ?>')">Cancelar Reserva</button>
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
                        <input type="text" class="form-control" id="horarioActividad" value="horarioActividad" readonly>
                    </div>
                    <button type="submit" class="btn btn-primary">Reservar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function reservarTurno(turnoId, actividadNombre, horario) {
        // Establecer los valores en el modal
        document.getElementById('horarioActividad').value = horario; // Ahora aquí se establecerá el horario correcto
        // Abrir el modal
        $('#reservarModal').modal('show');
    }

    function verificarDNI() {
        const dni = document.getElementById('dniHuesped').value;

        // Hacer la petición al archivo PHP
        fetch(`../SCRIPT/verificar-dni.php?dni=${dni}`)
            .then(response => response.json())
            .then(data => {
                if (data.encontrado) {
                    // Si el DNI fue encontrado, autocompletar los campos
                    document.getElementById('nombreHuesped').value = data.nombre;
                    document.getElementById('correoHuesped').value = data.correo;
                } else {
                    // Si no se encontró, informar al usuario
                    alert('DNI no encontrado. Por favor, verifica e intenta de nuevo.');
                    document.getElementById('nombreHuesped').value = '';
                    document.getElementById('correoHuesped').value = '';
                }
            })
            .catch(error => {
                console.error('Error al verificar el DNI:', error);
                alert('Ocurrió un error al verificar el DNI. Intenta nuevamente más tarde.');
            });
    }


    document.getElementById('reservarForm').addEventListener('submit', function(event) {
        event.preventDefault();
        // Aquí deberías agregar la lógica para guardar la reserva
        alert("Reserva realizada para " + document.getElementById('nombreHuesped').value);
        $('#reservarModal').modal('hide');
    });
</script>