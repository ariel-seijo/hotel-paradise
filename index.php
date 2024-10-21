<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
} // Asegúrate de iniciar la sesión
include 'SCRIPT\login.php';


$error = isset($_SESSION['error']) ? $_SESSION['error'] : "";
unset($_SESSION['error']); // Limpiar la variable de sesión

if (!empty($error)) {
    // Mostrar el mensaje de error
    echo '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>';
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>INICIO - Hotel Paradise</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="ESTILOS\inicio-estilo.css">
</head>

<body>
  <div class="alert alert-danger fs-4 text-center" role="alert" style="display: none;" id="Alerta">
    Los datos ingresados son incorrectos
  </div>
  <!-- MODAL  -->
  <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Inicio de sesión</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- FORMULARIO -->
          <form action="SCRIPT/login.php" method="POST" id="loginForm">
            <div class="mb-3">
              <label for="exampleInputEmail1" class="form-label">Correo electrónico</label>
              <input type="email" class="form-control" id="exampleInputEmail1" name="email" aria-describedby="emailHelp" required>
            </div>
            <div class="mb-3">
              <label for="exampleInputPassword1" class="form-label">Contraseña</label>
              <input type="password" class="form-control" id="exampleInputPassword1" name="contraseña" required>
            </div>
            <button type="submit" class="btn btn-primary">Enviar</button>
          </form>
        </div>
        <div class="modal-footer d-flex justify-content-center">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>


  <div class="container-fluid d-flex flex-column align-items-center justify-content-center text-center">
    <h1 class="display-1">RESERVA DE ACTIVIDADES</h1>
    <h1 class="display-3">HOTEL PARADISE</h1>
    <div class="d-grid gap-2 col-4 mx-auto">
      <button class="btn btn-primary mt-4 mb-2 p-3" data-bs-toggle="modal" data-bs-target="#staticBackdrop" id="btnIniciarSesion" type="button">Iniciar Sesión</button>
      <a class="btn btn-primary p-3" type="button" href="PAGINAS\actividades.php">Ingresar como invitado</a>
    </div>
  </div>
 
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>