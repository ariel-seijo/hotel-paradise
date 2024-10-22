<?php
// Importar la conexión a la base de datos
include '../SCRIPT/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los datos del formulario
    $nombreActividad = $_POST['nombreActividad'];
    $descripcionActividad = $_POST['descripcionActividad'];
    $diaInicio = $_POST['diaInicio'];
    $diaCierre = $_POST['diaCierre'];
    $horarioInicio = $_POST['horarioInicio'];
    $horarioCierre = $_POST['horarioCierre'];
    $formatoTurno = $_POST['formatoTurno'];
    $capacidadTurno = isset($_POST['capacidadTurno']) ? intval($_POST['capacidadTurno']) : null;
    $duracionTurno = intval($_POST['duracionTurno']);
    $cantidadTurnos = intval($_POST['cantidadTurnos']);

    // Procesar la imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['imagen']['tmp_name'];
        $fileName = $_FILES['imagen']['name'];
        $fileSize = $_FILES['imagen']['size'];
        $fileType = $_FILES['imagen']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Limitar extensiones permitidas
        $allowedfileExtensions = array('jpg', 'png', 'jpeg');
        if (in_array($fileExtension, $allowedfileExtensions)) {
            // Ruta donde se guardará la imagen
            $uploadFileDir = '../IMAGENES/';
            $dest_path = $uploadFileDir . $fileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $imagenRuta = $dest_path;
            } else {
                echo 'Error al mover la imagen al directorio de destino.';
            }
        } else {
            echo 'Extensión de archivo no permitida.';
        }
    } else {
        echo 'Error al cargar la imagen.';
    }

    // Insertar los datos en la tabla de actividades
    $stmt = $conn->prepare("INSERT INTO actividades (nombre, descripcion, dias, horario_inicio, horario_cierre, formato, capacidad_turno, cantidad_turnos, duracion, imagen) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $dias = $diaInicio . ', ' . $diaCierre;
    $stmt->bind_param("ssssssiiis", $nombreActividad, $descripcionActividad, $dias, $horarioInicio, $horarioCierre, $formatoTurno, $capacidadTurno, $cantidadTurnos, $duracionTurno, $imagenRuta);

    if ($stmt->execute()) {
        $actividadId = $stmt->insert_id; // Obtener el ID de la actividad recién creada

        // Insertar los turnos en la tabla de turnos_horarios
        for ($i = 0; $i < $cantidadTurnos; $i++) {
            $horaInicioTurno = date('H:i:s', strtotime($horarioInicio) + $i * $duracionTurno * 60); // Calcular la hora de cada turno
            $stmtTurnos = $conn->prepare("INSERT INTO turnos_horarios (actividad_id, horario) VALUES (?, ?)");
            $stmtTurnos->bind_param("is", $actividadId, $horaInicioTurno);
            $stmtTurnos->execute();
        }

        echo "Actividad y turnos guardados correctamente.";
    } else {
        echo "Error al guardar la actividad: " . $stmt->error;
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
}
?>
