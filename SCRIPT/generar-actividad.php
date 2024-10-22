<?php
// Incluir la conexión a la base de datos
include 'conexion.php';

// Consulta para obtener las actividades
$query = "SELECT id, nombre FROM actividades"; // Incluye el ID para redirigir a turnos.php
$result = $conn->query($query);

// Verifica si hay actividades
if ($result->num_rows > 0) {
    // Genera las cards
    while ($row = $result->fetch_assoc()) {
        $idActividad = htmlspecialchars($row['id']); // Obtener el ID de la actividad
        $nombreActividad = htmlspecialchars($row['nombre']); // Escapar caracteres especiales

        echo '
        <div class="card m-4" style="width: 18rem;">
            <div style="position: relative;">
                <img src="../IMAGENES/actividad-gimnasio.jpg" class="card-img-top" alt="...">
                <div style="position: absolute; top: 0; right: 0; width: 40px; height: 40px; background-color: red;"></div>
            </div>
            <div class="card-body">
                <a href="turnos.php?id=' . $idActividad . '" style="text-decoration: none; color: inherit;">
                    <p class="card-text">' . $nombreActividad . '</p>
                </a>
            </div>
        </div>';
    }
} else {
    echo '<p>No hay actividades disponibles.</p>';
}

// Cerrar la conexión
$conn->close();
?>

