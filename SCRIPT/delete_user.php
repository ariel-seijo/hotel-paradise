<?php
include 'conexion.php';
if (isset($_POST['id'])) {
    $userId = intval($_POST['id']);
    $query = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
}
