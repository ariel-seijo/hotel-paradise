<?php
include 'conexion.php';
if (isset($_POST['id']) && isset($_POST['isAdmin'])) {
    $userId = intval($_POST['id']);
    $isAdmin = intval($_POST['isAdmin']);

    $query = "UPDATE usuarios SET isAdmin = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $isAdmin, $userId);
    $stmt->execute();
}
