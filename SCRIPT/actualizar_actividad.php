<?php
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $horario_inicio = $_POST['horario_inicio'];
    $horario_cierre = $_POST['horario_cierre'];
    $formato = $_POST['formato'];
    $capacidad_turno = $_POST['capacidad_turno'];
    $duracion = $_POST['duracion'];
    $dia_inicio = $_POST['dia_inicio'];
    $dia_fin = $_POST['dia_fin'];

    $imagen_path = null;

    if (!empty($_FILES['imagen']['name'])) {
        $imagen = $_FILES['imagen'];
        $imagen_path = '../IMAGENES/' . basename($imagen['name']);

        if (!move_uploaded_file($imagen['tmp_name'], $imagen_path)) {
            echo "Error al subir la imagen.";
            exit;
        }
    } else {

        $sql = "SELECT imagen FROM actividades WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $imagen_path = $row['imagen'];
        } else {
            echo "Actividad no encontrada.";
            exit;
        }
    }

    $sql = "UPDATE actividades SET nombre = ?, descripcion = ?, horario_inicio = ?, horario_cierre = ?, formato = ?, capacidad_turno = ?, duracion = ?, dia_inicio = ?, dia_fin = ?, imagen = ? WHERE id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "sssssiisssi",
        $nombre,
        $descripcion,
        $horario_inicio,
        $horario_cierre,
        $formato,
        $capacidad_turno,
        $duracion,
        $dia_inicio,
        $dia_fin,
        $imagen_path,
        $id
    );

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Actividad actualizada con éxito.";
        header("Location: ../PAGINAS/administrador-actividades.php");
        exit();
    } else {
        $_SESSION['mensaje'] = "Error al actualizar la actividad: " . $stmt->error;
        header("Location: ../PAGINAS/administrador-actividades.php");
        exit();
    }

    $stmt->close();
}

$conn->close();
