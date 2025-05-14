<h1>Iniciar sesión</h1>
<?php if(!empty($error)): ?><div class="alert alert-danger"><?=$error?></div><?php endif; ?>
<form method="post" action="default.php?url=auth/login">
  <div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Contraseña</label>
    <input type="password" name="password" class="form-control" required>
  </div>
  <button class="btn btn-primary">Entrar</button>
  <a class="btn btn-link" href="default.php?url=auth/register">Registrarse</a>
</form>
