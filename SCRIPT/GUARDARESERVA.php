<?php
include '../SCRIPT/conexion.php';

// Obtener los datos JSON del cuerpo de la solicitud
$data = json_decode(file_get_contents("php://input"), true);

// Extraer los datos
$dni = $data['dni'];
$actividadId = $data['actividadId'];
$fecha = $data['fecha'];
$horario = $data['horario'];
$cupoId = $data['cupoId']; // El cupo_id
$turnoId = $data['turnoId']; // El turnoId

// Validar que se recibieron todos los datos
if (!isset($dni, $actividadId, $fecha, $horario, $cupoId, $turnoId)) {
    echo json_encode(['success' => false, 'error' => 'Faltan datos']);
    exit;
}

// Preparar la consulta para insertar la reserva
$query = "INSERT INTO reservas (id, huesped_dni, actividad_id, cupo_id, horario, fecha) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssiiss", $turnoId, $dni, $actividadId, $cupoId, $horario, $fecha); // Asegúrate de definir actividadId correctamente

// Ejecutar la consulta
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}

// Cerrar la declaración y la conexión
$stmt->close();
$conn->close();
?>

