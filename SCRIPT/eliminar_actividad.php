<?php
// eliminar_actividad.php

include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtiene el ID de la actividad a eliminar
    $id = (int)$_POST['id']; // Asegúrate de convertir a entero

    // Prepara la consulta de eliminación
    $sql = "DELETE FROM actividades WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    // Vincula el parámetro
    $stmt->bind_param("i", $id);

    // Ejecuta la consulta
    if ($stmt->execute()) {
        echo "Actividad eliminada exitosamente.";
    } else {
        echo "Error al eliminar la actividad: " . $stmt->error;
    }

    // Cierra la conexión
    $stmt->close();
}

$conn->close();
?>

