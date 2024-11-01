<?php
// Incluir archivo de conexión a la base de datos
include '../SCRIPT/conexion.php';

// Obtener el turnoId desde la solicitud
$data = json_decode(file_get_contents('php://input'), true);
$turnoId = $data['turnoId'];

// Preparar y ejecutar la consulta para eliminar la reserva
$query = "DELETE FROM reservas WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $turnoId);
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Error al cancelar la reserva.']);
}
$stmt->close();
$conn->close();
