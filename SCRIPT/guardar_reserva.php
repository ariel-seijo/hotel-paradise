<?php
include 'conexion.php'; // Asegúrate de que la conexión a la base de datos está bien configurada

// Importar las clases de PHPMailer y Exception
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Incluir el autoloader de Composer para cargar automáticamente PHPMailer
require '../vendor/autoload.php';

// Obtiene los datos enviados desde el formulario
$data = json_decode(file_get_contents("php://input"), true);

// Valida la entrada
$id = $data['id'] ?? null;
$huesped_dni = $data['huesped_dni'] ?? null;
$actividad_id = $data['actividad_id'] ?? null;
$horario = $data['horario'] ?? null;
$fecha = $data['fecha'] ?? null;
$correo = $data['correo'] ?? null; // Asegúrate de tener el correo en los datos

// Verifica que los datos necesarios estén presentes
if (!$id || !$huesped_dni || !$actividad_id || !$horario || !$fecha || !$correo) {
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
    exit;
}

// Inserta en la tabla reservas
if ($cupo_id !== null) {
    $sqlInsert = "INSERT INTO reservas (id, huesped_dni, actividad_id, cupo_id, horario, fecha) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sqlInsert);
    $stmt->bind_param("ssiiss", $id, $huesped_dni, $actividad_id, $cupo_id, $horario, $fecha);

    include 'enviar_correo.php';

    if ($stmt->execute()) {
        // Enviar el correo de confirmación
        if (enviarCorreoReserva($correo, $huesped_dni, $actividad_id, $fecha, $horario)) {
            echo json_encode(["status" => "success", "message" => "Reserva realizada y correo enviado con éxito."]);
        } else {
            echo json_encode(["status" => "success", "message" => "Reserva realizada, pero no se pudo enviar el correo."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No se pudo realizar la reserva: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Cupo no válido."]);
}

$conn->close();
