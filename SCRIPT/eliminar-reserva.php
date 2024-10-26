<?php
// Incluir la conexión a la base de datos
include 'conexion.php';

// Verificar si se ha enviado el DNI del huésped y el ID del cupo
if (isset($_POST['huesped_dni']) && isset($_POST['cupo_id'])) {
    $huespedDni = $_POST['huesped_dni'];
    $cupoId = intval($_POST['cupo_id']); // Asegurarse de que el ID es un número entero

    // Preparar la consulta para eliminar una sola reserva
    $stmt = $conn->prepare("DELETE FROM reservas WHERE huesped_dni = ? AND cupo_id = ? LIMIT 1");
    $stmt->bind_param("si", $huespedDni, $cupoId);

    if ($stmt->execute()) {
        // Si la eliminación fue exitosa
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Reserva eliminada con éxito.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se encontró ninguna reserva para eliminar.']);
        }
    } else {
        // Si hubo un error en la eliminación
        echo json_encode(['success' => false, 'message' => 'Error al eliminar la reserva.']);
    }

    // Cerrar la declaración
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Datos insuficientes.']);
}

// Cerrar conexión
$conn->close();
?>
