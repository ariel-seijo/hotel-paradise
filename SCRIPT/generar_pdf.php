<?php
require_once 'conexion.php'; // Conexión a la base de datos
require_once '../vendor/autoload.php'; // Librería MPDF para generar PDF

use Mpdf\Mpdf;

session_start();

$actividad_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$capacidad_turno = isset($_GET['capacidad_turno']) ? intval($_GET['capacidad_turno']) : 0;
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : '';

if ($actividad_id > 0 && !empty($fecha)) {
    // Obtener el nombre de la actividad
    $sqlActividad = "SELECT nombre FROM actividades WHERE id = ?";
    $stmtActividad = $conn->prepare($sqlActividad);
    $stmtActividad->bind_param("i", $actividad_id);
    $stmtActividad->execute();
    $resultActividad = $stmtActividad->get_result();

    if ($resultActividad->num_rows > 0) {
        $actividad = $resultActividad->fetch_assoc();
        $nombreActividad = $actividad['nombre'];
    } else {
        // Si no se encuentra la actividad, mostrar mensaje de error
        echo '<p>Error: No se encontró la actividad.</p>';
        exit;
    }

    $mpdf = new Mpdf();
    $mpdf->WriteHTML('<h2>Agenda de turnos de ' . htmlspecialchars($nombreActividad) . ' para la Fecha: ' . htmlspecialchars($fecha) . '</h2>');

    // Obtener horarios y reservas
    $sql = "SELECT id, horario FROM turnos_horarios WHERE actividad_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $actividad_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $horarioId = $row['id'];
            $horario = $row['horario'];

            // Escribir el horario en el PDF
            $mpdf->WriteHTML('<h3>Horario: ' . htmlspecialchars($horario) . '</h3>');
            $mpdf->WriteHTML('<table border="1" style="width: 100%; border-collapse: collapse;">');
            $mpdf->WriteHTML('<thead><tr>
                <th style="width: 20%;">Número de Turno</th>
                <th style="width: 20%;">Turno</th>
                <th style="width: 20%;">DNI de Huésped</th>
                <th style="width: 20%;">Nombre de Huésped</th>
            </tr></thead><tbody>');

            // Iterar por cada turno dentro del horario
            for ($i = 1; $i <= $capacidad_turno; $i++) {
                $turnoIdUnico = $horarioId . '-' . $i;

                // Consultar la reserva para cada turno
                $sqlReserva = "SELECT r.huesped_dni, h.huesped_nombre 
                               FROM reservas r
                               LEFT JOIN huespedes h ON r.huesped_dni = h.huesped_dni 
                               WHERE r.id = ? AND r.fecha = ?";
                $stmtReserva = $conn->prepare($sqlReserva);
                $stmtReserva->bind_param("ss", $turnoIdUnico, $fecha);
                $stmtReserva->execute();
                $resultReserva = $stmtReserva->get_result();

                if ($resultReserva->num_rows > 0) {
                    // Si hay reserva, obtener los datos
                    $reserva = $resultReserva->fetch_assoc();
                    $huespedDNI = $reserva['huesped_dni'];
                    $huespedNombre = $reserva['huesped_nombre'];
                } else {
                    // Si no hay reserva, poner valores por defecto
                    $huespedDNI = '-----';
                    $huespedNombre = '-----';
                }

                // Escribir los datos del turno en el PDF
                $mpdf->WriteHTML('<tr>
                    <td style="width: 20%;">' . $i . '/' . $capacidad_turno . '</td>
                    <td style="width: 20%;">' . htmlspecialchars($turnoIdUnico) . '</td>
                    <td style="width: 20%;">' . htmlspecialchars($huespedDNI) . '</td>
                    <td style="width: 20%;">' . htmlspecialchars($huespedNombre) . '</td>
                </tr>');
            }
            // Cerrar la tabla y agregar un salto de línea entre horarios
            $mpdf->WriteHTML('</tbody></table><br>');
        }
    } else {
        // Si no hay horarios, mostrar un mensaje en el PDF
        $mpdf->WriteHTML('<p>No se encontraron horarios para esta actividad.</p>');
    }

    // Cerrar la consulta de horarios
    $stmt->close();

    // Generar y descargar el PDF con el nombre de la actividad en el archivo
    $pdfFilename = 'Agenda_' . htmlspecialchars($nombreActividad) . '_' . $fecha . '.pdf';
    $mpdf->Output($pdfFilename, 'D');
} else {
    // Si no se proporciona un ID de actividad o fecha válida
    echo '<p>Error: ID de actividad o fecha no válida.</p>';
}
?>


