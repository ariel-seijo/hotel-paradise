<?php
include 'conexion.php';
require_once('../vendor/autoload.php');

$filtroFecha = isset($_GET['fecha']) ? $_GET['fecha'] : '';
$filtroNombreHuesped = isset($_GET['nombre_huesped']) ? $_GET['nombre_huesped'] : '';
$filtroDNI = isset($_GET['dni']) ? $_GET['dni'] : '';
$filtroActividad = isset($_GET['nombre_actividad']) ? $_GET['nombre_actividad'] : '';

$mpdf = new \Mpdf\Mpdf();

$tituloPDF = 'Registros de Turnos';
$nombreArchivo = 'registros_turnos';

if (!empty($filtroFecha)) {
    $tituloPDF .= ' - Fecha: ' . $filtroFecha;
    $nombreArchivo .= '_fecha_' . $filtroFecha;
}
if (!empty($filtroNombreHuesped)) {
    $tituloPDF .= ' - Huésped: ' . $filtroNombreHuesped;
    $nombreArchivo .= '_huesped_' . preg_replace('/[^a-zA-Z0-9]/', '_', $filtroNombreHuesped); // Reemplazar caracteres especiales en el nombre
}
if (!empty($filtroDNI)) {
    $tituloPDF .= ' - DNI: ' . $filtroDNI;
    $nombreArchivo .= '_dni_' . $filtroDNI;
}
if (!empty($filtroActividad)) {
    $tituloPDF .= ' - Actividad: ' . $filtroActividad;
    $nombreArchivo .= '_actividad_' . preg_replace('/[^a-zA-Z0-9]/', '_', $filtroActividad); // Reemplazar caracteres especiales en el nombre
}

$mpdf->SetTitle($tituloPDF);

$html = '
<html>
<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>' . htmlspecialchars($tituloPDF) . '</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>DNI</th>
                <th>Nombre del Huésped</th>
                <th>ID Actividad</th>
                <th>Nombre de la Actividad</th>
                <th>Horario</th>
                <th>Fecha del Turno</th>
            </tr>
        </thead>
        <tbody>';

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

$stmt = $conn->prepare($sql);

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

if (!empty($params)) {
    $stmt->bind_param(str_repeat("s", count($params)), ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $html .= '<tr>
                <td>' . htmlspecialchars($row['id']) . '</td>
                <td>' . htmlspecialchars($row['huesped_dni']) . '</td>
                <td>' . htmlspecialchars($row['huesped_nombre']) . '</td>
                <td>' . htmlspecialchars($row['actividad_id']) . '</td>
                <td>' . htmlspecialchars($row['nombre_actividad']) . '</td>
                <td>' . htmlspecialchars($row['horario']) . '</td>
                <td>' . htmlspecialchars($row['fecha']) . '</td>
              </tr>';
}

$html .= '</tbody></table></body></html>';

$mpdf->WriteHTML($html);

$mpdf->Output($nombreArchivo . '.pdf', 'D');
