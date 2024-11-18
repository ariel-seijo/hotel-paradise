<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
include 'SCRIPT\login.php';

$error = isset($_SESSION['error']) ? $_SESSION['error'] : "";
unset($_SESSION['error']); // Limpiar la variable de sesión

if (!empty($error)) {
  echo '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>INICIO - Hotel Paradise</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" href="IMAGENES/paradise-icono.png" type="image/png">
  <link rel="stylesheet" href="ESTILOS/inicio-estilo.css">
</head>

<body>
  <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title fs-5" id="staticBackdropLabel">Inicio de sesión</h2>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="modal-body">
          <!-- Formulario de Login -->
          <form action="SCRIPT/login.php" method="POST" id="loginForm">
            <div class="mb-3">
              <label for="exampleInputEmail1" class="form-label">Correo electrónico</label>
              <input type="email" class="form-control" id="exampleInputEmail1" name="email" required>
            </div>
            <div class="mb-3">
              <label for="exampleInputPassword1" class="form-label">Contraseña</label>
              <input type="password" class="form-control" id="exampleInputPassword1" name="contraseña" required>
            </div>
            <button type="submit" class="btn btn-primary">Enviar</button>
            <p class="text-center mt-2"><a href="#" id="forgotPasswordLink">Olvidé mi contraseña</a></p>
          </form>

          <!-- Formulario de Restablecimiento de Contraseña -->
          <form id="resetForm" style="display: none;">
            <div class="mb-3">
              <label for="resetEmail" class="form-label">Correo electrónico</label>
              <input type="email" class="form-control" id="resetEmail" name="reset_email" required>
            </div>
            <button type="button" class="btn btn-primary" id="sendTokenBtn">Restablecer contraseña</button>
            <button type="button" class="btn btn-secondary" id="backToLogin">Volver</button>
          </form>

          <!-- Formulario de Verificación de Código -->
          <form id="verifyCodeForm" style="display: none;">
            <div class="mb-3">
              <h5>Ingresa el código que te enviamos por mail</h5>
              <label for="verificationCode" class="form-label">Código de verificación</label>
              <input type="text" class="form-control" id="verificationCode" name="token" required>
            </div>
            <button type="button" class="btn btn-primary" id="verifyTokenBtn">Verificar</button>
          </form>

          <!-- Formulario de Cambio de Contraseña -->
          <form id="changePasswordForm" style="display: none;">
            <div class="mb-3">
              <label for="newPassword" class="form-label">Nueva contraseña</label>
              <input type="password" class="form-control" id="newPassword" name="new_password" required>
            </div>
            <div class="mb-3">
              <label for="confirmPassword" class="form-label">Confirmar contraseña</label>
              <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required>
            </div>
            <button type="button" class="btn btn-primary" id="changePasswordBtn">Cambiar contraseña</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid d-flex flex-column align-items-center justify-content-center text-center">
    <h1 class="display-1">RESERVA DE ACTIVIDADES</h1>
    <h1 class="display-3">HOTEL PARADISE</h1>
    <div class="d-grid gap-2 col-4 mx-auto">
      <button class="btn btn-primary btn-login mt-4 mb-2 p-3" data-bs-toggle="modal" data-bs-target="#staticBackdrop" id="btnIniciarSesion" type="button">INICIAR SESIÓN</button>
      <a class="btn btn-primary btn-login p-3" type="button" href="PAGINAS/actividades.php">SOY INVITADO</a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Cambiar entre formularios
    document.getElementById('forgotPasswordLink').addEventListener('click', function() {
      document.getElementById('loginForm').style.display = 'none';
      document.getElementById('resetForm').style.display = 'block';
    });

    document.getElementById('backToLogin').addEventListener('click', function() {
      document.getElementById('resetForm').style.display = 'none';
      document.getElementById('verifyCodeForm').style.display = 'none';
      document.getElementById('changePasswordForm').style.display = 'none';
      document.getElementById('loginForm').style.display = 'block';
    });

    document.getElementById('sendTokenBtn').addEventListener('click', function() {
      const email = document.getElementById('resetEmail').value;
      fetch('SCRIPT/send_token.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: `reset_email=${email}`
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert(data.message);
            document.getElementById('resetForm').style.display = 'none';
            document.getElementById('verifyCodeForm').style.display = 'block';
          } else {
            alert(data.message);
          }
        });
    });

    document.getElementById('verifyTokenBtn').addEventListener('click', function() {
      const email = document.getElementById('resetEmail').value;
      const token = document.getElementById('verificationCode').value;
      fetch('SCRIPT/verify_token.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: `email=${email}&token=${token}`
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            document.getElementById('verifyCodeForm').style.display = 'none';
            document.getElementById('changePasswordForm').style.display = 'block';
          } else {
            alert(data.message);
          }
        });
    });

    document.getElementById('changePasswordBtn').addEventListener('click', function() {
      const email = document.getElementById('resetEmail').value;
      const newPassword = document.getElementById('newPassword').value;
      const confirmPassword = document.getElementById('confirmPassword').value;

      if (newPassword !== confirmPassword) {
        alert('Las contraseñas no coinciden');
        return;
      }

      fetch('SCRIPT/update_password.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: `email=${email}&new_password=${newPassword}`
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert(data.message);
            document.getElementById('changePasswordForm').style.display = 'none';
            document.getElementById('loginForm').style.display = 'block';
          } else {
            alert(data.message);
          }
        });
    });
  </script>
</body>

<style>
  body {
    background-image: url('IMAGENES/hotel1.png');
  }

  .container-fluid {
    height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 20px;
  }

  h1.display-1,
  h1.display-3 {
    color: white;
  }

  .alert-danger {
    background-color: #ffb5ba;
    color: #8b0000;
    /* Texto rojo oscuro para mejor contraste */
    border: none;
  }

  /* Botón de Salir */

  .btn {
    background-color: #3aa4d9;
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

  .btn:hover {
    border: 2px solid white;
    background-color: #3e99c6;
    box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.2);
  }

  .btn:active {
    transform: scale(0.97);
    opacity: 0.9;
  }

  .modal-header {
    background-color: #4bbbf2;
    /* Color principal del encabezado modal */
    color: white;
  }

  .modal-content {
    border-radius: 8px;
  }

  .modal-footer {
    background-color: #4bbaf286;
    /* Fondo claro para el pie del modal */
  }

  .modal-body {
    background-color: white;
  }

  .form-control {
    border: 1px solid #62bfbd;
    /* Bordes de los campos de entrada */
    border-radius: 4px;
    transition: box-shadow 0.2s ease;
  }

  .form-control:focus {
    box-shadow: 0px 0px 8px rgba(98, 191, 189, 0.5);
    /* Efecto de enfoque */
  }
</style>

</html>