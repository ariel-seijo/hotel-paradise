<?php
// Incluir la conexión a la base de datos
include 'conexion.php';
session_start();

$actividad_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$capacidad_turno = isset($_GET['capacidad_turno']) ? intval($_GET['capacidad_turno']) : 0;
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : '';

// Verificar si el usuario es visualizador
$isVisualizador = !isset($_SESSION['isAdmin']);

if ($actividad_id > 0 && !empty($fecha)) {
    // Obtener todos los horarios
    $sql = "SELECT id, horario FROM turnos_horarios WHERE actividad_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $actividad_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo '<button class="btn btn-success" id="btn-pdf" style="width: 20%; display: none;" onclick="window.location.href=\'../SCRIPT/generar_pdf.php?id=' . $actividad_id . '&capacidad_turno=' . $capacidad_turno . '&fecha=' . urlencode($fecha) . '\'">
        <i class="bi bi-filetype-pdf"></i> Exportar PDF
      </button>';

        echo '<button class="btn btn-danger" id="btn-excel" style="width: 20%; display: none;" onclick="window.location.href=\'../SCRIPT/generar_excel.php?id=' . $actividad_id . '&capacidad_turno=' . $capacidad_turno . '&fecha=' . urlencode($fecha) . '\'">
        <i class="bi bi-file-earmark-excel"></i> Exportar Excel
      </button>';

        echo '<style>
        #btn-excel {
            margin-bottom: 20px;
            margin-left: 20px;
            color: white;
            background-color: #62bfbd;
            border: 2px solid #34a09e;
        }

        #btn-excel:focus,
        #btn-excel:hover,
        #btn-excel:active {
            background-color: #34a09e;
            border: 2px solid #34a09e;
        }
        #btn-pdf {
            margin-bottom: 20px;
            color: white;
            background-color: #ffb5ba;
            border: 2px solid #f36f78;
        }

        #btn-pdf:focus,
        #btn-pdf:hover,
        #btn-pdf:active {
            background-color: #f36f78;
            border: 2px solid #f36f78;
        }
        
        
        </style>';

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
            echo '<thead><tr>';
            echo '<th style="width: 20%;">Número de Turno</th>';
            echo '<th style="width: 20%;">Turno</th>';
            echo '<th style="width: 20%;">DNI de Huésped</th>';
            echo '<th style="width: 20%;">Nombre de Huésped</th>';
            if (!$isVisualizador) {
                echo '<th style="width: 20%;">Acciones</th>';
            }
            echo '</tr></thead>';
            echo '<tbody>';
            for ($i = 1; $i <= $capacidad_turno; $i++) {
                $turnoIdUnico = $horarioId . '-' . $i;

                // Comprobar si ya existe una reserva para este turno y fecha
                $sqlReserva = "SELECT r.huesped_dni, h.huesped_nombre 
                               FROM reservas r
                               LEFT JOIN huespedes h ON r.huesped_dni = h.huesped_dni 
                               WHERE r.id = ? AND r.fecha = ?";
                $stmtReserva = $conn->prepare($sqlReserva);
                $stmtReserva->bind_param("ss", $turnoIdUnico, $fecha);
                $stmtReserva->execute();
                $resultReserva = $stmtReserva->get_result();

                if ($resultReserva->num_rows > 0) {
                    $reserva = $resultReserva->fetch_assoc();
                    $huespedDNI = $reserva['huesped_dni'];
                    $huespedNombre = $reserva['huesped_nombre'];
                } else {
                    $huespedDNI = '-----';
                    $huespedNombre = '-----';
                }

                echo '<tr style="width: 20%;" id="turno-' . $turnoIdUnico . '">';
                echo '<td style="width: 20%;">' . $i . '/' . $capacidad_turno . '</td>';
                echo '<td style="width: 20%;">' . htmlspecialchars($turnoIdUnico) . '</td>';
                echo '<td style="width: 20%;">' . htmlspecialchars($huespedDNI) . '</td>';
                echo '<td style="width: 20%;">' . htmlspecialchars($huespedNombre) . '</td>';

                if (!$isVisualizador) {
                    if ($huespedDNI !== '-----') {
                        // Si hay una reserva, mostrar el botón de cancelar
                        echo '<td style="width: 20%;"><button class="btn btn-danger btn-sm btn-cancelar" onclick="cancelarReserva(\'' . htmlspecialchars($turnoIdUnico) . '\', \'' . htmlspecialchars($horario) . '\')">Cancelar Reserva</button></td>';
                    } else {
                        // Si no hay reserva, mostrar el botón de reservar
                        echo '<td style="width: 20%;"><button class="btn btn-primary btn-sm" onclick="reservarTurno(\'' . htmlspecialchars($turnoIdUnico) . '\', \'' . htmlspecialchars($horario) . '\')">Reservar</button></td>';
                    }
                }
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
    echo '<div class="alert alert-danger">ID de actividad no válido o fecha no proporcionada.</div>';
}
