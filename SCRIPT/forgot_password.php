<?php
include '../SCRIPT/conexion.php'; // Incluir la conexión a la base de datos

// Incluir PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['reset_email'];
    
    // Imprimir el correo en la consola con JavaScript

    // Verificar si el correo existe
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_id = $user['id'];

        // Generar token único
        $token = bin2hex(random_bytes(50));

        // Guardar el token en la base de datos
        $stmt = $conn->prepare("INSERT INTO reset_tokens (user_id, token) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $token);
        $stmt->execute();

        // Enlace de restablecimiento
        $reset_link = "http://localhost/hotel-paradise/SCRIPT/reset_password.php?token=" . $token;

        // Configuración de PHPMailer
        $mail = new PHPMailer(true);
        
        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'tas.consultora@gmail.com'; // Tu correo
            $mail->Password = 'drtg ebyv rmet avku'; // Tu contraseña de la app
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Configuración del remitente y destinatario
            $mail->setFrom('tas.consultora@gmail.com', 'Hotel Paradise');
            $mail->addAddress($email); // Correo del destinatario

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Restablecer tu contraseña';
            $mail->Body    = 'Haz clic en el siguiente enlace para restablecer tu contraseña: <a href="' . $reset_link . '">Restablecer contraseña</a>';
            $mail->AltBody = 'Haz clic en el siguiente enlace para restablecer tu contraseña: ' . $reset_link;

            // Enviar correo
            $mail->send();

            echo "Se ha enviado un enlace de restablecimiento a tu correo.";
        } catch (Exception $e) {
            echo "Error al enviar el correo. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "El correo electrónico no está registrado.";
    }
}
?>


