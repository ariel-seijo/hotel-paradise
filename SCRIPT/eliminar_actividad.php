<?php

include 'conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $sql = "DELETE FROM actividades WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Actividad eliminada con éxito.";
        header("Location: ../PAGINAS/administrador-actividades.php");
        exit();
    } else {
        $_SESSION['mensaje'] = "Error al eliminar la actividad: " . $stmt->error;
        header("Location: ../PAGINAS/administrador-actividades.php");
        exit();
    }
    $stmt->close();
}

$conn->close();
