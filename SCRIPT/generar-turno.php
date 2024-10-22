<?php
// Incluir la conexión a la base de datos
include 'conexion.php';

// Verificar si se ha enviado un ID de actividad
if (isset($_GET['id'])) {
    $actividadId = intval($_GET['id']); // Asegurarse de que el ID es un número entero

    // Consulta para obtener la actividad
    $stmt = $conn->prepare("SELECT cantidad FROM actividades WHERE id = ?");
    $stmt->bind_param("i", $actividadId);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Verificar si se encontró la actividad
    if ($resultado->num_rows > 0) {
        $actividad = $resultado->fetch_assoc();
        $cantidadTurnos = $actividad['cantidad'];

        // Generar los turnos
        for ($i = 0; $i < $cantidadTurnos; $i++) {
            $hora = date('H:i', strtotime('09:00') + ($i * 30 * 60)); // Cada turno de 30 minutos
            $collapseId = 'panelsStayOpen-collapse' . ($i + 1); // ID para cada sección del acordeón

            echo '
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading' . ($i + 1) . '">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#' . $collapseId . '" aria-expanded="false" aria-controls="' . $collapseId . '">
                        <div class="row">
                            <div class="col-2">' . $hora . '</div>
                            <div class="col-3">Turno disponible</div>
                            <div class="col-3">Ocupado: 0 / 0</div>
                            <div class="col-4"></div>
                        </div>
                    </button>
                </h2>
                <div id="' . $collapseId . '" class="accordion-collapse collapse" data-bs-parent="#accordionPanelsStayOpenExample">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-2">1 / 2</div>
                            <div class="col-3">Ocupado</div>
                            <div class="col-3">Nombre de huésped</div>
                            <div class="col-4"></div>
                        </div>
                    </div>
                </div>
            </div>';
        }
    } else {
        echo "Actividad no encontrada.";
    }
} else {
    echo "No se proporcionó ID de actividad.";
}

// Cerrar conexión
$conn->close();
?>




