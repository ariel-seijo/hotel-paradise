<?php
session_start();
$rol = 'Visualizador'; // Valor por defecto si no hay sesión activa

if (isset($_SESSION['isAdmin'])) {
  if ($_SESSION['isAdmin'] == 1) {
    $rol = 'Administrador';
  } elseif ($_SESSION['isAdmin'] == 0) {
    $rol = 'Recepcionista';
  }
}
?>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand p-0 m-0" href="#">
      <img src="../IMAGENES/logo-muestra.png" alt="logo de hotel" width="75" height="75" class="d-inline-block align-text-center">
      Hotel Paradise
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active fs-4" aria-current="page" href="#">Usuario <?php echo $rol; ?></a>
        </li>
        <li class="nav-item">
          <a class="btn btn-primary px-5 fs-4" type="button" aria-current="page" href="../SCRIPT/logout.php">Salir</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<style>
  .nav-item {
    padding-left: 10px;
  }
</style>