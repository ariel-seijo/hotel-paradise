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

<nav class="navbar navbar-expand-lg navbar-custom">
  <div class="container-fluid">
    <a class="navbar-brand p-0 m-0 d-flex align-items-center" href="#">
      <img src="../IMAGENES/logo-muestra.png" alt="logo de hotel" width="75" height="75" class="d-inline-block align-text-center me-2">
      <span class="fs-3 fw-bold text-white">Hotel Paradise</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active fs-4 text-white" aria-current="page" href="#">Entraste como: <b><?php echo $rol; ?><b></a>
        </li>
        <li class="nav-item">
          <a class="btn btn-custom px-5 fs-4" type="button" href="../SCRIPT/logout.php">Salir</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<style>
  /* Fondo y estilo de la barra de navegación */
  .navbar-custom {
    background-color: #34495e ; /* Fondo oscuro para el navbar */
    padding: 15px;
  }

  /* Estilo de los enlaces del navbar */
  .navbar-custom .nav-link {
    color: #ffffff;
    padding: 10px;
  }

  /* Botón personalizado con el color especificado */
  .btn-custom {
    background-color: #4bbbf2;
    color: white;
    border: none;
    transition: background-color 0.3s ease;
  }

  /* Efecto hover para el botón */
  .btn-custom:hover {
    background-color: #3aa3d4; /* Color más oscuro en hover */
  }

  /* Estilo de los enlaces de navegación en hover */
  .navbar-custom .nav-link:hover {
    color: #4bbbf2; /* Cambia el color de los enlaces a #4bbbf2 en hover */
  }

  /* Alineación y espaciado del ícono y el texto */
  .navbar-brand {
    display: flex;
    align-items: center;
    color: white;
    font-size: 1.5em;
  }
  
  /* Espaciado entre elementos de la navbar */
  .nav-item {
    margin-left: 15px;
  }


  span:hover {
    color: black;
    width: 10px;
  }
</style>
