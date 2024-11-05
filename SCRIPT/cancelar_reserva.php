<?php
include 'conexion.php';

// Obtiene los datos enviados desde el formulario
$data = json_decode(file_get_contents("php://input"), true);

// Valida la entrada
$id = $data['id'] ?? null;

// Verifica que el id esté presente
if (!$id) {
    echo json_encode(["status" => "error", "message" => "Faltan datos requeridos."]);
    exit;
}

// Elimina la reserva
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
