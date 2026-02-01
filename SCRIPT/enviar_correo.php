<?php
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function enviarCorreoConfirmacion($correo, $huespedNombre, $nombreActividad, $horario, $fecha) {
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
        $mail->addAddress($correo);

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Reserva realizada exitosamente';
        $mail->Body = "
            <h1>Confirmación de Reserva</h1>
            <p>Estimado {$huespedNombre},</p>
            <p>Su reserva para la actividad {$nombreActividad} se ha realizado con éxito. Aquí tiene los detalles de dicha reserva:</p>
            <ul>
                <li><strong>Horario:</strong> {$horario}</li>
                <li><strong>Fecha:</strong> {$fecha}</li>
            </ul>
            <p>Gracias por confiar en nosotros.</p>
        ";

        // Envía el correo
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("No se pudo enviar el correo. Error: {$mail->ErrorInfo}");
        return false;
    }
}
?>

