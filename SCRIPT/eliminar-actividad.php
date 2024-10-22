<?php
// Incluir la conexión a la base de datos
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idActividad = intval($_POST['id']); // Obtener el ID de la actividad de forma segura

    // Primero, eliminar los registros asociados en turnos_horarios
    $sqlDeleteTurnos = "DELETE FROM turnos_horarios WHERE actividad_id = ?";
    $stmt = $conn->prepare($sqlDeleteTurnos);
    $stmt->bind_param("i", $idActividad);
    $stmt->execute();
    
    // Luego, eliminar el registro en actividades
    $sqlDeleteActividad = "DELETE FROM actividades WHERE id = ?";
    $stmt = $conn->prepare($sqlDeleteActividad);
    $stmt->bind_param("i", $idActividad);
    $stmt->execute();

    // Redireccionar a la página anterior o a una página de éxito
    header("Location: ../PAGINAS/administrador-actividades.php"); // Cambia esto si es necesario
    exit();
}

// Cerrar la conexión
$conn->close();
?>
