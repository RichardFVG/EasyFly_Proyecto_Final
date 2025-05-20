<?php include __DIR__.'/partials/header.php'; ?>

<h2>Iniciar Sesión</h2>

<?php if (isset($error)): ?>
  <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<form method="POST">
  <div class="mb-3">
      <label>Email o Nombre de usuario</label>
      <input type="text" name="identifier" class="form-control" required>
  </div>

  <div class="mb-3">
      <label>Contraseña</label>
      <input type="password" name="password" class="form-control" required>
  </div>

  <button class="btn btn-primary">Entrar</button>
</form>

<?php include __DIR__.'/partials/footer.php'; ?>
