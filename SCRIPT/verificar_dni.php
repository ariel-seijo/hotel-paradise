<?php
include 'conexion.php';

$dni = isset($_POST['dni']) ? $_POST['dni'] : '';

if (!empty($dni)) {
    $sql = "SELECT huesped_nombre, huesped_email FROM huespedes WHERE huesped_dni = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $dni);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $huesped = $result->fetch_assoc();
        echo json_encode([
            "status" => "found",
            "nombre" => $huesped['huesped_nombre'],
            "email" => $huesped['huesped_email']
        ]);
    } else {
        echo json_encode(["status" => "not_found"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "DNI vacío"]);
}

$stmt->close();
$conn->close();
