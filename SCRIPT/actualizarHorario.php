<?php
require_once 'conexion.php';

$data = json_decode(file_get_contents("php://input"), true);
$horario_id = $data['horario_id'] ?? null;
$horario = $data['horario'] ?? null;

if ($horario_id && $horario) {
    $sql = "UPDATE turnos_horarios SET horario = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $horario, $horario_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al actualizar el horario']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
}

$conn->close();
?>
