<?php


session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $horario_inicio = $_POST['horario_inicio'];
    $horario_cierre = $_POST['horario_cierre'];
    $formato = $_POST['formato'];
    $capacidad_turno = $_POST['capacidad_turno'];
    $duracion = $_POST['duracion'];
    $dia_inicio = $_POST['dia_inicio'];
    $dia_fin = $_POST['dia_fin'];

    $imagen = $_FILES['imagen'];
    $imagen_path = '../IMAGENES/' . basename($imagen['name']);

    if (move_uploaded_file($imagen['tmp_name'], $imagen_path)) {

        $sql = "INSERT INTO actividades (nombre, descripcion, horario_inicio, horario_cierre, formato, capacidad_turno, duracion, imagen, dia_inicio, dia_fin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssissss", $nombre, $descripcion, $horario_inicio, $horario_cierre, $formato, $capacidad_turno, $duracion, $imagen_path, $dia_inicio, $dia_fin);

        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Actividad guardada exitosamente.";
            header("Location: ../PAGINAS/administrador-actividades.php");
            exit();
        } else {
            $_SESSION['mensaje'] = "Error al guardar la actividad: " . $stmt->error;
            header("Location: ../PAGINAS/administrador-actividades.php");
            exit();
        }

        $stmt->close();
    } else {
        $_SESSION['mensaje'] = "Error al subir la imagen.";
        header("Location: ../PAGINAS/administrador-actividades.php");
        exit();
    }
}

$conn->close();
