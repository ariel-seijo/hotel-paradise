<?php
// Incluir la conexión a la base de datos
include 'conexion.php';

// Inicializar variables de filtro
$filtroFecha = isset($_GET['fecha']) ? $_GET['fecha'] : '';
$filtroNombreHuesped = isset($_GET['nombre_huesped']) ? $_GET['nombre_huesped'] : '';
$filtroDNI = isset($_GET['dni']) ? $_GET['dni'] : '';
$filtroActividad = isset($_GET['nombre_actividad']) ? $_GET['nombre_actividad'] : '';

// Construir la consulta con filtros
$sql = "SELECT 
            reservas.id, 
            reservas.huesped_dni, 
            reservas.actividad_id, 
            reservas.cupo_id, 
            reservas.horario, 
            reservas.fecha,
            huespedes.huesped_nombre, 
            actividades.nombre AS nombre_actividad 
        FROM reservas 
        JOIN huespedes ON reservas.huesped_dni = huespedes.huesped_dni 
        JOIN actividades ON reservas.actividad_id = actividades.id 
        WHERE 1";

// Aplicar filtros si están presentes
if (!empty($filtroFecha)) {
    $sql .= " AND reservas.fecha = ?";
}
if (!empty($filtroNombreHuesped)) {
    $sql .= " AND huespedes.huesped_nombre LIKE ?";
}
if (!empty($filtroDNI)) {
    $sql .= " AND reservas.huesped_dni LIKE ?";
}
if (!empty($filtroActividad)) {
    $sql .= " AND actividades.nombre LIKE ?";
}

// Preparar la consulta
$stmt = $conn->prepare($sql);

// Vincular parámetros según los filtros
$params = [];
if (!empty($filtroFecha)) {
    $params[] = $filtroFecha;
}
if (!empty($filtroNombreHuesped)) {
    $params[] = '%' . $filtroNombreHuesped . '%';
}
if (!empty($filtroDNI)) {
    $params[] = '%' . $filtroDNI . '%';
}
if (!empty($filtroActividad)) {
    $params[] = '%' . $filtroActividad . '%';
}

// Vincular parámetros solo si hay filtros aplicados
if (!empty($params)) {
    $stmt->bind_param(str_repeat("s", count($params)), ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

?>

<div class="container mt-5">
    <!-- Formulario de búsqueda -->
    <form method="GET" action="administrador-registros.php">
        <div class="row mb-3" class="mt-2 mb-2">
            <div class="col-md-2">
                <input type="text" name="nombre_huesped" class="form-control" placeholder="Nombre del huésped" value="<?php echo htmlspecialchars($filtroNombreHuesped); ?>">
            </div>
            <div class="col-md-2">
                <input type="text" name="dni" class="form-control" placeholder="DNI del huésped" value="<?php echo htmlspecialchars($filtroDNI); ?>">
            </div>
            <div class="col-md-2">
                <input type="text" name="nombre_actividad" class="form-control" placeholder="Nombre de la actividad" value="<?php echo htmlspecialchars($filtroActividad); ?>">
            </div>
            <div class="col-md-2">
                <input type="date" name="fecha" class="form-control" value="<?php echo htmlspecialchars($filtroFecha); ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn w-100 btn-primary">Buscar</button>
            </div>
            <div class="col-md-2">
                <a href="administrador-registros.php" class="btn w-100 btn-secondary ms-2">Reiniciar</a>
            </div>
            <style>
                .col-md-2 input {
                    border: 2px solid #34a09e;
                }
            </style>
        </div>
    </form>

    <!-- Contenedor para hacer la tabla scrolleable -->
    <div class="table-responsive" style="max-height: 550px; overflow-y: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>DNI</th>
                    <th>Nombre del Huésped</th>
                    <th>ID Actividad</th>
                    <th>Nombre de la Actividad</th>
                    <th>Horario</th>
                    <th>Fecha del Turno</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['huesped_dni']); ?></td>
                            <td><?php echo htmlspecialchars($row['huesped_nombre']); ?></td>
                            <td><?php echo htmlspecialchars($row['actividad_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre_actividad']); ?></td>
                            <td><?php echo htmlspecialchars($row['horario']); ?></td>
                            <td><?php echo htmlspecialchars($row['fecha']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No se encontraron reservas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>