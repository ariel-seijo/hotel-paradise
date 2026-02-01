<?php
require '../SCRIPT/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $updateQuery = "UPDATE usuarios SET contraseña = ? WHERE email = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ss", $newPassword, $email);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $deleteQuery = "DELETE FROM password_resets WHERE email = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param("s", $email);
        $deleteStmt->execute();

        echo json_encode(['success' => true, 'message' => 'Contraseña actualizada correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar la contraseña.']);
    }
    $stmt->close();
}
