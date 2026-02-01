<?php
include '../SCRIPT/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $user_id = $_POST['user_id'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        echo "<p>Las contraseñas no coinciden. Por favor, inténtelo de nuevo.</p>";
        exit;
    }
    if (strlen($new_password) < 8) {
        echo "<p>La contraseña debe tener al menos 8 caracteres.</p>";
        exit;
    }
    $stmt = $conn->prepare("
        SELECT id 
        FROM reset_tokens 
        WHERE token = ? 
        AND user_id = ? 
        AND created_at >= NOW() - INTERVAL 1 DAY
    ");
    $stmt->bind_param("si", $token, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE usuarios SET contraseña = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed_password, $user_id);

        if ($stmt->execute()) {
            $stmt = $conn->prepare("DELETE FROM reset_tokens WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();

            echo "<p>Contraseña actualizada exitosamente. Ahora puede iniciar sesión con su nueva contraseña.</p>";
        } else {
            echo "<p>Hubo un error al actualizar la contraseña. Por favor, inténtelo de nuevo más tarde.</p>";
        }
    } else {
        echo "<p>El enlace de restablecimiento no es válido o ha expirado.</p>";
    }
} else {
    echo "<p>Método de solicitud no permitido.</p>";
}
