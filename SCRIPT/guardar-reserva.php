<?php
// Incluir la conexión a la base de datos
include 'conexion.php';

// Verificar si se han enviado los datos necesarios
if (isset($_POST['huesped_dni']) && isset($_POST['actividad_id']) && isset($_POST['cupo_id'])) {
    $huespedDni = $_POST['huesped_dni'];
    $actividadId = $_POST['actividad_id'];
    $cupoId = $_POST['cupo_id'];

    // Preparar la consulta para insertar la reserva
    $stmt = $conn->prepare("INSERT INTO reservas (huesped_dni, actividad_id, cupo_id) VALUES (?, ?, ?)");
    $stmt->bind_param("sii", $huespedDni, $actividadId, $cupoId); // Asumiendo que huesped_dni es un string y los otros son enteros

    // Ejecutar la consulta
    if ($stmt->execute()) {
        $response = array('success' => true, 'message' => 'Reserva guardada exitosamente.');
    } else {
        $response = array('success' => false, 'message' => 'Error al guardar la reserva: ' . $stmt->error);
    }

    // Cerrar la declaración
    $stmt->close();
} else {
    $response = array('success' => false, 'message' => 'Datos incompletos para guardar la reserva.');
}

// Devolver la respuesta como JSON
header('Content-Type: application/json');
echo json_encode($response);

// Cerrar conexión
$conn->close();
?>



