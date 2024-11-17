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
    <a class="navbar-brand" href="#">
      <img src="../IMAGENES/paradise-logo.png" alt="logo de hotel" width="75" height="75" class="d-inline-block align-text-center me-2">
      <span class="fs-3 fw-bold text-white">Hotel Paradise</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav align-items-center">
        <li class="nav-item">
          <a class="nav-link active fs-5 text-white" aria-current="page" href="#">Entraste como: <b><?php echo $rol; ?></b></a>
        </li>
        <li class="nav-item">
          <a class="btn btn-nav px-4 ms-3 fs-5" type="button" href="../SCRIPT/logout.php">Salir</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<style>
/* Estilo del Navbar */
.navbar-custom {
    background-color: #3e99c6;
    padding: 15px 30px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); /* Sombra */
    border-bottom: 3px solid #3490b3; /* Borde inferior sutil */
}

.navbar-custom .navbar-brand {
    display: flex;
    align-items: center;
}

.navbar-custom .navbar-brand img {
    margin-right: 10px; /* Espacio entre logo y texto */
    border-radius: 50%; /* Logo redondeado */
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); /* Sombra del logo */
    border: 3px solid white;
}

.navbar-custom .navbar-brand span {
    color: white;
    font-size: 1.8rem;
    font-weight: bold;
}

/* Toggler icon personalizado */
.navbar-custom .navbar-toggler {
    border-color: rgba(255, 255, 255, 0.6);
}

.navbar-custom .navbar-toggler-icon {
    background-image: url('data:image/svg+xml;charset=utf8,%3Csvg viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg"%3E%3Cpath stroke="rgba(255, 255, 255, 0.8)" stroke-width="2" stroke-linecap="round" stroke-miterlimit="10" d="M4 7h22M4 15h22M4 23h22"/%3E%3C/svg%3E');
}

/* Estilo de enlace de navegación */
.navbar-custom .nav-link {
    color: white;
    margin: 0 15px;
    font-size: 1.1rem;
    font-weight: 500;
    transition: color 0.3s ease;
}

.navbar-custom .nav-link:hover {
    color: #d1ecf9;
}

/* Botón de Salir */
.btn-nav {
    background-color: #4bbbf2;
    color: white;
    border: 2px solid white;
    padding: 8px 15px;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.2s ease;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.15);
}

.btn-nav:hover {
    background-color: #3aa4d9;
    box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.2);
}

.btn-nav:active {
    transform: scale(0.97);
    opacity: 0.9;
}
</style>
