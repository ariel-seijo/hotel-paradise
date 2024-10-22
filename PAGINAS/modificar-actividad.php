<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMINISTRACIÓN - Hotel Paradise</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../ESTILOS/panel-administrador-estilo.css">
    <script>
        function generarHorarios() {
            const cantidadTurnos = document.getElementById('cantidadTurnos').value;
            const duracionTurno = parseInt(document.getElementById('duracionTurno').value, 10);
            const contenedorHorarios = document.getElementById('contenedorHorarios');
            contenedorHorarios.innerHTML = '';

            const horaInicio = document.getElementById('horarioInicio').value;
            const horaCierre = document.getElementById('horarioCierre').value;

            if (!horaInicio || !horaCierre || isNaN(duracionTurno) || duracionTurno <= 0) {
                alert("Por favor, asegúrate de ingresar el horario de inicio, cierre y una duración válida.");
                return;
            }

            const inicioBase = new Date(`1970-01-01T${horaInicio}:00`);
            const cierre = new Date(`1970-01-01T${horaCierre}:00`);

            for (let i = 0; i < cantidadTurnos; i++) {
                const inicio = new Date(inicioBase); // Crear una copia para cada turno
                const div = document.createElement('div');
                div.className = 'form-group mb-2';
                div.innerHTML = `
                <label for="horario${i}">Horario Turno ${i + 1}:</label>
                <select class="form-control" id="horario${i}" name="horario${i}" required>
                    ${generarOpcionesHorario(inicio, cierre, duracionTurno)}
                </select>
            `;
                contenedorHorarios.appendChild(div);
                // Aumenta el inicio en la duración del turno para el siguiente turno
                inicioBase.setMinutes(inicioBase.getMinutes() + duracionTurno);
            }
        }

        function generarOpcionesHorario(inicio, cierre, duracionTurno) {
            let opciones = '';
            while (inicio <= cierre) {
                const formatoHora = inicio.toTimeString().slice(0, 5); // Formato HH:MM
                opciones += `<option value="${formatoHora}">${formatoHora}</option>`;
                inicio.setMinutes(inicio.getMinutes() + duracionTurno); // Incremento según la duración del turno
            }
            return opciones;
        }

        function mostrarCapacidad() {
            const formatoTurno = document.getElementById('formatoTurno').value;
            const capacidadTurnoDiv = document.getElementById('capacidadTurnoDiv');
            const capacidadTurno = document.getElementById('capacidadTurno');

            if (formatoTurno === 'grupal') {
                capacidadTurnoDiv.style.display = 'block';
                capacidadTurno.setAttribute('required', 'true'); // Añadir el atributo 'required'
            } else {
                capacidadTurnoDiv.style.display = 'none';
                capacidadTurno.removeAttribute('required'); // Eliminar el atributo 'required'
            }
        }

        function confirmarEnvio(event) {
            if (!confirm("¿Deseas confirmar el envío de la actividad?")) {
                event.preventDefault(); // Cancelar el envío del formulario si el usuario presiona "Cancelar"
            }
        }
    </script>

</head>

<body>
    <?php include 'navbar.php'; ?>
    <div class="container contenedor-principal">
        <div class="row">
            <div class="col-3 h-100vh seccion-panel gap-2">
                <h1 class="display-6 text-center">PANEL DE ADMINISTRACIÓN</h1>
                <a class="btn btn-primary w-100 btnPanel" href="administrador-actividades.php" role="button">Actividades</a>
                <a class="btn btn-primary w-100 btnPanel" href="#" role="button">Usuarios</a>
                <a class="btn btn-primary w-100 btnPanel" href="#" role="button">Registro de turnos</a>
            </div>
            <div class="col-9 seccion-elegida">
                <h1 class="display-1 text-center">AGREGAR ACTIVIDAD</h1>
                <form action="../SCRIPT/agregar-actividad.php" method="POST" enctype="multipart/form-data" onsubmit="confirmarEnvio(event)">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label for="imagen">Selecciona una imagen:</label>
                                <input type="file" id="imagen" name="imagen" accept="image/*" class="form-control" required>
                            </div>

                            <div class="form-group mb-2">
                                <label for="nombreActividad">Nombre de la actividad:</label>
                                <input type="text" id="nombreActividad" name="nombreActividad" class="form-control" required>
                            </div>

                            <div class="form-group mb-2">
                                <label for="descripcionActividad">Descripción de la actividad:</label>
                                <textarea id="descripcionActividad" name="descripcionActividad" class="form-control" required></textarea>
                            </div>

                            <div class="form-group mb-2">
                                <label for="diaInicio">Día de inicio:</label>
                                <select id="diaInicio" name="diaInicio" class="form-control" required>
                                    <option value="">Seleccione un día</option>
                                    <option value="lunes">Lunes</option>
                                    <option value="martes">Martes</option>
                                    <option value="miércoles">Miércoles</option>
                                    <option value="jueves">Jueves</option>
                                    <option value="viernes">Viernes</option>
                                    <option value="sábado">Sábado</option>
                                    <option value="domingo">Domingo</option>
                                </select>
                            </div>

                            <div class="form-group mb-2">
                                <label for="diaCierre">Día de cierre:</label>
                                <select id="diaCierre" name="diaCierre" class="form-control" required>
                                    <option value="">Seleccione un día</option>
                                    <option value="lunes">Lunes</option>
                                    <option value="martes">Martes</option>
                                    <option value="miércoles">Miércoles</option>
                                    <option value="jueves">Jueves</option>
                                    <option value="viernes">Viernes</option>
                                    <option value="sábado">Sábado</option>
                                    <option value="domingo">Domingo</option>
                                </select>
                            </div>

                            <div class="form-group mb-2">
                                <label for="horarioInicio">Horario de inicio:</label>
                                <input type="time" id="horarioInicio" name="horarioInicio" class="form-control" required>
                            </div>

                            <div class="form-group mb-2">
                                <label for="horarioCierre">Horario de cierre:</label>
                                <input type="time" id="horarioCierre" name="horarioCierre" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label for="formatoTurno">Formato de turno:</label>
                                <select id="formatoTurno" name="formatoTurno" class="form-control" required onchange="mostrarCapacidad()">
                                    <option value="">Seleccione una opción</option>
                                    <option value="individual">Individual</option>
                                    <option value="grupal">Grupal</option>
                                </select>
                            </div>

                            <div id="capacidadTurnoDiv" class="form-group mb-2" style="display: none;">
                                <label for="capacidadTurno">Capacidad del turno:</label>
                                <input type="number" id="capacidadTurno" name="capacidadTurno" class="form-control" required>
                            </div>

                            <div class="form-group mb-2">
                                <label for="duracionTurno">Duración del turno (minutos):</label>
                                <input type="number" id="duracionTurno" name="duracionTurno" class="form-control" required>
                            </div>

                            <div class="form-group mb-2">
                                <label for="cantidadTurnos">Cantidad de turnos:</label>
                                <input type="number" id="cantidadTurnos" name="cantidadTurnos" class="form-control" min="1" required oninput="generarHorarios()">
                            </div>

                            <div id="contenedorHorarios"></div>

                            <button type="submit" class="btn btn-primary mt-2">Enviar</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>