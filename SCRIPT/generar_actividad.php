<?php
// Incluir archivo de conexión a la base de datos
include 'conexion.php';

// Consulta para obtener todas las actividades
$query = "SELECT * FROM actividades";
$result = $conn->query($query);
?>

<div class="container mt-4">
    <div class="row">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <a href="../PAGINAS/turnos.php?id=<?php echo $row['id']; ?>" class="card text-decoration-none text-dark">
                        <img src="<?php echo htmlspecialchars($row['imagen']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['nombre']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['nombre']); ?></h5>
                        </div>
                    </a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>