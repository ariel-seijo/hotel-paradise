<?php
require_once 'conexion.php';

$horario_id = $_GET['id'] ?? null;

if ($horario_id) {
    $sql = "DELETE FROM turnos_horarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $horario_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al eliminar el horario']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'ID de horario no válido']);
}

$conn->close();
