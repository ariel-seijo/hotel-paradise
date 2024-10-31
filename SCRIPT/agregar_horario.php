<?php
// Incluir la conexión a la base de datos
require_once 'conexion.php';

// Obtener los datos enviados desde JavaScript
$data = json_decode(file_get_contents("php://input"), true);
$actividad_id = $data['actividad_id'];
$horario = $data['horario'];

// Consulta para obtener el rango permitido de horarios
$sqlRango = "SELECT horario_inicio, horario_cierre FROM actividades WHERE id = ?";
$stmtRango = $conn->prepare($sqlRango);
$stmtRango->bind_param("i", $actividad_id);
$stmtRango->execute();
$resultRango = $stmtRango->get_result();
$rango = $resultRango->fetch_assoc();

// Validar que el horario esté dentro del rango
if ($horario < $rango['horario_inicio'] || $horario > $rango['horario_cierre']) {
    echo json_encode(['error' => "El horario debe estar entre {$rango['horario_inicio']} y {$rango['horario_cierre']}"]);
    exit;
}

// Insertar el nuevo horario en la tabla turnos_horarios
$sqlInsert = "INSERT INTO turnos_horarios (actividad_id, horario) VALUES (?, ?)";
$stmtInsert = $conn->prepare($sqlInsert);
$stmtInsert->bind_param("is", $actividad_id, $horario);

if ($stmtInsert->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Error al insertar el horario']);
}

$conn->close();
?>
