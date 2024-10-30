<?php
// guardar_actividad.php

include 'conexion.php';

// Manejo de la carga de imagen
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

    // Manejo de la imagen
    $imagen = $_FILES['imagen'];
    $imagen_path = '../IMAGENES/' . basename($imagen['name']);

    // Mueve la imagen a la carpeta IMAGENES/
    if (move_uploaded_file($imagen['tmp_name'], $imagen_path)) {
        // Inserta la actividad en la base de datos
        $sql = "INSERT INTO actividades (nombre, descripcion, horario_inicio, horario_cierre, formato, capacidad_turno, duracion, imagen, dia_inicio, dia_fin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssissss", $nombre, $descripcion, $horario_inicio, $horario_cierre, $formato, $capacidad_turno, $duracion, $imagen_path, $dia_inicio, $dia_fin);
        
        if ($stmt->execute()) {
            echo "Actividad guardada exitosamente.";
        } else {
            echo "Error al guardar la actividad: " . $stmt->error;
        }
        
        // Cierra la conexión
        $stmt->close();
    } else {
        echo "Error al subir la imagen.";
    }
}

$conn->close();
?>

