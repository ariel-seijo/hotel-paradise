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
<?php if (isset($_SESSION['mensaje'])): ?>
    <div class="alert alert-success" role="alert">
        <?php
        echo $_SESSION['mensaje']; // Mostrar el mensaje
        unset($_SESSION['mensaje']); // Eliminar el mensaje de la sesión
        ?>
    </div>
<?php endif; ?>
<div class="container mt-5">
    <div class="d-flex justify-content-between mb-4">
        <!-- Formulario de búsqueda -->
        <form class="form-inline" method="GET" action="">
            <input class="form-control mr-2 busqueda-filtrada" type="text" name="search" placeholder="Buscar actividad" value="<?php echo htmlspecialchars($search); ?>">
            <button class="btn btn-primary" type="submit">Buscar</button>
            <style>
                .busqueda-filtrada {
                    border: 3px solid #34a09e;
                }
            </style>
        </form>
        <!-- Botón para añadir nueva actividad -->
        <button class="btn btn-success" data-toggle="modal" data-target="#agregarActividadModal">Añadir Nueva Actividad</button>
    </div>

    <!-- Listado de actividades -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th class="col-25">Imagen</th>
                <th class="col-25">Nombre</th>
                <th class="col-25">Acciones</th>
                <th class="col-25">Horarios</th>
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
                    <td class="td-acciones">
                        <div class="action-buttons">
                            <!-- Botón de editar -->
                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editarActividadModal" data-id="<?php echo $row['id']; ?>" onclick="cargarDatosActividad(this)">Editar</button>

                            <!-- Botón de eliminar -->
                            <form id="eliminarActividadForm<?php echo $row['id']; ?>" action="../SCRIPT/eliminar_actividad.php" method="POST" style="display: inline;">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="button" class="btn btn-sm btn-danger" onclick="confirmarEliminar(<?php echo $row['id']; ?>)">Eliminar</button>
                            </form>
                        </div>
                    </td>
                    <td>
                        <!-- Botones de Agregar y Editar Horarios -->
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#agregarHorarioModal" onclick="abrirAgregarHorarioModal(<?php echo $row['id']; ?>)">
                            Agregar horario
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#editarHorariosModal" onclick="abrirEditarHorariosModal(<?php echo $row['id']; ?>)">
                            Editar horarios
                        </button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <script>
        function confirmarEliminar(id) {
            if (confirm("¿Estás seguro de que deseas eliminar esta actividad?")) {
                document.getElementById("eliminarActividadForm" + id).submit();
            }
        }
    </script>

    <style>
        .col-25 {
            width: 25%;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
            width: 100%;
            /* Ajusta el espacio entre botones */
        }

        .action-buttons .btn {
            width: 100px;
        }

        /* Estilos de los botones */
        .btn-primary {
            background-color: #62bfbd;
            border: 2px solid #34a09e;
        }

        .btn-primary:focus,
        .btn-primary:hover,
        .btn-primary:active {
            background-color: #34a09e;
            border: 2px solid #34a09e;
        }

        .btn-warning {
            color: white;
            background-color: #4bbbf2;
            border: 2px solid #2aa9e8;
        }

        .btn-warning:focus,
        .btn-warning:hover,
        .btn-warning:active {
            color: white;
            background-color: #2aa9e8;
            border: 2px solid #2aa9e8;
        }

        .btn-danger {
            background-color: #ffb5ba;
            border: 2px solid #f36f78;
        }

        .btn-danger:focus,
        .btn-danger:hover,
        .btn-danger:active {
            background-color: #f36f78;
            border: 2px solid #f36f78;
        }

        .btn-secondary {
            background-color: #4bbbf2;
            border: 2px solid #2aa9e8;
        }

        .btn-secondary:focus,
        .btn-secondary:hover,
        .btn-secondary:active {
            background-color: #2aa9e8;
            border: 2px solid #2aa9e8;
        }

        .btn-success {
            background-color: #62bfbd;
            border: 2px solid #34a09e;
        }

        .btn-success:focus,
        .btn-success:hover,
        .btn-success:active {
            background-color: #34a09e;
            border: 2px solid #34a09e;
        }
    </style>

</div>

<!-- Modal de Editar Horarios -->
<div class="modal fade" id="editarHorariosModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Horarios</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="horariosContainer">
                <!-- Los campos de horario se cargarán aquí mediante JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Agregar Horario -->
<div class="modal fade" id="agregarHorarioModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Horario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formAgregarHorario">
                    <input type="hidden" id="actividadId">
                    <h4>Horario debe estar entre A y B:</h4>
                    <div class="form-group">
                        <label for="nuevoHorario">Horario</label>
                        <input type="time" id="nuevoHorario" class="form-control" required>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="guardarHorario()">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar actividad -->
