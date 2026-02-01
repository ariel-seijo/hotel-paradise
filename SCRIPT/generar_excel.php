<?php
require_once 'conexion.php';
require_once '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

session_start();

$actividad_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$capacidad_turno = isset($_GET['capacidad_turno']) ? intval($_GET['capacidad_turno']) : 0;
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : '';

if ($actividad_id > 0 && !empty($fecha)) {

    $sqlActividad = "SELECT nombre FROM actividades WHERE id = ?";
    $stmtActividad = $conn->prepare($sqlActividad);
    $stmtActividad->bind_param("i", $actividad_id);
    $stmtActividad->execute();
    $resultActividad = $stmtActividad->get_result();

    if ($resultActividad->num_rows > 0) {
        $actividad = $resultActividad->fetch_assoc();
        $nombreActividad = $actividad['nombre'];
    } else {

        echo '<p>Error: No se encontró la actividad.</p>';
        exit;
    }

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'Agenda de turnos de ' . htmlspecialchars($nombreActividad) . ' para la Fecha: ' . htmlspecialchars($fecha));
    $sheet->mergeCells('A1:D1');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
    $sheet->getRowDimension(1)->setRowHeight(30);
    $sheet->setCellValue('A2', 'Número de Turno');
    $sheet->setCellValue('B2', 'Turno');
    $sheet->setCellValue('C2', 'DNI de Huésped');
    $sheet->setCellValue('D2', 'Nombre de Huésped');
    $sheet->getStyle('A2:D2')->getFont()->setBold(true);
    $sheet->getStyle('A2:D2')->getAlignment()->setHorizontal('center');
    $row = 3;
    $sql = "SELECT id, horario FROM turnos_horarios WHERE actividad_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $actividad_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($rowHorario = $result->fetch_assoc()) {
            $horarioId = $rowHorario['id'];
            $horario = $rowHorario['horario'];
            $sheet->setCellValue('A' . $row, 'Horario: ' . htmlspecialchars($horario));
            $sheet->mergeCells('A' . $row . ':D' . $row);
            $sheet->getStyle('A' . $row)->getFont()->setBold(true);
            $row++;

            for ($i = 1; $i <= $capacidad_turno; $i++) {
                $turnoIdUnico = $horarioId . '-' . $i;
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

                $sheet->setCellValue('A' . $row, $i . '/' . $capacidad_turno);
                $sheet->setCellValue('B' . $row, htmlspecialchars($turnoIdUnico));
                $sheet->setCellValue('C' . $row, htmlspecialchars($huespedDNI));
                $sheet->setCellValue('D' . $row, htmlspecialchars($huespedNombre));
                $row++;
            }
        }
    } else {
        $sheet->setCellValue('A' . $row, 'No se encontraron horarios para esta actividad.');
        $sheet->mergeCells('A' . $row . ':D' . $row);
        $sheet->getStyle('A' . $row)->getFont()->setItalic(true);
    }

    $excelFilename = 'Agenda_' . htmlspecialchars($nombreActividad) . '_' . $fecha . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $excelFilename . '"');
    header('Cache-Control: max-age=0');
    ob_clean();
    flush();
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
} else {
    echo '<p>Error: ID de actividad o fecha no válida.</p>';
}
