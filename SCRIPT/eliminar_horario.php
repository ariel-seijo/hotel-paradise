<?php
include 'conexion.php';

$horario_id = $_POST['horario_id'];

$sql = "DELETE FROM turnos_horarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $horario_id);

if ($stmt->execute()) {
    echo "Horario eliminado exitosamente.";
} else {
    echo "Error al eliminar el horario.";
}

$stmt->close();
$conn->close();
?>
