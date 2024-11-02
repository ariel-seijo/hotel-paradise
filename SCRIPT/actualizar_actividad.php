<?php
// actualizar_actividad.php
session_start(); // Iniciar la sesión
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtiene los datos del formulario
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

    // Inicializa la variable de la imagen
    $imagen_path = null;

    // Verifica si se ha subido una nueva imagen
    if (!empty($_FILES['imagen']['name'])) {
        $imagen = $_FILES['imagen'];
        $imagen_path = '../IMAGENES/' . basename($imagen['name']);

        // Mueve la imagen a la carpeta IMAGENES/
        if (!move_uploaded_file($imagen['tmp_name'], $imagen_path)) {
            echo "Error al subir la imagen.";
            exit;
        }
    } else {
        // Si no se sube una nueva imagen, se obtiene la imagen actual de la base de datos
        $sql = "SELECT imagen FROM actividades WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Verifica si se encontró la actividad
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Mantiene la imagen actual
            $imagen_path = $row['imagen'];
        } else {
            echo "Actividad no encontrada.";
            exit;
        }
    }

    // Construye la consulta de actualización
    $sql = "UPDATE actividades SET nombre = ?, descripcion = ?, horario_inicio = ?, horario_cierre = ?, formato = ?, capacidad_turno = ?, duracion = ?, dia_inicio = ?, dia_fin = ?, imagen = ? WHERE id = ?";

    $stmt = $conn->prepare($sql);
    
    // Vincula los parámetros
    $stmt->bind_param("sssssiisssi",
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

    // Ejecuta la consulta
    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Actividad actualizada con éxito."; // Mensaje de éxito
        header("Location: ../PAGINAS/administrador-actividades.php");
        exit(); // Asegurarse de que no se ejecute más código
    } else {
        $_SESSION['mensaje'] = "Error al actualizar la actividad: " . $stmt->error; // Mensaje de error
        header("Location: ../PAGINAS/administrador-actividades.php");
        exit(); // Asegurarse de que no se ejecute más código
    }

    // Cierra la conexión
    $stmt->close();
}

$conn->close();
?>



