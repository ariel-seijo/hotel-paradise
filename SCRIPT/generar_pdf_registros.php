<?php
// Incluir la conexión a la base de datos y la librería mPDF
include 'conexion.php';
require_once('../vendor/autoload.php');

// Obtener los filtros aplicados desde la URL
// Recuperar los filtros desde la URL
$filtroFecha = isset($_GET['fecha']) ? $_GET['fecha'] : '';
$filtroNombreHuesped = isset($_GET['nombre_huesped']) ? $_GET['nombre_huesped'] : '';
$filtroDNI = isset($_GET['dni']) ? $_GET['dni'] : '';
$filtroActividad = isset($_GET['nombre_actividad']) ? $_GET['nombre_actividad'] : '';

// Continuar con la lógica para generar el PDF usando los filtros


// Inicializar mPDF
$mpdf = new \Mpdf\Mpdf();

// Crear un título personalizado basado en los filtros
$tituloPDF = 'Registros de Turnos';
$nombreArchivo = 'registros_turnos';

// Aplicar filtros al título y al nombre del archivo
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

// Establecer el título del documento
$mpdf->SetTitle($tituloPDF);

// Definir el contenido HTML
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

// Construir la consulta con filtros aplicados
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

// Agregar los datos de los registros al HTML del PDF
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

// Cerrar la tabla
$html .= '</tbody></table></body></html>';

// Escribir el contenido HTML en el PDF
$mpdf->WriteHTML($html);

// Descargar el PDF con el nombre basado en los filtros
$mpdf->Output($nombreArchivo . '.pdf', 'D');
