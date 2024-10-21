<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'conexion.php'; // Incluir la conexión a la base de datos

// Verificar si se enviaron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si los índices existen en el arreglo POST
    if (isset($_POST['email']) && isset($_POST['contraseña'])) {
        $email = $_POST['email'];
        $contraseña = $_POST['contraseña'];

        // Consulta para verificar el usuario
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $usuario = $resultado->fetch_assoc();
            // Verificar la contraseña
            if ($contraseña === $usuario['contraseña']) {
                // Contraseña correcta, iniciar sesión
                $_SESSION['usuario'] = $usuario['email'];
                $_SESSION['isAdmin'] = $usuario['isAdmin'];
                header("Location: ../PAGINAS/actividades.php"); // Redirigir a actividades.php
                exit();
            }
        }
        // Si el usuario no se encontró o la contraseña es incorrecta
        $_SESSION['error'] = "Correo electronico o contraseña incorrectos."; // Almacenar el error en la sesión
    } else {
        $_SESSION['error'] = "Datos no enviados correctamente."; // Almacenar otro tipo de error
    }
    // Redirigir a index.php
    header("Location: ../index.php");
    exit();
}

// Cerrar conexión
$conn->close();