<div class="modal fade" id="agregarActividadModal" tabindex="-1" role="dialog" aria-labelledby="agregarActividadModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <style>
                .modal-content {
                    min-width: 800px;
                }

                .img-preview {
                    max-width: 50%;
                    margin-top: 10px;
                }
            </style>
            <div class="modal-header">
                <h5 class="modal-title" id="agregarActividadModalLabel">Agregar Nueva Actividad</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formAgregarActividad" method="POST" enctype="multipart/form-data" action="../SCRIPT/guardar_actividad.php" onsubmit="return confirmarAgregarActividad();">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div class="form-group">
                                <label for="imagen">Imagen</label>
                                <input type="file" class="form-control-file" id="imagen" name="imagen" required accept="image/*">
                                <img id="imgPreview" class="img-preview" src="" alt="Previsualización" style="display:none;">
                            </div>
                            <div class="form-group">
                                <label for="descripcion">Descripción</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="col-6">
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
                                <select class="form-control" id="formato" name="formato" required onchange="actualizarCapacidad();">
                                    <option value="individual">Individual</option>
                                    <option value="grupal">Grupal</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="capacidad_turno">Capacidad por Turno</label>
                                <input type="number" class="form-control" id="capacidad_turno" name="capacidad_turno" min="1" value="1" readonly required>
                            </div>
                            <div class="form-group">
                                <label for="duracion">Duración (en minutos)</label>
                                <input type="number" class="form-control" id="duracion" name="duracion" required>
                            </div>
                        </div>
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

<script>
    function actualizarCapacidad() {
        var formato = document.getElementById("formato").value;
        var capacidadTurno = document.getElementById("capacidad_turno");
        if (formato == "individual") {
            capacidadTurno.value = 1; // Establecer capacidad a 1
            capacidadTurno.readOnly = true; // Deshabilitar el campo
        } else {
            capacidadTurno.readOnly = false; // Habilitar el campo para grupos
        }
    }
</script>

<script>
    function confirmarAgregarActividad() {
        return confirm("¿Estás seguro de que deseas agregar esta actividad?");
    }
</script>

<script>
    document.getElementById('imagen').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const imgPreview = document.getElementById('imgPreview');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imgPreview.src = e.target.result;
                imgPreview.style.display = 'block'; // Mostrar la imagen
            }
            reader.readAsDataURL(file);
        } else {
            imgPreview.src = '';
            imgPreview.style.display = 'none'; // Ocultar si no hay archivo
        }
    });
</script>


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
            <form id="formEditarActividad" method="POST" enctype="multipart/form-data" action="../SCRIPT/actualizar_actividad.php" onsubmit="return confirmarActualizacion();">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <input type="hidden" id="editar_id" name="id">
                            <div class="form-group">
                                <label for="editar_nombre">Nombre</label>
                                <input type="text" class="form-control" id="editar_nombre" name="nombre" required>
                            </div>
                            <div class="form-group">
                                <label for="editar_imagen">Seleccionar Nueva Imagen</label>
                                <input type="file" class="form-control-file" id="editar_imagen" name="imagen" accept="image/*" onchange="mostrarVistaPrevia(this)">
                            </div>
                            <div class="form-group">
                                <label for="editar_imagen_actual">Imagen seleccionada</label>
                                <img id="editar_imagen_actual" class="img-preview" src="" alt="Imagen actual" style="max-width: 50%; display: none;">
                            </div>
                            <div class="form-group">
                                <label for="editar_descripcion">Descripción</label>
                                <textarea class="form-control" id="editar_descripcion" name="descripcion" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="col-6">
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
                        </div>
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
    function confirmarActualizacion() {
        return confirm("¿Estás seguro de que deseas actualizar esta actividad?");
    }
