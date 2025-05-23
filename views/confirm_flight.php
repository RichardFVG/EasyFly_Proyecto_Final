<?php
/*  Página de confirmación de vuelo con resumen y botón final
 ----------------------------------------------------------------*/
include __DIR__.'/partials/header.php';
Auth::start();
if (!Auth::check()){
    header('Location: default.php?controller=user&action=login');
    exit;
}

$datos = $_SESSION['resumen_vuelo'] ?? null;
if (!$datos){
    header('Location: default.php?controller=flight&action=list');
    exit;
}
?>
<h2 class="mb-4 text-center">Confirmación de Vuelo</h2>

<div class="card shadow-sm mb-4">
  <div class="card-body">
    <h5 class="card-title">Datos del pasajero</h5>
    <p><?= htmlspecialchars(Auth::user()['nombre']) ?><br>
       <?= htmlspecialchars(Auth::user()['email']) ?></p>

    <h5 class="card-title mt-3">Resumen de tu reserva</h5>
    <ul class="list-group list-group-flush">
        <?php foreach ($datos['detalle'] as $k => $v): ?>
          <li class="list-group-item">
              <strong><?= $k ?>:</strong> <?= htmlspecialchars($v) ?>
          </li>
          <?php if ($k === 'Fecha y hora del vuelo'): ?>
            <li class="list-group-item">
              <strong>Duración del vuelo:</strong>
              <?= htmlspecialchars($datos['duracion']) ?>
            </li>
          <?php endif; ?>
        <?php endforeach; ?>
    </ul>

    <h4 class="mt-3">Precio final: €<?= number_format($datos['precio'], 2) ?></h4>
  </div>
</div>

<button type="button" class="btn btn-outline-success w-100 mb-3"
        onclick="this.classList.toggle('btn-success')">
    Añadir información bancaria para pagar
</button>

<form method="POST"
      action="default.php?controller=reservation&action=reserveCustom">
    <button class="btn btn-primary w-100">Reservar Vuelo</button>
</form>

<?php include __DIR__.'/partials/footer.php'; ?>
