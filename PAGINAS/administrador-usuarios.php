<?php
include '../SCRIPT/conexion.php';

// Buscar usuarios según el criterio
$searchQuery = "";
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $query = "SELECT * FROM usuarios WHERE email LIKE ? OR (CASE WHEN isAdmin=1 THEN 'Administrador' ELSE 'Recepcionista' END) LIKE ?";
    $stmt = $conn->prepare($query);
    $searchTerm = '%' . $searchQuery . '%';
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
} else {
    $query = "SELECT * FROM usuarios";
    $stmt = $conn->prepare($query);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USUARIOS - Hotel Paradise</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../ESTILOS/panel-administrador-estilo.css">
    <style>
        .scrollable-table {
            max-height: 400px;
            overflow-y: auto;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: space-between;
        }

        /* Asegurar el mismo ancho en cada columna */
        .w-25 {
            width: 25%;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div class="container contenedor-principal">
        <div class="row">
            <div class="col-3 seccion-panel gap-2">
                <h1 class="display-6 text-center">PANEL DE ADMINISTRACIÓN</h1>
                <a class="btn btn-primary w-100 btnPanel" href="administrador-actividades.php" role="button">Actividades</a>
                <a class="btn btn-primary w-100 btnPanel" href="administrador-usuarios.php" role="button">Usuarios</a>
                <a class="btn btn-primary w-100 btnPanel" href="administrador-registros.php" role="button">Registro de turnos</a>
            </div>
            <div class="col-9 seccion-elegida">
                <h1 class="display-1 text-center">PANEL DE USUARIOS</h1>
                <?php if (isset($_SESSION['mensaje'])): ?>
                    <div class="alert alert-success" role="alert">
                        <?php
                        echo $_SESSION['mensaje']; // Mostrar el mensaje
                        unset($_SESSION['mensaje']); // Eliminar el mensaje de la sesión
                        ?>
                    </div>
                <?php endif; ?>
                <div class="d-flex justify-content-between align-items-center mb-3 px-4">
                    <form method="get" class="form-inline">
                        <input type="text" name="search" placeholder="Buscar por nombre o tipo de usuario" value="<?= $searchQuery ?>" class="form-control mr-2">
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </form>
                    <button class="btn btn-success" data-toggle="modal" data-target="#addUserModal">Añadir Usuario</button>
                </div>

                <div class="table-responsive scrollable-table px-4">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 25%;">Nombre de Usuario</th>
                                <th style="width: 25%;">Tipo de Usuario</th>
                                <th style="width: 25%;"></th>
                                <th style="width: 25%;">Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td style="width: 25%;"><?= explode('@', $row['email'])[0]; ?></td>
                                    <td style="width: 25%;">
                                        <div class="user-info d-flex align-items-center justify-content-between">
                                            <select class="form-control user-type" data-id="<?= $row['id'] ?>">
                                                <option value="0" <?= $row['isAdmin'] == 0 ? 'selected' : '' ?>>Recepcionista</option>
                                                <option value="1" <?= $row['isAdmin'] == 1 ? 'selected' : '' ?>>Administrador</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td style="width: 25%;">
                                        <button class="btn btn-warning btn-sm update-btn" style="display: none;" data-id="<?= $row['id'] ?>">Cambiar Permiso</button>
                                    </td>
                                    <td style="width: 25%;">
                                        <button class="btn btn-danger btn-sm delete-btn" data-id="<?= $row['id'] ?>">Eliminar</button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Modal para añadir usuario -->
            <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form id="addUserForm" action="../SCRIPT/add_user.php" method="post" onsubmit="return confirmarCreacionUsuario();">
                            <div class="modal-header">
                                <h5 class="modal-title">Añadir Usuario</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Email:</label>
                                    <input type="email" name="email" required class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Contraseña:</label>
                                    <input type="password" name="contraseña" required class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Tipo de Usuario:</label>
                                    <select name="isAdmin" class="form-control">
                                        <option value="0">Recepcionista</option>
                                        <option value="1">Administrador</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Crear Usuario</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <script>
                function confirmarCreacionUsuario() {
                    return confirm("¿Estás seguro de que deseas crear este usuario?");
                }
            </script>

        </div>
    </div>    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Mostrar botón Cambiar Permiso al cambiar el tipo de usuario
            $('.user-type').on('change', function() {
                $(this).closest('td').siblings().find('.update-btn').show();
            });

            // Cambiar Permiso en la BD
            $('.update-btn').on('click', function() {
                var userId = $(this).data('id');
                var newType = $(this).closest('tr').find('.user-type').val();
                $.post('../SCRIPT/update_user.php', {
                    id: userId,
                    isAdmin: newType
                }, function() {
                    alert('Permiso actualizado correctamente');
                    location.reload();
                });
            });

            // Eliminar Usuario
            $('.delete-btn').on('click', function() {
                if (confirm('¿Estás seguro de eliminar este usuario?')) {
                    var userId = $(this).data('id');
                    $.post('../SCRIPT/delete_user.php', {
                        id: userId
                    }, function() {
                        alert('Usuario eliminado');
                        location.reload();
                    });
                }
            });
        });
    </script>

</body>

</html>