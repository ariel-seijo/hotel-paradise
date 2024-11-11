<?php
include 'conexion.php';

$actividad_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($actividad_id > 0) {
    $sql = "SELECT * FROM actividades WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $actividad_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $actividad = $result->fetch_assoc();
        $dia_inicio = $actividad['dia_inicio'];
        $dia_fin = $actividad['dia_fin'];
        $capacidad_turno = $actividad['capacidad_turno'];
    } else {
        echo "No se encontró la actividad.";
        exit;
    }
} else {
    echo "ID de actividad no válido.";
    exit;
}
?>
<div class="container contenedor-fecha mt-5">
    <form class="contenedor-formulario-fecha" id="searchForm">
        <div class="form-group">
            <label for="fecha">Seleccione una fecha:</label>
            <input type="date" class="form-control" id="fecha" required>
        </div>
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>
    <div id="result" class="mt-3"></div>
</div>


<!-- Modal para Reservar -->
<div class="modal fade" id="reservarModal" tabindex="-1" aria-labelledby="reservarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <style>
                    .modal-header {
                        background-color: #62bfbd;
                    }
                </style>
                <h5 class="modal-title" id="reservarModalLabel">Reservar Turno</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="reservarForm">
                    <div hidden class="mb-3">
                        <label for="turnoId" class="form-label"></label>
                        <input type="text" class="form-control" id="turnoId" required>
                    </div>
                    <div class="mb-3">
                        <label for="dniHuesped" class="form-label">DNI Huésped</label>
                        <input type="text" class="form-control" id="dniHuesped" required>
                        <button type="button" id="verificarDniButton" class="btn btn-secondary mt-2">Verificar</button>

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
                        <input type="date" class="form-control" id="fechaActividad" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="horarioActividad" class="form-label">Horario</label>
                        <input type="text" class="form-control" id="horarioActividad" value="<?php echo htmlspecialchars($horario); ?>" readonly>
                    </div>
                    <button type="submit" class="btn btn-primary">Reservar</button>
                    <button type="button" class="btn btn-secondary btn-cancelar" data-bs-dismiss="modal">Cancelar</button>
                    <style>
                        .btn-cancelar {
                            background-color: #f5838a;
                        }

                        .btn-cancelar:hover {
                            color: white;
                            background-color: #f3606a;
                        }
                    </style>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    document.getElementById('reservarForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Evita el envío normal del formulario

        const turnoId = document.getElementById('turnoId').value;
        const dniHuesped = document.getElementById('dniHuesped').value;
        const actividadId = '<?php echo $actividad_id; ?>';
        const horario = document.getElementById('horarioActividad').value;
        const fecha = document.getElementById('fechaActividad').value;

        console.log(turnoId, dniHuesped, actividadId, horario, fecha); // Muestra los valores en consola

        fetch('../SCRIPT/guardar_reserva.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    id: turnoId,
                    huesped_dni: dniHuesped,
                    actividad_id: actividadId,
                    horario: horario,
                    fecha: fecha
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Reserva realizada con éxito.');
                    // Cierra el modal
                    $('#reservarModal').modal('hide');
                    location.reload();
                } else {
                    alert('Error al realizar la reserva: ' + data.message);
                }
            })
            .catch(error => console.error('Error al guardar la reserva:', error));
    });


    document.getElementById('verificarDniButton').addEventListener('click', function() {
        const dni = document.getElementById('dniHuesped').value;

        if (dni) {
            fetch('../SCRIPT/verificar_dni.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'dni=' + encodeURIComponent(dni)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'found') {
                        // Si el huésped es encontrado, autocompleta los campos
                        document.getElementById('nombreHuesped').value = data.nombre;
                        document.getElementById('correoHuesped').value = data.email;
                    } else if (data.status === 'not_found') {
                        alert('El huésped no fue encontrado.');
                    }
                })
                .catch(error => console.error('Error:', error));
        } else {
            alert('Por favor, ingrese un DNI.');
        }
    });

    function reservarTurno(turnoId, horario) {
        // Asigna los valores a los campos del formulario en el modal
        document.getElementById('turnoId').value = turnoId;
        document.getElementById('horarioActividad').value = horario;
        document.getElementById('nombreActividad').value = '<?php echo $actividad['nombre'] ?>';
        document.getElementById('fechaActividad').value = document.getElementById('fecha').value;

        // Abre el modal de reserva
        const reservarModal = new bootstrap.Modal(document.getElementById('reservarModal'));
        reservarModal.show();
    }

    document.getElementById('searchForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const fechaSeleccionada = document.getElementById('fecha').value;
        const fechaActual = new Date().toISOString().split("T")[0]; // Obtiene la fecha actual en formato YYYY-MM-DD

        // Comprobar si la fecha seleccionada es anterior o igual a la fecha actual
        if (fechaSeleccionada < fechaActual) {
            document.getElementById('result').innerHTML = '<div class="alert alert-danger">No se puede reservar turnos en días anteriores a la fecha.</div>';
            return; // Detiene el proceso si la fecha no es válida
        }

        // Continúa con el resto de las validaciones de días de la semana
        const fecha = new Date(fechaSeleccionada + "T00:00:00Z");
        const dia = fecha.getUTCDay();
        const dias = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
        const diaInicio = '<?php echo $dia_inicio; ?>';
        const diaFin = '<?php echo $dia_fin; ?>';

        const diasDeLaSemana = ['lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado', 'domingo'];
        const diasValidosArray = [];
        for (let i = diasDeLaSemana.indexOf(diaInicio); i <= diasDeLaSemana.indexOf(diaFin); i++) {
            diasValidosArray.push(diasDeLaSemana[i]);
        }

        const diaSeleccionado = dias[dia];
        const esDiaValido = diasValidosArray.includes(diaSeleccionado);

        const resultDiv = document.getElementById('result');
        if (esDiaValido) {
            // Hacer una solicitud AJAX a PHP para obtener los horarios de la actividad
            fetch(`../SCRIPT/obtener_turnos.php?id=<?php echo $actividad_id; ?>&capacidad_turno=<?php echo $capacidad_turno; ?>&fecha=${fechaSeleccionada}`)
                .then(response => response.text())
                .then(data => {
                    resultDiv.innerHTML = data; // Agregar la lista de horarios al resultado
                })
                .catch(error => console.error('Error al obtener los turnos:', error));
        } else {
            resultDiv.innerHTML = '<div class="alert alert-danger">La actividad no tiene turnos asignados para estos días.</div>';
        }
    });



    function cancelarReserva(turnoId, horario) {
        // Aquí puedes implementar la lógica para cancelar la reserva
        if (confirm("¿Está seguro de que desea cancelar esta reserva?")) {
            fetch('../SCRIPT/cancelar_reserva.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id: turnoId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('Reserva cancelada con éxito.');
                        // Actualiza la UI o recarga los horarios
                        location.reload(); // O puedes actualizar solo la parte afectada
                    } else {
                        alert('Error al cancelar la reserva: ' + data.message);
                    }
                })
                .catch(error => console.error('Error al cancelar la reserva:', error));
        }
    }

    window.onload = function() {
        const fechaInput = document.getElementById('fecha');
        const today = new Date().toISOString().split("T")[0];
        fechaInput.value = today;
    };
</script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>