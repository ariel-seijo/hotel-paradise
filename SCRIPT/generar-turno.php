<?php
// Incluir la conexión a la base de datos
include 'conexion.php';

// Verificar si se ha enviado un ID de actividad
if (isset($_GET['id'])) {
    $actividadId = intval($_GET['id']); // Asegurarse de que el ID es un número entero

    // Consulta para obtener la actividad y su capacidad de turno
    $stmt = $conn->prepare("SELECT capacidad_turno FROM actividades WHERE id = ?");
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

                // Contador de cupos ocupados (esto es un ejemplo, puedes obtenerlo de una tabla de reservas si la tienes)
                $cuposOcupados = rand(0, $capacidadTurno); // Simula los cupos ocupados
                $estadoTurno = ($cuposOcupados < $capacidadTurno) ? "Turno disponible" : "Turno lleno";

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
                    // Alternar entre "ocupado" y "disponible" para simular el estado de los cupos
                    if ($j <= $cuposOcupados) {
                        $estadoCupo = "Ocupado";
                        $nombreHuesped = "Alfonso"; // Aquí puedes poner el nombre real si tienes una tabla de reservas
                        $colorClase = 'bg-ocupado'; // Rojo pastel
                    } else {
                        $estadoCupo = "Disponible";
                        $nombreHuesped = "- - -";
                        $colorClase = 'bg-disponible'; // Verde pastel
                    }

                    // Mostrar cada cupo dentro del acordeón
                    echo '
                        <div class="row ' . $colorClase . ' align-items-center" style="height: 60px; border: 1px solid black">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-2">' . $j . ' / ' . $capacidadTurno . '</div>
                                    <div class="col-3">' . $estadoCupo . '</div>
                                    <div class="col-3">' . $nombreHuesped . '</div>
                                    <div class="col-4"></div>
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
?>





