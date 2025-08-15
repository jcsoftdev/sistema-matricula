<!DOCTYPE html>
<html lang="es" data-theme="theme-default">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Iniciar Sesión - I.E.P. Shekiná School</title>

    <!-- Favicon -->
    <link rel="icon" href="../assets/img/favicon.ico" type="image/x-icon" />

    <!-- Google Fonts & Bootstrap Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <style>
      body {
        background: url('../assets/img/fondo_login.png') no-repeat center center fixed;
        background-size: cover;
        font-family: 'Public Sans', sans-serif;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
      }

      .card {
        background: rgba(255, 255, 255, 0.93);
        color: #2f4684;
        border-radius: 1rem;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        border: none;
        animation: fadeIn 0.3s ease-in-out;
        max-width: 450px;
        width: 100%;
      }

      @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
      }

      .app-logo {
        width: 160px;
        margin-bottom: 1rem;
      }

      .form-label {
        font-weight: 500;
      }

      .form-control {
        background-color: #f1f4f9;
        border: 1px solid #ccd6e0;
        color: #2f4684;
      }

      .form-control::placeholder {
        color: #95a3b3;
      }

      .form-control:focus {
        background-color: #ffffff;
        border-color: #4CAF50;
        box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.4);
      }

      .btn-primary {
        background-color: #2f4684;
        border: none;
        transition: background-color 0.3s ease-in-out;
      }

      .btn-primary:hover {
        background-color: #4CAF50;
        color: #ffffff;
      }

      .password-toggle {
        cursor: pointer;
      }

      a.small {
        color: #4CAF50;
        text-decoration: none;
      }

      a.small:hover {
        text-decoration: underline;
      }
    </style>
  </head>

  <body>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
          <div class="card p-4">
            <div class="text-center">
              <img src="../assets/img/logo.png" alt="Logo" class="app-logo rounded" />
              <h4 class="fw-bold">Bienvenido a Shekiná School <i class='bx bxs-graduation'></i></h4>
              <p class="text-muted">Ingresa tu usuario y contraseña</p>
            </div>

            @include('layouts.alerts')

            <form id="formAuthentication" action="{{ route('login.post') }}" method="POST">
              @csrf
              <div class="mb-3">
                <label for="username" class="form-label">Usuario</label>
                <div class="input-group">
                  <span class="input-group-text"><i class='bx bx-user'></i></span>
                  <input type="text" class="form-control" id="username" name="username" placeholder="Usuario" required autofocus />
                </div>
              </div>

              <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <div class="input-group">
                  <span class="input-group-text"><i class='bx bx-lock'></i></span>
                  <input type="password" class="form-control" id="password" name="password" placeholder="********" required />
                  <span class="input-group-text password-toggle"><i class='bx bx-hide'></i></span>
                </div>
              </div>

              <div class="d-grid">
                <button type="submit" class="btn btn-primary">Iniciar sesión</button>
              </div>
            </form>

            <div class="text-center mt-3">
              <a href="#" data-bs-toggle="modal" data-bs-target="#password-forgot" class="small">¿Olvidaste tu contraseña?</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="password-forgot" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class='bx bx-info-circle'></i> ¡Atención!</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>Para restablecer tu contraseña comunícate con el administrador del sistema.</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Aceptar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Optimized Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <script defer>
      document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.querySelector('.password-toggle');
        const icon = toggle.querySelector('i');
        const input = document.getElementById('password');

        toggle.addEventListener('click', () => {
          const isHidden = input.type === 'password';
          input.type = isHidden ? 'text' : 'password';
          icon.classList.toggle('bx-hide');
          icon.classList.toggle('bx-show');
        });
      });
    </script>
  </body>
</html>
