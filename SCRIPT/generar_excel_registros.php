<?php
// Incluir la conexión a la base de datos
require_once 'conexion.php';
require_once '../vendor/autoload.php'; // Cargar la librería PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Recuperar los filtros desde la URL
$filtroFecha = isset($_GET['fecha']) ? $_GET['fecha'] : '';
$filtroNombreHuesped = isset($_GET['nombre_huesped']) ? $_GET['nombre_huesped'] : '';
$filtroDNI = isset($_GET['dni']) ? $_GET['dni'] : '';
$filtroActividad = isset($_GET['nombre_actividad']) ? $_GET['nombre_actividad'] : '';

// Construir la consulta con filtros
$sql = "SELECT 
            reservas.id, 
            reservas.huesped_dni, 
            reservas.actividad_id, 
            reservas.cupo_id, 
            reservas.horario, 
            reservas.fecha,
            huespedes.huesped_nombre, 
            actividades.nombre AS nombre_actividad 
        FROM reservas 
        JOIN huespedes ON reservas.huesped_dni = huespedes.huesped_dni 
        JOIN actividades ON reservas.actividad_id = actividades.id 
        WHERE 1";

// Aplicar filtros si están presentes
if (!empty($filtroFecha)) {
    $sql .= " AND reservas.fecha = ?";
}
if (!empty($filtroNombreHuesped)) {
    $sql .= " AND huespedes.huesped_nombre LIKE ?";
}
if (!empty($filtroDNI)) {
    $sql .= " AND reservas.huesped_dni LIKE ?";
}
if (!empty($filtroActividad)) {
    $sql .= " AND actividades.nombre LIKE ?";
}

// Preparar la consulta
$stmt = $conn->prepare($sql);

// Vincular parámetros según los filtros
$params = [];
if (!empty($filtroFecha)) {
    $params[] = $filtroFecha;
}
if (!empty($filtroNombreHuesped)) {
    $params[] = '%' . $filtroNombreHuesped . '%';
}
if (!empty($filtroDNI)) {
    $params[] = '%' . $filtroDNI . '%';
}
if (!empty($filtroActividad)) {
    $params[] = '%' . $filtroActividad . '%';
}

// Vincular parámetros solo si hay filtros aplicados
if (!empty($params)) {
    $stmt->bind_param(str_repeat("s", count($params)), ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

// Crear una nueva hoja de cálculo
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Títulos de las columnas
$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'DNI');
$sheet->setCellValue('C1', 'Nombre del Huésped');
$sheet->setCellValue('D1', 'ID Actividad');
$sheet->setCellValue('E1', 'Nombre de la Actividad');
$sheet->setCellValue('F1', 'Horario');
$sheet->setCellValue('G1', 'Fecha del Turno');

// Escribir los registros en las filas
$rowNum = 2;
while ($row = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $rowNum, $row['id']);
    $sheet->setCellValue('B' . $rowNum, $row['huesped_dni']);
    $sheet->setCellValue('C' . $rowNum, $row['huesped_nombre']);
    $sheet->setCellValue('D' . $rowNum, $row['actividad_id']);
    $sheet->setCellValue('E' . $rowNum, $row['nombre_actividad']);
    $sheet->setCellValue('F' . $rowNum, $row['horario']);
    $sheet->setCellValue('G' . $rowNum, $row['fecha']);
    $rowNum++;
}

// Definir nombre del archivo Excel
$nombreArchivo = 'registros_turnos';  // Cambié el nombre a 'agenda_turnos'

// Agregar filtros al nombre del archivo
if (!empty($filtroFecha)) {
    $nombreArchivo .= '_fecha_' . $filtroFecha;
}
if (!empty($filtroNombreHuesped)) {
    $nombreArchivo .= '_huesped_' . urlencode($filtroNombreHuesped);
}
if (!empty($filtroDNI)) {
    $nombreArchivo .= '_dni_' . $filtroDNI;
}
if (!empty($filtroActividad)) {
    $nombreArchivo .= '_actividad_' . urlencode($filtroActividad);
}

// Establecer la cabecera para la descarga del archivo
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $nombreArchivo . '.xlsx"');
header('Cache-Control: max-age=0');
header('Expires: 0');

// Limpiar el buffer y forzar la salida del archivo
ob_clean();
flush();

// Crear el archivo Excel y enviarlo al navegador
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>