<?php
include 'conexion.php';

header('Content-Type: application/json'); // Asegura que la respuesta sea JSON

$actividad_id = isset($_GET['actividad_id']) ? intval($_GET['actividad_id']) : 0;

$sql = "SELECT horario_inicio, horario_cierre FROM actividades WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $actividad_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode([
        "success" => true,
        "horario_inicio" => $row['horario_inicio'],
        "horario_cierre" => $row['horario_cierre']
    ]);
} else {
    // Envía una respuesta JSON si no se encuentra el registro
    echo json_encode(["success" => false, "message" => "No se encontró la actividad."]);
}

$stmt->close();
$conn->close();
?>

