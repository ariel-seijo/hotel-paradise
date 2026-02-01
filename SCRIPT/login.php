<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'conexion.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email']) && isset($_POST['contraseña'])) {
        $email = $_POST['email'];
        $contraseña = $_POST['contraseña'];
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $usuario = $resultado->fetch_assoc();
            if (password_verify($contraseña, $usuario['contraseña'])) {
                $_SESSION['usuario'] = $usuario['email'];
                $_SESSION['isAdmin'] = $usuario['isAdmin'];
                if ($usuario['isAdmin'] == 1) {
                    header("Location: ../PAGINAS/administrador-actividades.php");
                } else {
                    header("Location: ../PAGINAS/actividades.php");
                }
                exit();
            }
        }
        $_SESSION['error'] = "Correo electrónico o contraseña incorrectos.";
    } else {
        $_SESSION['error'] = "Datos no enviados correctamente.";
    }
    header("Location: ../index.php");
    exit();
}

$conn->close();
