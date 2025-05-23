<?php include __DIR__.'/partials/header.php'; ?>

<h2>Registro</h2>

<?php if (isset($error)): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST">
  <!-- Nombre -------------------------------------------------------------->
  <div class="mb-3">
    <label>Nombre</label>
    <input type="text"
           name="name"
           class="form-control"
           required
           value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
  </div>

  <!-- Email --------------------------------------------------------------->
  <div class="mb-3">
    <label>Email</label>
    <input type="email"
           name="email"
           class="form-control"
           required
           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
  </div>

  <!-- Contraseña ---------------------------------------------------------->
  <div class="mb-3">
    <label>Contraseña</label>
    <input type="password"
           name="password"
           class="form-control"
           required>
  </div>

  <button class="btn btn-success">Registrarse</button>
</form>

<?php include __DIR__.'/partials/footer.php'; ?>
