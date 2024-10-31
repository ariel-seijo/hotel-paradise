<?php
include 'conexion.php';

$actividad_id = isset($_POST['actividad_id']) ? intval($_POST['actividad_id']) : 0;
$horario = isset($_POST['horario']) ? $_POST['horario'] : '';

if ($actividad_id && $horario) {
    $sql = "INSERT INTO turnos_horarios (actividad_id, horario) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $actividad_id, $horario);

    if ($stmt->execute()) {
        header("Location: ../PAGINAS/administrador-actividades.php?success=Horario guardado");
    } else {
        header("Location: ../PAGINAS/administrador-actividades.php?error=No se pudo guardar el horario");
    }

    $stmt->close();
} else {
    header("Location: ../PAGINAS/administrador-actividades.php?error=Datos no válidos");
}

$conn->close();
?>
