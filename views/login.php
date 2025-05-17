
<?php include __DIR__.'/partials/header.php'; ?>
<h2>Iniciar Sesión</h2>
<?php if(isset($error)): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
<form method="POST">
<div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
<div class="mb-3"><label>Contraseña</label><input type="password" name="password" class="form-control" required></div>
<button class="btn btn-primary">Entrar</button>
</form>
<?php include __DIR__.'/partials/footer.php'; ?>
