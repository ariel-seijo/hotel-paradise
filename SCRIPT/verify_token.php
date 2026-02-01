<?php
require '../SCRIPT/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $token = $_POST['token'];
    $query = "SELECT * FROM password_resets WHERE email = ? AND reset_token = ? AND token_expiration > NOW()";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'El código es incorrecto o ha expirado.']);
    }
    $stmt->close();
}
