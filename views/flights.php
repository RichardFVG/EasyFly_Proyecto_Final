<?php
/*  FORMULARIO COMPLETO DE SELECCIÓN DE VUELO
    – Cálculo de precio y redirección a la página de confirmación –
-----------------------------------------------------------------*/
include __DIR__.'/partials/header.php';
Auth::start();
if (!Auth::check()){
    // Solo usuarios registrados pueden reservar.
    header('Location: default.php?controller=user&action=login');
    exit;
}

// ------  Precios base y aeropuertos disponibles  ----------------
$basePrices = [
  'Argentina'      => 470,
  'Brasil'         => 425,
  'Francia'        =>  50,
  'Alemania'       =>  60,
  'Italia'         =>  45,
  'Japón'          => 400,
  'México'         => 230,
  'España'         =>  25,
  'Reino Unido'    =>  60,
  'Estados Unidos' => 190
];

$airports = [
  'Argentina'      => [
      'Ministro Pistarini International Airport',
      'San Carlos de Bariloche Airport',
      'Ingeniero Aeronáutico Ambrosio L.V. Taravella'
  ],
  'Brasil'         => [
      'São Paulo-Guarulhos International Airport',
      'Galeão International Airport',
      'Presidente Juscelino Kubitschek International'
  ],
  'Francia'        => [
      'Charles de Gaulle Airport',
      'Nice Côte d\'Azur Airport',
      'Lyon-Saint Exupéry Airport'
  ],
  'Alemania'       => [
      'Berlin Brandenburg Airport',
      'Frankfurt Airport',
      'Munich Airport'
  ],
  'Italia'         => [
      'Leonardo da Vinci–Fiumicino Airport',
      'Milan Malpensa Airport',
      'Venice Marco Polo Airport'
  ],
  'Japón'          => [
      'Tokyo International Airport',
      'Kansai International Airport',
      'Chubu Centrair International Airport'
  ],
  'México'         => [
      'Mexico City International Airport',
      'Cancún International Airport',
      'Guadalajara International Airport'
  ],
  'España'         => [
      'Gran Canaria Airport (Las Palmas)',
      'Tenerife North Airport (Los Rodeos Airport)',
      'Madrid–Barajas Airport',
      'Barcelona–El Prat Airport'
  ],
  'Reino Unido'    => [
      'Heathrow Airport',
      'Manchester Airport',
      'Birmingham Airport'
  ],
  'Estados Unidos' => [
      'John F. Kennedy International Airport',
      'Los Angeles International Airport',
      'O\'Hare International Airport'
  ]
];

$error = $_SESSION['flight_error'] ?? null;
unset($_SESSION['flight_error']);
?>
<h2 class="mb-4 text-center">Vuelos Disponibles</h2>

<?php if ($error): ?>
  <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<form class="form-reserva" method="POST"
      action="default.php?controller=reservation&action=summary">

  <!-- 1. Lugar de partida (España) + región ----------------------------->
  <label class="form-label">Lugar de partida: España</label>
  <select name="region_origen" class="form-select" required>
      <option value="Islas Canarias">Islas Canarias</option>
      <option value="Península">Península (para Japón)</option>
  </select>

  <!-- 2. Aeropuerto de origen ------------------------------------------->
  <label class="form-label">Aeropuerto de origen</label>
  <select name="aeropuerto_origen" class="form-select" required>
      <option value="Gran Canaria Airport (Las Palmas)">Gran Canaria Airport (Las Palmas)</option>
      <option value="Tenerife North Airport (Los Rodeos Airport)">Tenerife North Airport (Los Rodeos Airport)</option>
      <option value="Madrid–Barajas Airport">Madrid–Barajas Airport</option>
      <option value="Barcelona–El Prat Airport">Barcelona–El Prat Airport</option>
  </select>

  <!-- 3. Tipo de pasajero ---------------------------------------------->
  <label class="form-label">Tipo de pasajero</label>
  <select name="tipo_pasajero" class="form-select" required>
      <option value="adulto">Mayor de edad</option>
      <option value="menor">Menor de edad (-25%)</option>
  </select>

  <!-- 4. Equipaje facturado -------------------------------------------->
  <label class="form-label">Equipaje facturado</label>
  <select name="equipaje" class="form-select" required>
      <option value="si">Incluir una maleta facturada de 23 kg (+25%)</option>
      <option value="no">NO incluir una maleta facturada de 23 kg</option>
  </select>

  <!-- 5. Clase --------------------------------------------------------->
  <label class="form-label">Clase</label>
  <select name="clase" class="form-select" required>
      <option value="economica">Clase Económica</option>
      <option value="business">Clase Business (+200%)</option>
  </select>

  <!-- 6. Mascota ------------------------------------------------------->
  <label class="form-label">Mascota en cabina</label>
  <select name="mascota" class="form-select" required>
      <option value="no">NO incluir una mascota pequeña en cabina</option>
      <option value="si">Incluir una mascota pequeña en cabina (+80 %)</option>
  </select>

  <button type="button" class="btn btn-outline-success w-100"
          onclick="this.classList.toggle('btn-success')">
      Añadir información de la mascota
  </button>

  <!-- 7. País de destino ----------------------------------------------->
  <label class="form-label mt-3">País de destino</label>
  <select name="pais_destino" id="pais_destino" class="form-select" required
          onchange="cargarAeropuertos()">
      <option value="" selected disabled>— Selecciona un país —</option>
      <?php foreach ($basePrices as $pais => $price): ?>
        <option value="<?= $pais ?>"><?= $pais ?> (desde €<?= $price ?>)</option>
      <?php endforeach; ?>
  </select>

  <!-- 8. Aeropuerto de destino (rellenado vía JS) ----------------------->
  <label class="form-label mt-2">Aeropuerto de destino</label>
  <select name="aeropuerto_destino" id="aeropuerto_destino"
          class="form-select" required>
      <option value="" selected disabled>
          — Selecciona primero el país —
      </option>
  </select>

  <!-- 9.  Reservar ------------------------------------------------------->
  <button type="submit" class="btn btn-primary w-100 mt-3">
      Reservar Vuelo
  </button>
</form>

<script>
/* --------------------------------------------------------------
   JS rápido embebido para rellenar aeropuertos destino
-------------------------------------------------------------- */
const airports = <?= json_encode($airports, JSON_UNESCAPED_UNICODE) ?>;
function cargarAeropuertos(){
  const pais = document.getElementById('pais_destino').value;
  const sel  = document.getElementById('aeropuerto_destino');
  sel.innerHTML = '';
  (airports[pais] || []).forEach(ap => {
      const opt      = document.createElement('option');
      opt.value      = ap;
      opt.textContent= ap;
      sel.appendChild(opt);
  });
}
</script>

<?php include __DIR__.'/partials/footer.php'; ?>
