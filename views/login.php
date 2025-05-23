<?php include __DIR__.'/partials/header.php'; ?>

<h2>Iniciar Sesión</h2>

<?php if (isset($error)): ?>
  <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<form method="POST">
  <!-- Identificador ------------------------------------------------------->
  <div class="mb-3">
      <label>Email o Nombre de usuario</label>
      <input type="text" name="identifier" class="form-control" required>
  </div>

  <!-- Contraseña + icono SVG --------------------------------------------->
  <div class="mb-3 password-wrapper">
      <label>Contraseña</label>
      <input type="password"
             name="password"
             id="passwordField"
             class="form-control"
             required>

      <!-- Icono para alternar visibilidad de la contraseña -->
      <span class="toggle-password" id="togglePwd"
            aria-label="Mostrar/ocultar contraseña">
        <!--
        tags: [view, watch]
        category: System
        version: "1.22"
        unicode: "ecf0"
        -->
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="40"
          height="40"
          viewBox="0 0 24 24"
          fill="none"
          stroke="#00c7be"
          stroke-width="1.75"
          stroke-linecap="round"
          stroke-linejoin="round"
        >
          <path d="M10.585 10.587a2 2 0 0 0 2.829 2.828" />
          <path d="M16.681 16.673a8.717 8.717 0 0 1 -4.681 1.327c-3.6 0 -6.6 -2 -9 -6c1.272 -2.12 2.712 -3.678 4.32 -4.674m2.86 -1.146a9.055 9.055 0 0 1 1.82 -.18c3.6 0 6.6 2 9 6c-.666 1.11 -1.379 2.067 -2.138 2.87" />
          <path d="M3 3l18 18" />
        </svg>
      </span>
  </div>

  <!-- Botón de envío ----------------------------------------------------->
  <button class="btn btn-primary">Entrar</button>

  <!-- Mensaje de ayuda --------------------------------------------------->
  <p class="mt-3 fst-italic text-center">
    ¿Olvidaste tu contraseña? Contacta servicio al cliente con este número: +34&nbsp;612&nbsp;254&nbsp;641
  </p>
</form>

<!-- Script: alternar visibilidad de la contraseña ------------------------>
<script>
document.getElementById('togglePwd').addEventListener('click', function () {
    const pwd = document.getElementById('passwordField');
    pwd.type = (pwd.type === 'password') ? 'text' : 'password';
});
</script>

<?php include __DIR__.'/partials/footer.php'; ?>
