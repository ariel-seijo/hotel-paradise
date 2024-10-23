<?php
// Incluir la conexión a la base de datos
include 'conexion.php';

// Consulta para obtener las actividades
$query = "SELECT id, nombre, imagen FROM actividades"; // Incluye la columna de imagen
$result = $conn->query($query);

// Verifica si hay actividades
if ($result->num_rows > 0) {
    // Genera las cards
    while ($row = $result->fetch_assoc()) {
        $idActividad = htmlspecialchars($row['id']); // Obtener el ID de la actividad
        $nombreActividad = htmlspecialchars($row['nombre']); // Escapar caracteres especiales
        $imagenActividad = htmlspecialchars($row['imagen']); // Obtener la ruta de la imagen y escapar caracteres

        echo '
        <div class="card m-4" style="width: 18rem;">
            <div style="position: relative;">
                <img src="' . $imagenActividad . '" class="card-img-top" alt="' . $nombreActividad . '" style="height: 200px; width: 100%; object-fit: cover;">
            </div>
            <div class="card-body">
                <a href="turnos.php?id=' . $idActividad . '" style="text-decoration: none; color: inherit;">
                    <p class="card-text">' . $nombreActividad . '</p>
                </a>
                <div class="d-flex justify-content-between">
                    <form action="../SCRIPT/eliminar-actividad.php" method="POST" style="margin: 0;" onsubmit="return confirm(\'¿Estás seguro de que deseas eliminar esta actividad?\');">
                        <input type="hidden" name="id" value="' . $idActividad . '">
                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                    </form>
                    <a href="modificar-actividad.php?id=' . $idActividad . '" class="btn btn-warning btn-sm">Editar</a>
                </div>
            </div>
        </div>';
    }
} else {
    echo '<p>No hay actividades disponibles.</p>';
}

// Cerrar la conexión
$conn->close();
?>

