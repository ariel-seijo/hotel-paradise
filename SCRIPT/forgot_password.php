<?php
include '../SCRIPT/conexion.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['reset_email'];
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_id = $user['id'];
        $token = bin2hex(random_bytes(50));
        $stmt = $conn->prepare("INSERT INTO reset_tokens (user_id, token) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $token);
        $stmt->execute();
        $reset_link = "http://localhost/hotel-paradise/SCRIPT/reset_password.php?token=" . $token;
        $mail = new PHPMailer(true);

        try {
            $env = parse_ini_file(__DIR__ . '/../.env');
            $mail->isSMTP();
            $mail->Host = $env['SMTP_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $env['SMTP_USER'];
            $mail->Password = $env['SMTP_PASS'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $env['SMTP_PORT'];
            $mail->setFrom($env['SMTP_USER'], 'Hotel Paradise');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Restablecer tu contraseña';
            $mail->Body    = 'Haz clic en el siguiente enlace para restablecer tu contraseña: <a href="' . $reset_link . '">Restablecer contraseña</a>';
            $mail->AltBody = 'Haz clic en el siguiente enlace para restablecer tu contraseña: ' . $reset_link;
            $mail->send();

            echo "Se ha enviado un enlace de restablecimiento a tu correo.";
        } catch (Exception $e) {
            echo "Error al enviar el correo. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "El correo electrónico no está registrado.";
    }
}
