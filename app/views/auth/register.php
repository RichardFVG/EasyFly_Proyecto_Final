<h1>Registrarse</h1>
<?php if(!empty($error)): ?><div class="alert alert-danger"><?=$error?></div><?php endif; ?>
<form method="post" action="default.php?url=auth/register">
  <div class="mb-3">
    <label>Nombre</label>
    <input type="text" name="name" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Contraseña</label>
    <input type="password" name="password" class="form-control" required>
  </div>
  <button class="btn btn-success">Crear cuenta</button>
</form>
