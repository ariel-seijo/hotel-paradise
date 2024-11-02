<?php
include 'conexion.php'; // Asegúrate de que la conexión a la base de datos está bien configurada

// Obtiene los datos enviados desde el formulario
$data = json_decode(file_get_contents("php://input"), true);

// Valida la entrada
$id = $data['id'] ?? null;
$huesped_dni = $data['huesped_dni'] ?? null;
$actividad_id = $data['actividad_id'] ?? null;
$horario = $data['horario'] ?? null;
$fecha = $data['fecha'] ?? null;

// Verifica que los datos necesarios estén presentes
if (!$id || !$huesped_dni || !$actividad_id || !$horario || !$fecha) {
    echo json_encode(["status" => "error", "message" => "Faltan datos requeridos."]);
    exit;
}

// Busca el cupo_id
$cupo_id = null;
$sqlCupo = "SELECT id FROM turnos_horarios WHERE horario = ? AND actividad_id = ?";
$stmtCupo = $conn->prepare($sqlCupo);
$stmtCupo->bind_param("si", $horario, $actividad_id);
$stmtCupo->execute();
$resultCupo = $stmtCupo->get_result();

if ($resultCupo->num_rows > 0) {
    $cupo_id = $resultCupo->fetch_assoc()['id'];
} else {
    echo json_encode(["status" => "error", "message" => "No se encontró el cupo para la reserva."]);
    exit; // Salir si no se encuentra el cupo
}

// Inserta en la tabla reservas
if ($cupo_id !== null) {
    $sqlInsert = "INSERT INTO reservas (id, huesped_dni, actividad_id, cupo_id, horario, fecha) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sqlInsert);
    $stmt->bind_param("ssiiss", $id, $huesped_dni, $actividad_id, $cupo_id, $horario, $fecha);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "No se pudo realizar la reserva: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Cupo no válido."]);
}

$conn->close();
