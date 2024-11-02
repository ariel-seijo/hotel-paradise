<?php
include 'conexion.php';

$actividad_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$capacidad_turno = isset($_GET['capacidad_turno']) ? intval($_GET['capacidad_turno']) : 0;

if ($actividad_id > 0) {
    $sql = "SELECT id, horario FROM turnos_horarios WHERE actividad_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $actividad_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo '<div class="accordion" id="horariosAccordion">';
        while ($row = $result->fetch_assoc()) {
            $horarioId = $row['id'];
            $horario = $row['horario'];

            echo '<div class="accordion-item">';
            echo '<h2 class="accordion-header" id="heading' . $horarioId . '">';
            echo '<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse' . $horarioId . '" aria-expanded="true" aria-controls="collapse' . $horarioId . '">';
            echo 'Horario: ' . htmlspecialchars($horario);
            echo '</button></h2>';
            echo '<div id="collapse' . $horarioId . '" class="accordion-collapse collapse" aria-labelledby="heading' . $horarioId . '" data-bs-parent="#horariosAccordion">';
            echo '<div class="accordion-body">';

            // Generar filas de reservas
            echo '<table class="table">';
            echo '<thead><tr><th>Número de Turno</th><th>ID</th><th>Acciones</th></tr></thead>';
            echo '<tbody>';
            for ($i = 1; $i <= $capacidad_turno; $i++) {
                $turnoIdUnico = $horarioId . '-' . $i;
                echo '<tr id="turno-' . $turnoIdUnico . '">';
                echo '<td>' . $i . '/' . $capacidad_turno . '</td>';
                echo '<td>' . htmlspecialchars($turnoIdUnico) . '</td>';
                echo '<td><button class="btn btn-primary btn-sm" onclick="reservarTurno(\'' . htmlspecialchars($turnoIdUnico) . '\', \'' . htmlspecialchars($horario) . '\')">Reservar</button></td>';
                echo '</tr>';
            }
            echo '</tbody></table>';


            echo '</div></div></div>';
        }
        echo '</div>';
    } else {
        echo '<div class="alert alert-info">No se encontraron horarios para esta actividad.</div>';
    }
    $stmt->close();
} else {
    echo '<div class="alert alert-danger">ID de actividad no válido.</div>';
}
