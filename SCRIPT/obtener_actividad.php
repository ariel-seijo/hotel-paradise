<?php
// obtener_actividad.php

include 'conexion.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consulta para obtener la actividad por ID
    $sql = "SELECT * FROM actividades WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Devolver los datos en formato JSON
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode([]);
    }

    $stmt->close();
}

$conn->close();
?>
