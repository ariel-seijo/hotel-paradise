<?php
include 'conexion.php';

// Obtiene los datos enviados desde el formulario
$data = json_decode(file_get_contents("php://input"), true);

// Valida la entrada
$id = $data['id'] ?? null;
$huesped_dni = $data['huesped_dni'] ?? null;
$actividad_id = $data['actividad_id'] ?? null;
$horario = $data['horario'] ?? null;
$fecha = $data['fecha'] ?? null;
$correo = $data['correo'] ?? null;

if (!$id || !$huesped_dni || !$actividad_id || !$horario || !$fecha || !$correo) {
    echo json_encode(["status" => "error", "message" => "Faltan datos requeridos."]);
    exit;
}

// Busca el cupo_id
$sqlCupo = "SELECT id FROM turnos_horarios WHERE horario = ? AND actividad_id = ?";
$stmtCupo = $conn->prepare($sqlCupo);
$stmtCupo->bind_param("si", $horario, $actividad_id);
$stmtCupo->execute();
$resultCupo = $stmtCupo->get_result();

if ($resultCupo->num_rows > 0) {
    $cupo_id = $resultCupo->fetch_assoc()['id'];
} else {
    echo json_encode(["status" => "error", "message" => "No se encontró el cupo para la reserva."]);
    exit;
}

// Obtiene el nombre del huésped
$sqlHuesped = "SELECT huesped_nombre FROM huespedes WHERE huesped_dni = ?";
$stmtHuesped = $conn->prepare($sqlHuesped);
$stmtHuesped->bind_param("s", $huesped_dni);
$stmtHuesped->execute();
$resultHuesped = $stmtHuesped->get_result();
$huespedNombre = $resultHuesped->fetch_assoc()['huesped_nombre'] ?? '';

// Obtiene el nombre de la actividad
$sqlActividad = "SELECT nombre FROM actividades WHERE id = ?";
$stmtActividad = $conn->prepare($sqlActividad);
$stmtActividad->bind_param("i", $actividad_id);
$stmtActividad->execute();
$resultActividad = $stmtActividad->get_result();
$nombreActividad = $resultActividad->fetch_assoc()['nombre'] ?? '';

// Inserta en la tabla reservas
if ($cupo_id !== null) {
    $sqlInsert = "INSERT INTO reservas (id, huesped_dni, actividad_id, cupo_id, horario, fecha) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sqlInsert);
    $stmt->bind_param("ssiiss", $id, $huesped_dni, $actividad_id, $cupo_id, $horario, $fecha);

    include 'enviar_correo.php';

    if ($stmt->execute()) {
        // Enviar el correo de confirmación con los nombres obtenidos
        if (enviarCorreoConfirmacion($correo, $huespedNombre, $nombreActividad, $horario, $fecha)) {
            echo json_encode(["status" => "success", "message" => "Reserva realizada y correo enviado con éxito."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Reserva realizada pero el correo no se pudo enviar."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No se pudo realizar la reserva: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Cupo no válido."]);
}

$conn->close();
?>

