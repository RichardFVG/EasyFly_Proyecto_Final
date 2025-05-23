<?php
/*  FORMULARIO COMPLETO DE SELECCIÓN DE VUELO
    – Cálculo de precio y redirección a la página de confirmación –
-----------------------------------------------------------------*/
include __DIR__.'/partials/header.php';
Auth::start();
if (!Auth::check()){
    header('Location: default.php?controller=user&action=login');
    exit;
}

/* ---  Datos base (precios y aeropuertos)  --------------------- */
$basePrices = [
  'Argentina'=>470,'Brasil'=>425,'Francia'=>50,'Alemania'=>60,
  'Italia'=>45,'Japón'=>400,'México'=>230,'España'=>25,
  'Reino Unido'=>60,'Estados Unidos'=>190
];

/* ---  Aeropuertos de DESTINO (sin cambios) -------------------- */
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
      'Nice Côte d’Azur Airport',
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
      'O’Hare International Airport'
  ]
];

/* ---  NUEVO  ▸  Aeropuertos de ORIGEN por región  -------------- */
$originAirports = [
  'Islas Canarias' => [
      'Gran Canaria Airport (Las Palmas)',
      'Tenerife North Airport (Los Rodeos Airport)'
  ],
  'Península'      => [
      'Madrid–Barajas Airport',
      'Barcelona–El Prat Airport'
  ]
];

/* ----------  Recuperar datos previos (si hubo “< Atrás”) ------- */
$prev = $_SESSION['prev_flight_form'] ?? [];

$selRegion        = $prev['region_origen']      ?? '';
$selOriginAirport = $prev['aeropuerto_origen']  ?? '';
$selTipoPasajero  = $prev['tipo_pasajero']      ?? '';
$selEquipaje      = $prev['equipaje']           ?? '';
$selClase         = $prev['clase']              ?? '';
$selMascota       = $prev['mascota']            ?? '';
$selPaisDest      = $prev['pais_destino']       ?? '';
$selDestAirport   = $prev['aeropuerto_destino'] ?? '';
$selFechaVuelo    = $prev['fecha_vuelo']        ?? '';

/* Fecha → formato input datetime-local (Y-m-dTH:i) */
$fechaInputValue = '';
if ($selFechaVuelo){
    try{
        $dtTmp = new DateTime($selFechaVuelo, new DateTimeZone('Atlantic/Canary'));
        $fechaInputValue = $dtTmp->format('Y-m-d\TH:i');
    } catch(Exception $e){
        $fechaInputValue = '';
    }
}

$error = $_SESSION['flight_error'] ?? null;
unset($_SESSION['flight_error']);
?>
<!-- ===========================================================
     WRAPPER NUEVO: anchura máxima más amplia en escritorio
     y un poco de padding horizontal extra para legibilidad
     =========================================================== -->
