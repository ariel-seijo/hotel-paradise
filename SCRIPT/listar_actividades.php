<?php
// listar_actividades.php

// Conexión a la base de datos
include 'conexion.php';

// Inicializa la variable de búsqueda
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Consulta para obtener las actividades
$sql = "SELECT * FROM actividades";
if (!empty($search)) {
    $sql .= " WHERE nombre LIKE ?";
}

$stmt = $conn->prepare($sql);
if (!empty($search)) {
    $search_param = "%" . $search . "%";
    $stmt->bind_param("s", $search_param);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container mt-5">
    <h1 class="mb-4">Listado de Actividades</h1>

    <div class="d-flex justify-content-between mb-4">
        <!-- Formulario de búsqueda -->
        <form class="form-inline" method="GET" action="">
            <input class="form-control mr-2" type="text" name="search" placeholder="Buscar actividad" value="<?php echo htmlspecialchars($search); ?>">
            <button class="btn btn-primary" type="submit">Buscar</button>
        </form>
        <!-- Botón para añadir nueva actividad -->
        <button class="btn btn-success" data-toggle="modal" data-target="#agregarActividadModal">Añadir Nueva Actividad</button>
    </div>

    <!-- Listado de actividades -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>
                        <?php if ($row['imagen']): ?>
                            <img src="<?php echo htmlspecialchars($row['imagen']); ?>" alt="Imagen de la actividad" class="activity-image img-thumbnail" style="max-width: 100px; max-height: 100px;">
                        <?php else: ?>
                            <span>Sin imagen</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                    <td>
                        <!-- Botón de editar -->
                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editarActividadModal" data-id="<?php echo $row['id']; ?>" onclick="cargarDatosActividad(this)">Editar</button>
                        <!-- Botón de eliminar -->
    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#confirmDeleteModal<?php echo $row['id']; ?>">
        Eliminar
    </button>

    <!-- Modal de confirmación -->
    <div class="modal fade" id="confirmDeleteModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar eliminación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar esta actividad?</p>
                </div>
                <div class="modal-footer">
                    <form action="../SCRIPT/eliminar_actividad.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modal para agregar actividad -->
<div class="modal fade" id="agregarActividadModal" tabindex="-1" role="dialog" aria-labelledby="agregarActividadModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregarActividadModalLabel">Agregar Nueva Actividad</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formAgregarActividad" method="POST" enctype="multipart/form-data" action="../SCRIPT/guardar_actividad.php">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="horario_inicio">Horario de Inicio</label>
                        <input type="time" class="form-control" id="horario_inicio" name="horario_inicio" required>
                    </div>
                    <div class="form-group">
                        <label for="horario_cierre">Horario de Cierre</label>
                        <input type="time" class="form-control" id="horario_cierre" name="horario_cierre" required>
                    </div>
                    <div class="form-group">
                        <label for="formato">Formato</label>
                        <select class="form-control" id="formato" name="formato" required>
                            <option value="individual">Individual</option>
                            <option value="grupal">Grupal</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="capacidad_turno">Capacidad por Turno</label>
                        <input type="number" class="form-control" id="capacidad_turno" name="capacidad_turno" min="1" value="1" required>
                    </div>
                    <div class="form-group">
                        <label for="duracion">Duración (en minutos)</label>
                        <input type="number" class="form-control" id="duracion" name="duracion" required>
                    </div>
                    <div class="form-group">
                        <label for="imagen">Imagen</label>
                        <input type="file" class="form-control-file" id="imagen" name="imagen" required>
                    </div>
                    <div class="form-group">
                        <label for="dia_inicio">Día de Inicio</label>
                        <select class="form-control" id="dia_inicio" name="dia_inicio" required>
                            <option value="lunes">Lunes</option>
                            <option value="martes">Martes</option>
                            <option value="miércoles">Miércoles</option>
                            <option value="jueves">Jueves</option>
                            <option value="viernes">Viernes</option>
                            <option value="sábado">Sábado</option>
                            <option value="domingo">Domingo</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="dia_fin">Día de Fin</label>
                        <select class="form-control" id="dia_fin" name="dia_fin" required>
                            <option value="lunes">Lunes</option>
                            <option value="martes">Martes</option>
                            <option value="miércoles">Miércoles</option>
                            <option value="jueves">Jueves</option>
                            <option value="viernes">Viernes</option>
                            <option value="sábado">Sábado</option>
                            <option value="domingo">Domingo</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar actividad -->
<div class="modal fade" id="editarActividadModal" tabindex="-1" role="dialog" aria-labelledby="editarActividadModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarActividadModalLabel">Editar Actividad</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditarActividad" method="POST" enctype="multipart/form-data" action="../SCRIPT/actualizar_actividad.php">
                <div class="modal-body">
                    <!-- Agregar un campo oculto para el ID -->
                    <input type="hidden" id="editar_id" name="id">
                    <div class="form-group">
                        <label for="editar_nombre">Nombre</label>
                        <input type="text" class="form-control" id="editar_nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="editar_descripcion">Descripción</label>
                        <textarea class="form-control" id="editar_descripcion" name="descripcion" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="editar_horario_inicio">Horario de Inicio</label>
                        <input type="time" class="form-control" id="editar_horario_inicio" name="horario_inicio" required>
                    </div>
                    <div class="form-group">
                        <label for="editar_horario_cierre">Horario de Cierre</label>
                        <input type="time" class="form-control" id="editar_horario_cierre" name="horario_cierre" required>
                    </div>
                    <div class="form-group">
                        <label for="editar_formato">Formato</label>
                        <select class="form-control" id="editar_formato" name="formato" required>
                            <option value="individual">Individual</option>
                            <option value="grupal">Grupal</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editar_capacidad_turno">Capacidad por Turno</label>
                        <input type="number" class="form-control" id="editar_capacidad_turno" name="capacidad_turno" min="1" value="1" required>
                    </div>
                    <div class="form-group">
                        <label for="editar_duracion">Duración (en minutos)</label>
                        <input type="number" class="form-control" id="editar_duracion" name="duracion" required>
                    </div>
                    <div class="form-group">
                        <label for="editar_imagen">Imagen</label>
                        <input type="file" class="form-control-file" id="editar_imagen" name="imagen">
                    </div>
                    <div class="form-group">
                        <label for="editar_dia_inicio">Día de Inicio</label>
                        <select class="form-control" id="editar_dia_inicio" name="dia_inicio" required>
                            <option value="lunes">Lunes</option>
                            <option value="martes">Martes</option>
                            <option value="miércoles">Miércoles</option>
                            <option value="jueves">Jueves</option>
                            <option value="viernes">Viernes</option>
                            <option value="sábado">Sábado</option>
                            <option value="domingo">Domingo</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editar_dia_fin">Día de Fin</label>
                        <select class="form-control" id="editar_dia_fin" name="dia_fin" required>
                            <option value="lunes">Lunes</option>
                            <option value="martes">Martes</option>
                            <option value="miércoles">Miércoles</option>
                            <option value="jueves">Jueves</option>
                            <option value="viernes">Viernes</option>
                            <option value="sábado">Sábado</option>
                            <option value="domingo">Domingo</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function cargarDatosActividad(button) {
        // Obtener el ID de la actividad desde el atributo data-id del botón
        var id = button.getAttribute('data-id');

        // Hacer una llamada AJAX para obtener los datos de la actividad
        fetch('../SCRIPT/obtener_actividad.php?id=' + id)
            .then(response => response.json())
            .then(data => {
                // Verifica si hay datos de la actividad
                if (data) {
                    // Llenar los campos del modal con los datos recibidos
                    document.getElementById('editar_id').value = data.id;
                    document.getElementById('editar_nombre').value = data.nombre;
                    document.getElementById('editar_descripcion').value = data.descripcion;
                    document.getElementById('editar_horario_inicio').value = data.horario_inicio;
                    document.getElementById('editar_horario_cierre').value = data.horario_cierre;
                    document.getElementById('editar_formato').value = data.formato;
                    document.getElementById('editar_capacidad_turno').value = data.capacidad_turno;
                    document.getElementById('editar_duracion').value = data.duracion;
                    document.getElementById('editar_dia_inicio').value = data.dia_inicio;
                    document.getElementById('editar_dia_fin').value = data.dia_fin;

                    // Si tienes un campo de imagen, puedes optar por mostrar la imagen actual
                    // document.getElementById('imagen_actual').src = data.imagen; // Asegúrate de tener una etiqueta de imagen en tu modal
                }
            })
            .catch(error => console.error('Error al cargar los datos de la actividad:', error));
    }
</script>