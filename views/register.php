
<?php include __DIR__.'/partials/header.php'; ?>
<h2>Registro</h2>
<form method="POST">
<div class="mb-3"><label>Nombre</label><input type="text" name="name" class="form-control" required></div>
<div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
<div class="mb-3"><label>Contraseña</label><input type="password" name="password" class="form-control" required></div>
<button class="btn btn-success">Registrarse</button>
</form>
<?php include __DIR__.'/partials/footer.php'; ?>
