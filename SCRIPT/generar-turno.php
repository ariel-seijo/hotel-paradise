<?php
// Incluir la conexión a la base de datos
include 'conexion.php';

// Verificar si se ha enviado un ID de actividad
if (isset($_GET['id'])) {
    $actividadId = intval($_GET['id']); // Asegurarse de que el ID es un número entero

    // Consulta para obtener la actividad, incluyendo el nombre y la capacidad de turno
    $stmt = $conn->prepare("SELECT nombre, capacidad_turno FROM actividades WHERE id = ?");
    $stmt->bind_param("i", $actividadId);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Verificar si se encontró la actividad
    if ($resultado->num_rows > 0) {
        $actividad = $resultado->fetch_assoc();
        $capacidadTurno = $actividad['capacidad_turno'];

        // Consulta para obtener los turnos de la actividad
        $stmtTurnos = $conn->prepare("SELECT horario FROM turnos_horarios WHERE actividad_id = ?");
        $stmtTurnos->bind_param("i", $actividadId);
        $stmtTurnos->execute();
        $resultTurnos = $stmtTurnos->get_result();

        // Verificar si se encontraron turnos
        if ($resultTurnos->num_rows > 0) {
            $i = 0;

            while ($turno = $resultTurnos->fetch_assoc()) {
                $i++;
                $hora = $turno['horario'];
                $collapseId = 'panelsStayOpen-collapse' . $i;

                // Consulta para contar cuántos cupos están ocupados para este horario
                $stmtCuposOcupados = $conn->prepare("SELECT COUNT(*) as ocupados FROM reservas WHERE actividad_id = ? AND cupo_id = ?");
                $cupoId = $i; // Suponiendo que el ID de cupo es el índice en el bucle
                $stmtCuposOcupados->bind_param("ii", $actividadId, $cupoId);
                $stmtCuposOcupados->execute();
                $resultadoCupos = $stmtCuposOcupados->get_result();
                $ocupados = $resultadoCupos->fetch_assoc()['ocupados'];

                // Calcular el estado del turno
                $cuposOcupados = $ocupados; // Número de cupos ocupados
                $estadoTurno = ($cuposOcupados < $capacidadTurno) ? "Turno disponible" : "Turno ocupado";

                // Mostrar el encabezado del turno en el acordeón
                echo '
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading' . $i . '">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#' . $collapseId . '" aria-expanded="false" aria-controls="' . $collapseId . '">
                            <div class="row">
                                <div class="col-2">' . $hora . '</div>
                                <div class="col-3">' . $estadoTurno . '</div>
                                <div class="col-3">Ocupado: ' . $cuposOcupados . ' / ' . $capacidadTurno . '</div>
                                <div class="col-4"></div>
                            </div>
                        </button>
                    </h2>
                    <div id="' . $collapseId . '" class="accordion-collapse collapse" data-bs-parent="#accordionPanelsStayOpenExample">
                        <div class="accordion-body">';

                // Generar los cupos dentro del acordeón
                for ($j = 1; $j <= $capacidadTurno; $j++) {
                    // Determinar el estado del cupo
                    $estadoCupo = "Disponible";
                    $nombreHuesped = "- - -";
                    $colorClase = 'bg-disponible'; // Verde pastel

                    // Si el cupo está ocupado, actualizar el estado
                    if ($j <= $cuposOcupados) {
                        $estadoCupo = "Ocupado";
                        $nombreHuesped = "Huésped"; // Aquí podrías obtener el nombre del huésped si se almacena
                        $colorClase = 'bg-ocupado'; // Rojo pastel
                    }

                    // Aquí añadimos los atributos data-actividad y data-horario al botón
                    $botonReserva = ($estadoCupo == "Disponible") ? 
                        '<button type="button" class="btn btn-primary reservar-btn" data-actividad="' . htmlspecialchars($actividad['nombre']) . '" data-horario="' . htmlspecialchars($hora) . '" data-cupo-id="' . $j . '" data-bs-toggle="modal" data-bs-target="#exampleModal">Reservar</button>' : 
                        '<button type="button" class="btn btn-secondary" disabled>No disponible</button>'; // Botón deshabilitado si no está disponible

                    // Mostrar cada cupo dentro del acordeón
                    echo '
                        <div class="row ' . $colorClase . ' align-items-center" style="height: 60px; border: 1px solid black">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-2">' . $j . ' / ' . $capacidadTurno . '</div>
                                    <div class="col-3">' . $estadoCupo . '</div>
                                    <div class="col-3">' . $nombreHuesped . '</div>
                                    <div class="col-4">' . $botonReserva . '</div>
                                </div>
                            </div>
                        </div>';
                }

                // Cerrar el cuerpo del acordeón
                echo '
                        </div>
                    </div>
                </div>';
            }
        } else {
            echo "No se encontraron turnos para esta actividad.";
        }
    } else {
        echo "Actividad no encontrada.";
    }
} else {
    echo "No se proporcionó ID de actividad.";
}

// Cerrar conexión
$conn->close();
