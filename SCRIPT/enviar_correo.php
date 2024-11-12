<?php
function enviarCorreoReserva($correo, $huesped_dni, $actividad_id, $fecha, $horario) {
    // Configuración del encabezado del correo
    $to = $correo;
    $subject = "Confirmación de Reserva para $actividad_id";
    $headers = "From: arielseijo@mail.com\r\n";
    $headers .= "Reply-To: arielseijo@mail.com\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    // Cuerpo del mensaje
    $message = "
        <html>
        <head>
            <title>Confirmación de Reserva</title>
        </head>
        <body>
            <h2>Hola, $huesped_dni</h2>
            <p>Gracias por reservar en nuestra actividad <strong>$actividad_id</strong>.</p>
            <p><strong>Detalles de tu reserva:</strong></p>
            <ul>
                <li>Fecha: $fecha</li>
                <li>Horario: $horario</li>
            </ul>
            <p>Te esperamos en la fecha indicada.</p>
        </body>
        </html>
    ";

    // Envía el correo
    if (mail($to, $subject, $message, $headers)) {
        return true;
    } else {
        return false;
    }
}
?>