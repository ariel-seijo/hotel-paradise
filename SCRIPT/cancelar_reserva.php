<?php
include 'conexion.php';

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'] ?? null;

if (!$id) {
    echo json_encode(["status" => "error", "message" => "Faltan datos requeridos."]);
    exit;
}

$sqlDelete = "DELETE FROM reservas WHERE id = ?";
$stmt = $conn->prepare($sqlDelete);
$stmt->bind_param("s", $id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "No se pudo cancelar la reserva: " . $stmt->error]);
}

$stmt->close();
$conn->close();
