<?php
// guardar_actividad.php

session_start(); // Iniciar la sesión
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
            $_SESSION['mensaje'] = "Actividad guardada exitosamente."; // Mensaje de éxito
            header("Location: ../PAGINAS/administrador-actividades.php");
            exit(); // Asegurarse de que no se ejecute más código
        } else {
            $_SESSION['mensaje'] = "Error al guardar la actividad: " . $stmt->error; // Mensaje de error
            header("Location: ../PAGINAS/administrador-actividades.php");
            exit(); // Asegurarse de que no se ejecute más código
        }
        
        // Cierra la conexión
        $stmt->close();
    } else {
        $_SESSION['mensaje'] = "Error al subir la imagen."; // Mensaje de error al subir la imagen
        header("Location: ../PAGINAS/administrador-actividades.php");
        exit(); // Asegurarse de que no se ejecute más código
    }
}

$conn->close();
?>


