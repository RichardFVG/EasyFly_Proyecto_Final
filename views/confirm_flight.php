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

/* Mensaje de error si faltó la información bancaria */
$bankError = $_SESSION['bank_error'] ?? null;
unset($_SESSION['bank_error']);
?>
<h2 class="mb-4 text-center">Confirmación de Vuelo</h2>

<?php if ($bankError): ?>
  <div class="alert alert-danger text-center"><?= htmlspecialchars($bankError) ?></div>
<?php endif; ?>

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

<!-- Botón para añadir / quitar información bancaria -->
<button type="button"
        id="bankBtn"
        class="btn btn-outline-success w-100 mb-3">
    Añadir información bancaria para pagar
</button>

<form method="POST"
      action="default.php?controller=reservation&action=reserveCustom">
    <!-- Campo oculto que indica si se añadió la info bancaria -->
    <input type="hidden" name="bank_added" id="bank_added" value="0">
    <button id="reservarBtn" class="btn btn-primary w-100" disabled>
        Reservar Vuelo
    </button>
</form>

<script>
const bankBtn      = document.getElementById('bankBtn');
const bankAddedInp = document.getElementById('bank_added');
const reservarBtn  = document.getElementById('reservarBtn');

let added = false;   // estado interno

bankBtn.addEventListener('click', () => {
    added = !added;  // alternar

    if (added){
        bankBtn.classList.remove('btn-outline-success');
        bankBtn.classList.add('btn-success', 'btn-bank-added');
        bankBtn.textContent = 'Información Añadida!';
        bankAddedInp.value  = '1';
        reservarBtn.disabled = false;
    } else {
        bankBtn.classList.remove('btn-success', 'btn-bank-added');
        bankBtn.classList.add('btn-outline-success');
        bankBtn.textContent = 'Añadir información bancaria para pagar';
        bankAddedInp.value  = '0';
        reservarBtn.disabled = true;
    }
});
</script>

<?php include __DIR__.'/partials/footer.php'; ?>
