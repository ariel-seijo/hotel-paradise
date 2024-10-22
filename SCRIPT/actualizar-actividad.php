<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idActividad = intval($_POST['id']);
    $nombreActividad = $_POST['nombreActividad'];
    $descripcionActividad = $_POST['descripcionActividad'];
    $dias = implode(',', $_POST['dias']); // Concatenar días
    $horarioInicio = $_POST['horarioInicio'];
    $horarioCierre = $_POST['horarioCierre'];
    $formatoTurno = $_POST['formatoTurno'];
    $capacidadTurno = intval($_POST['capacidadTurno']);
    $cantidadTurnos = intval($_POST['cantidadTurnos']);
    $duracionTurno = intval($_POST['duracionTurno']);

    // Consulta para actualizar la actividad
    $query = "UPDATE actividades SET nombre = ?, descripcion = ?, dias = ?, horario_inicio = ?, horario_cierre = ?, formato = ?, capacidad_turno = ?, cantidad_turnos = ?, duracion = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssiiiii", $nombreActividad, $descripcionActividad, $dias, $horarioInicio, $horarioCierre, $formatoTurno, $capacidadTurno, $cantidadTurnos, $duracionTurno, $idActividad);

    if ($stmt->execute()) {
        echo "Actividad actualizada con éxito.";
    } else {
        echo "Error al actualizar la actividad: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>

