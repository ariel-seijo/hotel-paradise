
<?php
// Incluir la conexión a la base de datos
include '../SCRIPT/conexion.php';

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener datos del formulario
    $huespedDNI = $_POST['huesped_dni'];
    $actividadId = intval($_POST['actividad_id']);
    $turnoHorario = $_POST['turno_horario'];

    // Preparar la consulta para insertar la reserva
    $stmt = $conn->prepare("INSERT INTO reservas (huesped_dni, actividad_id, turno_horario) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $huespedDNI, $actividadId, $turnoHorario);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Reserva guardada correctamente."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al guardar la reserva: " . $stmt->error]);
    }

    // Cerrar la declaración y conexión
    $stmt->close();
    $conn->close();
}
?>
