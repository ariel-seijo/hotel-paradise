<?php
require_once 'conexion.php';

$data = json_decode(file_get_contents("php://input"), true);
$actividad_id = $data['actividad_id'] ?? null;
$horario = $data['horario'] ?? null;

if ($actividad_id && $horario) {
    $sqlInsert = "INSERT INTO turnos_horarios (actividad_id, horario) VALUES (?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("is", $actividad_id, $horario);

    if ($stmtInsert->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al insertar el horario']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
}

$conn->close();
