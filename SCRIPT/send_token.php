<?php
include '../SCRIPT/conexion.php'; // Incluir la conexión a la base de datos

// Incluir PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['reset_email'], FILTER_SANITIZE_EMAIL);

    // Verificar si el correo está registrado
    $query = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generar el token de 6 dígitos y calcular la expiración
        $token = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiration = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        // Guardar el token en la tabla `password_resets`
        $insertQuery = "INSERT INTO password_resets (email, reset_token, token_expiration)
                        VALUES (?, ?, ?)
                        ON DUPLICATE KEY UPDATE reset_token = VALUES(reset_token), token_expiration = VALUES(token_expiration)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("sss", $email, $token, $expiration);
        $stmt->execute();

        // Configurar PHPMailer para enviar el correo
        $mail = new PHPMailer(true);
        try {
            // Configuración del servidor SMTP
            $env = parse_ini_file(__DIR__ . '/../.env');
            $mail->isSMTP();
            $mail->Host = $env['SMTP_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $env['SMTP_USER'];
            $mail->Password = $env['SMTP_PASS'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $env['SMTP_PORT'];
            

            // Configuración del remitente y destinatario
            $mail->setFrom($env['SMTP_USER'], 'Hotel Paradise');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Restablecimiento de contraseña';
            $mail->Body = "<p>Has solicitado restablecer tu contraseña. Usa el siguiente código:</p>
                           <h2>$token</h2>
                           <p>El código expirará en 15 minutos.</p>";

            $mail->send();
            echo json_encode(['success' => true, 'message' => 'El correo ha sido enviado.']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error al enviar el correo: ' . $mail->ErrorInfo]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'El correo no está registrado.']);
    }
    $stmt->close();
}
?>

