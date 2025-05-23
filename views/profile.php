<?php
include __DIR__.'/partials/header.php';
Auth::start();   // por si acaso

// Mensajes de éxito / error tras actualizar
$success = $_SESSION['profile_success'] ?? null;
$error   = $_SESSION['profile_error']   ?? null;
unset($_SESSION['profile_success'], $_SESSION['profile_error']);
?>
<h2>Mis Reservas</h2>

<?php if ($success): ?>
  <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>
<?php if ($error): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<table class="table">
  <thead>
    <tr>
      <th>Destino</th>
      <th>Código</th>
      <th>Vuela el</th>
      <th>Fecha reserva</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($reservations as $r): ?>
      <tr>
          <td><?= htmlspecialchars($r['pais_destino']) ?></td>
          <td><?= htmlspecialchars($r['codigo_reserva']) ?></td>
          <td><?= (new DateTime($r['fecha_vuelo']))->format('d/m/Y H:i') ?></td>
          <td><?= $r['fecha_reserva'] ?></td>
          <td>
              <a href="default.php?controller=reservation&action=delete&id=<?= $r['id'] ?>"
                 class="btn btn-sm btn-danger"
                 onclick="return confirm('¿Cancelar esta reserva?');">
                  Eliminar
              </a>
          </td>
      </tr>
  <?php endforeach; ?>

  <?php if (empty($reservations)): ?>
      <tr><td colspan="5" class="text-center">Sin reservas</td></tr>
  <?php endif; ?>
  </tbody>
</table>

<?php if (!Auth::isAdmin()): ?>
<hr>
<h3>Otras opciones de la cuenta</h3>

<!-- Datos editables del usuario ------------------------------------------->
<div class="mb-3 d-flex justify-content-between align-items-center">
    <div>
        <strong>Nombre:</strong> <?= htmlspecialchars(Auth::user()['nombre']) ?>
    </div>
    <button class="btn btn-outline-secondary btn-sm"
            data-bs-toggle="modal" data-bs-target="#editNameModal">
        Editar
    </button>
</div>

<div class="mb-3 d-flex justify-content-between align-items-center">
    <div>
        <strong>Email:</strong> <?= htmlspecialchars(Auth::user()['email']) ?>
    </div>
    <button class="btn btn-outline-secondary btn-sm"
            data-bs-toggle="modal" data-bs-target="#editEmailModal">
        Editar
    </button>
</div>

<!-- Botón existente de borrado de cuenta ---------------------------------->
<a href="default.php?controller=user&action=deleteAccount"
   class="btn btn-outline-danger"
   onclick="return confirm('¿Seguro que deseas borrar tu cuenta? Esta acción eliminará también todas tus reservas y no se puede deshacer.');">
   Borrar Cuenta
</a>
<?php endif; ?>

<!-- ================= MODALES ================= -->

<!-- Modal ▸ Editar Nombre -->
<div class="modal fade" id="editNameModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="POST"
          action="default.php?controller=user&action=updateName">
      <div class="modal-header">
        <h5 class="modal-title">Editar Nombre</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Nuevo nombre</label>
          <input type="text" name="new_name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Contraseña</label>
          <input type="password" name="password" class="form-control" required>
          <div class="form-text">Necesitamos tu contraseña para confirmar el cambio.</div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary">Guardar cambios</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal ▸ Editar Email -->
<div class="modal fade" id="editEmailModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="POST"
          action="default.php?controller=user&action=updateEmail">
      <div class="modal-header">
        <h5 class="modal-title">Editar Email</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Nuevo email</label>
          <input type="email" name="new_email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Contraseña</label>
          <input type="password" name="password" class="form-control" required>
          <div class="form-text">Necesitamos tu contraseña para confirmar el cambio.</div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary">Guardar cambios</button>
      </div>
    </form>
  </div>
</div>

<?php include __DIR__.'/partials/footer.php'; ?>
