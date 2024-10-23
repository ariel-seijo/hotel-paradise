<?php
include 'conexion.php'; // Incluir la conexión a la base de datos

if (isset($_GET['dni'])) {
    $dni = $_GET['dni'];
    $stmt = $conn->prepare("SELECT huesped_nombre, huesped_email FROM huespedes WHERE huesped_dni = ?");
    $stmt->bind_param("i", $dni);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $huesped = $resultado->fetch_assoc();
        echo json_encode([
            'encontrado' => true,
            'nombre' => $huesped['huesped_nombre'],
            'correo' => $huesped['huesped_email']
        ]);
    } else {
        echo json_encode(['encontrado' => false]);
    }
}

$conn->close();
?>
