<?php
include 'conexion.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);
    $isAdmin = $_POST['isAdmin'];

    $query = "INSERT INTO usuarios (email, contraseña, isAdmin) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $email, $password, $isAdmin);
    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Usuario agregado con éxito.";
        header("Location: ../PAGINAS/administrador-usuarios.php");
        exit();
    } else {
        $_SESSION['mensaje'] = "Error al agregar el usuario: " . $stmt->error;
        header("Location: ../PAGINAS/administrador-usuarios.php");
        exit();
    }
}
