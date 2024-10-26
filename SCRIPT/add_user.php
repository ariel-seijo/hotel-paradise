<?php
include 'conexion.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);
    $isAdmin = $_POST['isAdmin'];

    $query = "INSERT INTO usuarios (email, contraseña, isAdmin) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $email, $password, $isAdmin);
    $stmt->execute();
    header("Location: ../PAGINAS/administrador-usuarios.php");
}
?>