</script>
<script>
    let horarioInicio, horarioCierre;

    function abrirEditarHorariosModal(actividadId) {
        // Obtener el rango de horarios y los horarios de la actividad
        fetch(`../SCRIPT/obtenerRangoHorario.php?id=${actividadId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    horarioInicio = data.horario_inicio;
                    horarioCierre = data.horario_cierre;

                    // Obtener los horarios asociados a la actividad
                    fetch(`../SCRIPT/obtenerHorariosActividad.php?id=${actividadId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const horariosContainer = document.getElementById('horariosContainer');
                                horariosContainer.innerHTML = ''; // Limpiar el contenedor

                                data.horarios.forEach(horario => {
                                    const horarioRow = document.createElement('div');
                                    horarioRow.className = 'form-group d-flex align-items-center';

                                    // Campo de horario
                                    const horarioInput = document.createElement('input');
                                    horarioInput.type = 'time';
                                    horarioInput.value = horario.horario;
                                    horarioInput.className = 'form-control mr-2';
                                    horarioInput.disabled = true; // Deshabilitado por defecto
                                    horarioInput.dataset.horarioId = horario.id;

                                    // Botón Editar
                                    const editButton = document.createElement('button');
                                    editButton.className = 'btn btn-warning btn-sm mr-2';
                                    editButton.innerText = 'Editar';
                                    editButton.onclick = () => {
                                        horarioInput.disabled = !horarioInput.disabled;
                                        editButton.innerText = horarioInput.disabled ? 'Editar' : 'Actualizar';

                                        if (horarioInput.disabled) {
                                            actualizarHorario(horario.id, horarioInput.value);
                                        }
                                    };

                                    // Botón Eliminar
                                    const deleteButton = document.createElement('button');
                                    deleteButton.className = 'btn btn-danger btn-sm';
                                    deleteButton.innerText = 'Eliminar';
                                    deleteButton.onclick = () => eliminarHorario(horario.id);

                                    // Agregar elementos al contenedor
                                    horarioRow.appendChild(horarioInput);
                                    horarioRow.appendChild(editButton);
                                    horarioRow.appendChild(deleteButton);
                                    horariosContainer.appendChild(horarioRow);
                                });
                            } else {
                                alert(data.error);
                            }
                        });
                } else {
                    alert(data.error);
                }
            })
            .catch(error => console.error('Error al cargar el rango de horarios o los horarios de la actividad:', error));
    }

    function actualizarHorario(horarioId, nuevoHorario) {
        // Verificar que el horario esté dentro del rango permitido
        if (nuevoHorario >= horarioInicio && nuevoHorario <= horarioCierre) {
            // Enviar el nuevo horario al servidor
            fetch('../SCRIPT/actualizarHorario.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        horario_id: horarioId,
                        horario: nuevoHorario
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Horario actualizado correctamente');
                    } else {
                        alert(data.error || 'Error al actualizar el horario');
                    }
                })
                .catch(error => console.error('Error al actualizar el horario:', error));
        } else {
            alert(`El horario debe estar entre ${horarioInicio} y ${horarioCierre}.`);
        }
    }

    function eliminarHorario(horarioId) {
        if (confirm('¿Estás seguro de que deseas eliminar este horario?')) {
            fetch(`../SCRIPT/eliminarHorario.php?id=${horarioId}`, {
                    method: 'GET'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Horario eliminado correctamente');
                        $('#editarHorarioModal').modal('hide'); // Cerrar el modal
                        location.reload();;
                    } else {
                        alert(data.error || 'Error al eliminar el horario');
                    }
                })
                .catch(error => console.error('Error al eliminar el horario:', error));
        }
    }

    function abrirAgregarHorarioModal(actividadId) {
        // Guardar el ID de la actividad en el modal
        document.getElementById('actividadId').value = actividadId;

        // Hacer una solicitud Ajax para obtener el horario_inicio y horario_cierre de la actividad
        fetch(`../SCRIPT/obtenerRangoHorario.php?id=${actividadId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    horarioInicio = data.horario_inicio;
                    horarioCierre = data.horario_cierre;
                    // Actualizar el encabezado del modal con el rango de horarios
                    const encabezadoHorario = document.querySelector('#agregarHorarioModal .modal-body h4');
                    encabezadoHorario.textContent = `Horario debe estar entre ${horarioInicio} y ${horarioCierre}`;
                } else {
                    alert(data.error);
                }
            })
            .catch(error => console.error('Error al obtener el rango de horarios:', error));
    }

    function guardarHorario() {
        const actividadId = document.getElementById('actividadId').value;
        const horario = document.getElementById('nuevoHorario').value;

        // Verificar si el horario está en el rango permitido
        if (horario >= horarioInicio && horario <= horarioCierre) {
            // Enviar el horario al servidor para guardarlo
            fetch('../SCRIPT/agregarHorario.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        actividad_id: actividadId,
                        horario: horario
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Horario agregado correctamente');
                        $('#agregarHorarioModal').modal('hide'); // Cerrar el modal
                        location.reload(); // Recargar la página
                    } else {
                        alert(data.error || 'Error al agregar el horario');
                    }
                })
                .catch(error => console.error('Error al agregar el horario:', error));
        } else {
            alert(`El horario debe estar entre ${horarioInicio} y ${horarioCierre}.`);
        }
    }

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

                    // Cargar y mostrar la imagen actual
                    const imagenActual = document.getElementById('editar_imagen_actual');
                    imagenActual.src = data.imagen; // Suponiendo que 'data.imagen' contiene la URL de la imagen
                    imagenActual.style.display = 'block'; // Mostrar la imagen
                }
            })
            .catch(error => console.error('Error al cargar los datos de la actividad:', error));
    }

    // Función para mostrar la vista previa de la nueva imagen
    function mostrarVistaPrevia(input) {
        const imagenPreview = document.getElementById('editar_imagen_actual');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagenPreview.src = e.target.result; // Actualizar la vista previa con la nueva imagen
            }
            reader.readAsDataURL(input.files[0]); // Leer el archivo como una URL de datos
        }
    }
</script>