<?php
// eliminar_actividad.php

include 'conexion.php';
session_start(); // Iniciar la sesión

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
        $_SESSION['mensaje'] = "Actividad eliminada con éxito."; // Mensaje de éxito
        header("Location: ../PAGINAS/administrador-actividades.php");
        exit(); // Asegurarse de que no se ejecute más código
    } else {
        $_SESSION['mensaje'] = "Error al eliminar la actividad: " . $stmt->error; // Mensaje de error
        header("Location: ../PAGINAS/administrador-actividades.php");
        exit(); // Asegurarse de que no se ejecute más código
    }

    // Cierra la conexión
    $stmt->close();
}

$conn->close();
?>