<div class="container-xl px-lg-5">  <!--  MOD  -->
  <h2 class="mb-4 text-center display-6 fw-bold">Vuelos Disponibles</h2> <!--  MOD  -->

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>

  <form class="form-reserva" method="POST"
        action="default.php?controller=reservation&action=summary">

    <!-- 1. Lugar de partida (España) + región --------------------------->
    <label class="form-label fw-semibold">Lugar de partida: España</label> <!--  MOD  -->
    <select name="region_origen" id="region_origen"
            class="form-select" required>
        <option value="Islas Canarias"
                <?= $selRegion==='Islas Canarias' ? 'selected' : '' ?>>
            Islas Canarias
        </option>
        <option value="Península"
                <?= $selRegion==='Península' ? 'selected' : '' ?>>
            Península (para Japón)
        </option>
    </select>

    <!-- 2. Aeropuerto de origen (se actualiza según región) ------------->
    <label class="form-label fw-semibold">Aeropuerto de origen</label> <!--  MOD  -->
    <select name="aeropuerto_origen" id="aeropuerto_origen"
            class="form-select" required>
        <?php if ($selRegion && isset($originAirports[$selRegion])): ?>
            <?php foreach ($originAirports[$selRegion] as $ap): ?>
                <option value="<?= htmlspecialchars($ap) ?>"
                        <?= $ap === $selOriginAirport ? 'selected' : '' ?>>
                    <?= htmlspecialchars($ap) ?>
                </option>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Se rellenará con JavaScript -->
        <?php endif; ?>
    </select>

    <!-- 3. Tipo de pasajero ------------------------------------------->
    <label class="form-label fw-semibold">Tipo de pasajero</label> <!--  MOD  -->
    <select name="tipo_pasajero" class="form-select" required>
        <option value="adulto"
                <?= $selTipoPasajero==='adulto' || $selTipoPasajero==='' ? 'selected' : '' ?>>
            Mayor de edad
        </option>
        <option value="menor"
                <?= $selTipoPasajero==='menor' ? 'selected' : '' ?>>
            Menor de edad (-25 %)
        </option>
    </select>

    <!-- 4. Equipaje facturado ----------------------------------------->
    <label class="form-label fw-semibold">Equipaje facturado</label> <!--  MOD  -->
    <select name="equipaje" class="form-select" required>
        <option value="si"
                <?= $selEquipaje==='si' ? 'selected' : '' ?>>
            Incluir una maleta facturada de 23 kg (+25 %)
        </option>
        <option value="no"
                <?= $selEquipaje==='no' || $selEquipaje==='' ? 'selected' : '' ?>>
            NO incluir una maleta facturada de 23 kg
        </option>
    </select>

    <!-- 5. Clase ------------------------------------------------------->
    <label class="form-label fw-semibold">Clase</label> <!--  MOD  -->
    <select name="clase" class="form-select" required>
        <option value="economica"
                <?= $selClase==='economica' || $selClase==='' ? 'selected' : '' ?>>
            Clase Económica
        </option>
        <option value="business"
                <?= $selClase==='business' ? 'selected' : '' ?>>
            Clase Business (+200 %)
        </option>
    </select>

    <!-- 6. Mascota ----------------------------------------------------->
    <label class="form-label fw-semibold">Mascota en cabina</label> <!--  MOD  -->
    <select name="mascota" class="form-select" required>
        <option value="no"
                <?= $selMascota==='no' || $selMascota==='' ? 'selected' : '' ?>>
            NO incluir una mascota pequeña en cabina
        </option>
        <option value="si"
                <?= $selMascota==='si' ? 'selected' : '' ?>>
            Incluir una mascota pequeña en cabina (+80 %)
        </option>
    </select>

    <button type="button" class="btn btn-outline-success w-100"
            onclick="this.classList.toggle('btn-success')">
        Añadir información de la mascota
    </button>

    <!-- 7. País de destino --------------------------------------------->
    <label class="form-label mt-3 fw-semibold">País de destino</label> <!--  MOD  -->
    <select name="pais_destino" id="pais_destino" class="form-select" required
            onchange="cargarAeropuertos()">
        <option value="" <?= $selPaisDest==='' ? 'selected' : '' ?> disabled>
            — Selecciona un país —
        </option>
        <?php foreach ($basePrices as $pais => $price): ?>
          <option value="<?= $pais ?>"
                  <?= $pais === $selPaisDest ? 'selected' : '' ?>>
              <?= $pais ?> (desde €<?= $price ?>)
          </option>
        <?php endforeach; ?>
    </select>

    <!-- 8. Aeropuerto de destino (se actualiza con JS) ----------------->
    <label class="form-label mt-2 fw-semibold">Aeropuerto de destino</label> <!--  MOD  -->
    <select name="aeropuerto_destino" id="aeropuerto_destino"
            class="form-select" required>
        <?php if ($selPaisDest && isset($airports[$selPaisDest])): ?>
            <?php foreach ($airports[$selPaisDest] as $ap): ?>
                <option value="<?= htmlspecialchars($ap) ?>"
                        <?= $ap === $selDestAirport ? 'selected' : '' ?>>
                    <?= htmlspecialchars($ap) ?>
                </option>
            <?php endforeach; ?>
        <?php else: ?>
            <option value="" selected disabled>
                — Selecciona primero el país —
            </option>
        <?php endif; ?>
    </select>

    <!-- 9. Fecha y hora del vuelo -------------------------------------->
    <label class="form-label mt-3 fw-semibold">Fecha y hora del vuelo</label> <!--  MOD  -->
    <input type="datetime-local" name="fecha_vuelo"
           class="form-control"
           value="<?= htmlspecialchars($fechaInputValue) ?>"
           required>

    <!-- 10.  Reservar --------------------------------------------------->
    <button type="submit" class="btn btn-primary w-100 mt-3">
        Reservar Vuelo
    </button>
  </form>
</div><!-- /.container-xl -->

<script>
/* ----------  Destinos (ya existente)  ------------------------------ */
const airports        = <?= json_encode($airports, JSON_UNESCAPED_UNICODE) ?>;
/* ----------  NUEVO ▸ Orígenes por región --------------------------- */
const originAirports  = <?= json_encode($originAirports, JSON_UNESCAPED_UNICODE) ?>;

/* ----------  Datos preseleccionados (PHP → JS) --------------------- */
let preselectedOrigin = <?= json_encode($selOriginAirport, JSON_UNESCAPED_UNICODE) ?>;
let preselectedDest   = <?= json_encode($selDestAirport,   JSON_UNESCAPED_UNICODE) ?>;

/* ---------  Actualiza “Aeropuerto de destino” ---------------------- */
function cargarAeropuertos(){
  const pais = document.getElementById('pais_destino').value;
  const sel  = document.getElementById('aeropuerto_destino');
  sel.innerHTML = '';
  (airports[pais] || []).forEach(ap => {
      const opt = document.createElement('option');
      opt.value = ap;
      opt.textContent = ap;
      sel.appendChild(opt);
  });
  if (preselectedDest){
      sel.value = preselectedDest;
  }
}

/* ---------  Actualiza “Aeropuerto de origen” ----------------------- */
function cargarAeropuertosOrigen(){
  const region = document.getElementById('region_origen').value;
  const sel    = document.getElementById('aeropuerto_origen');
  sel.innerHTML = '';
  (originAirports[region] || []).forEach(ap => {
      const opt = document.createElement('option');
      opt.value = ap;
      opt.textContent = ap;
      sel.appendChild(opt);
  });
  if (preselectedOrigin){
      sel.value = preselectedOrigin;
  }
}

/*  Eventos ---------------------------------------------------------- */
document.getElementById('region_origen')
        .addEventListener('change', () => {
            preselectedOrigin = '';      // si cambia región limpiamos selección previa
            cargarAeropuertosOrigen();
        });

document.getElementById('pais_destino')
        .addEventListener('change', () => {
            preselectedDest = '';        // si cambia país limpiamos selección previa
            cargarAeropuertos();
        });

/*  Primera carga al abrir la página --------------------------------- */
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('region_origen').value){
        cargarAeropuertosOrigen();
    }
    if (document.getElementById('pais_destino').value){
        cargarAeropuertos();
    }
});
</script>

<?php include __DIR__.'/partials/footer.php'; ?>
