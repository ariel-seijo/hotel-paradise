<?php
require_once 'conexion.php';

$actividad_id = $_GET['id'] ?? null;

if ($actividad_id) {
    $sql = "SELECT id, horario FROM turnos_horarios WHERE actividad_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $actividad_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $horarios = [];
    while ($row = $result->fetch_assoc()) {
        $horarios[] = $row;
    }

    echo json_encode(['success' => true, 'horarios' => $horarios]);
}

$conn->close();
