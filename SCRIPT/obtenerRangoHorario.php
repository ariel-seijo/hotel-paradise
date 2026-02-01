<?php
require_once 'conexion.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $sql = "SELECT horario_inicio, horario_cierre FROM actividades WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $rango = $result->fetch_assoc();
        echo json_encode([
            'success' => true,
            'horario_inicio' => $rango['horario_inicio'],
            'horario_cierre' => $rango['horario_cierre']
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Actividad no encontrada']);
    }
};

$conn->close();
