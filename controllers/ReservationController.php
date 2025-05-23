<?php
require_once __DIR__ . '/../helpers/Mail.php';

class ReservationController {
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    /* ------------------------------------------------------------------
     * PASO 1: recibe POST desde flights.php → calcula precio y muestra
     *         confirm_flight.php
     * ------------------------------------------------------------------ */
    public function summary(){
        Auth::start();
        if (!Auth::check()){
            header('Location: default.php?controller=user&action=login');
            exit;
        }

        /* ----------  Datos recibidos  ---------- */
        $region      = $_POST['region_origen']      ?? '';
        $aeropOrig   = $_POST['aeropuerto_origen']  ?? '';
        $tipoPasaj   = $_POST['tipo_pasajero']      ?? '';
        $equipaje    = $_POST['equipaje']           ?? '';
        $clase       = $_POST['clase']              ?? '';
        $mascota     = $_POST['mascota']            ?? '';
        $paisDest    = $_POST['pais_destino']       ?? '';
        $aeropDest   = $_POST['aeropuerto_destino'] ?? '';
        $fechaVueloR = $_POST['fecha_vuelo']        ?? '';

        /* ----------  Validar fecha y hora de vuelo  ---------- */
        $tzCanary = new DateTimeZone('Atlantic/Canary');
        try {
            $dtVuelo = new DateTime($fechaVueloR ?: 'now', $tzCanary);
        } catch (Exception $e){
            $_SESSION['flight_error'] = 'Fecha u hora de vuelo no válidas.';
            header('Location: default.php?controller=flight&action=list');
            exit;
        }

        $ahora   = new DateTime('now', $tzCanary);
        $diffSeg = $dtVuelo->getTimestamp() - $ahora->getTimestamp();
        if ($diffSeg < 300){
            $_SESSION['flight_error'] =
              'La fecha del vuelo debe ser, como mínimo, 5 minutos posterior a la actual.';
            header('Location: default.php?controller=flight&action=list');
            exit;
        }
        $fechaVuelo = $dtVuelo->format('Y-m-d H:i:s');

        /* ----------  Precios base y duraciones ---------- */
        $basePrices = [
          'Argentina'=>470,'Brasil'=>425,'Francia'=>50,'Alemania'=>60,
          'Italia'=>45,'Japón'=>400,'México'=>230,'España'=>25,
          'Reino Unido'=>60,'Estados Unidos'=>190
        ];
        $flightDurations = [
          'Islas Canarias' => [
              'Argentina'=>'11 horas','Brasil'=>'8 horas 30 minutos',
              'Francia'=>'4 horas','Alemania'=>'4 horas 30 minutos',
              'Italia'=>'4 horas','México'=>'11 horas',
              'España'=>'2 horas 30 minutos','Reino Unido'=>'4 horas',
              'Estados Unidos'=>'7 horas',
          ],
          'Península' => ['Japón'=>'14 horas 30 minutos'],
        ];

        /* ----------  Validaciones ---------- */
        if (!isset($basePrices[$paisDest])){
            $_SESSION['flight_error'] = 'País de destino no válido.';
            header('Location: default.php?controller=flight&action=list');
            exit;
        }
        if ($paisDest === 'Japón' && $region !== 'Península'){
            $_SESSION['flight_error'] =
              'Los vuelos a Japón solo están disponibles desde la Península.';
            header('Location: default.php?controller=flight&action=list');
            exit;
        }
        if ($paisDest === 'España' && $aeropDest === $aeropOrig){
            $_SESSION['flight_error'] =
              'El aeropuerto de destino en España no puede coincidir con el de origen.';
            header('Location: default.php?controller=flight&action=list');
            exit;
        }

        /* ----------  Cálculo de precio ---------- */
        $precioBase = $basePrices[$paisDest];
        $precio     = $precioBase;
        if ($tipoPasaj === 'menor')     $precio *= 0.75;
        if ($equipaje   === 'si')       $precio *= 1.25;
        if ($clase      === 'business') $precio *= 3;
        if ($mascota    === 'si')       $precio += $precioBase * 0.80;

        $duracion = $flightDurations[$region][$paisDest] ?? '—';

        /* ----------  Detalle textual ---------- */
        $detalle = [
            'Origen'   => "$region – $aeropOrig",
            'Tipo Pasajero' => $tipoPasaj === 'menor' ? 'Menor de edad' : 'Mayor de edad',
            'Equipaje' => $equipaje === 'si' ? 'Incluye maleta facturada' : 'Sin maleta facturada',
            'Clase'    => $clase === 'business' ? 'Business' : 'Económica',
            'Mascota'  => $mascota === 'si' ? 'Incluye mascota en cabina' : 'Sin mascota',
            'Destino'  => "$paisDest – $aeropDest",
            'Fecha y hora del vuelo' => (new DateTime($fechaVuelo))->format('d/m/Y H:i')
        ];

        /* ----------  Guardar en sesión ---------- */
        $_SESSION['resumen_vuelo'] = [
            'precio'      => $precio,
            'detalle'     => $detalle,
            'pais'        => $paisDest,
            'fecha_vuelo' => $fechaVuelo,
            'duracion'    => $duracion
        ];

        /* ----------  NUEVO ▸ Guardar el formulario para “< Atrás” ----- */
        $_SESSION['prev_flight_form'] = [
            'region_origen'      => $region,
            'aeropuerto_origen'  => $aeropOrig,
            'tipo_pasajero'      => $tipoPasaj,
            'equipaje'           => $equipaje,
            'clase'              => $clase,
            'mascota'            => $mascota,
            'pais_destino'       => $paisDest,
            'aeropuerto_destino' => $aeropDest,
            'fecha_vuelo'        => $fechaVuelo
        ];

        require __DIR__.'/../views/confirm_flight.php';
    }

    /* ------------------------------------------------------------------
     * PASO 2: botón final “Reservar Vuelo”
     * ------------------------------------------------------------------ */
    public function reserveCustom(){
        Auth::start();
        if (!Auth::check() || !isset($_SESSION['resumen_vuelo'])){
            header('Location: default.php');
            exit;
        }

        /* --------  ¿Se añadió la info bancaria? -------- */
        if (($_POST['bank_added'] ?? '0') !== '1'){
            $_SESSION['bank_error'] =
              'Debes añadir la información bancaria antes de confirmar la reserva.';
            /* Mostramos otra vez la página de confirmación */
            require __DIR__.'/../views/confirm_flight.php';
            return;
        }

        $data = $_SESSION['resumen_vuelo'];
        unset($_SESSION['resumen_vuelo']);

        /* 1. Buscar vuelo disponible ---------------------------------- */
        $stmt = $this->pdo->prepare(
          'SELECT * FROM vuelos WHERE pais_destino = ? AND plazas_disponibles > 0 LIMIT 1'
        );
        $stmt->execute([$data['pais']]);
        $vuelo = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$vuelo){
            $_SESSION['flight_error'] = 'Lo sentimos, no quedan plazas.';
            header('Location: default.php?controller=flight&action=list');
            exit;
        }

        /* 2. Crear la reserva ---------------------------------------- */
        $rm     = new ReservationModel($this->pdo);
        $codigo = $rm->createDetailed(
            Auth::user()['id'],
            $vuelo['id'],
            $data['detalle'],
            $data['precio'],
            $data['fecha_vuelo']
        );

        /* 3. Correo de confirmación ----------------------------------- */
        $detalleHtml = '';
        foreach ($data['detalle'] as $k => $v){
            $detalleHtml .= "<strong>$k:</strong> ".htmlspecialchars($v).'<br>';
            if ($k === 'Fecha y hora del vuelo'){
                $detalleHtml .= '<strong>Duración del vuelo:</strong> '
                              . htmlspecialchars($data['duracion']).'<br>';
            }
        }
        $detalleHtml .= "<strong>Precio final:</strong> €".number_format($data['precio'],2).'<br>';
        $detalleHtml .= "<strong>Código de reserva:</strong> $codigo";

        sendConfirmation(Auth::user()['email'], Auth::user()['nombre'], $detalleHtml);

        /* 4. Limpiar datos temporales de formulario ------------------- */
        unset($_SESSION['prev_flight_form']);

        /* 5. Vista OK ------------------------------------------------- */
        require __DIR__.'/../views/confirm.php';
    }

    /* ------------------------------------------------------------------
     *  ACCIÓN ORIGINAL (reserva rápida con el botón antiguo)
     * ------------------------------------------------------------------ */
    public function reserve(){
        Auth::start();
        if (!Auth::check()){
            header('Location: default.php?controller=user&action=login');
            exit;
        }

        $flightId = intval($_GET['id'] ?? 0);
        $fm       = new FlightModel($this->pdo);
        $flight   = $fm->get($flightId);

        if (!$flight){
            header('Location: default.php');
            exit;
        }

        $rm   = new ReservationModel($this->pdo);
        $code = $rm->create(Auth::user()['id'], $flight);

        if (!$code){
            $error = 'Lo sentimos, no quedan plazas.';
            require __DIR__ . '/../views/flights.php';
            return;
        }

        $details = "Destino: {$flight['pais_destino']}<br>Código: {$code}";
        sendConfirmation(Auth::user()['email'], Auth::user()['nombre'], $details);

        require __DIR__ . '/../views/confirm.php';
    }

    /* ------------------------------------------------------------------
     * BORRAR una reserva – sin cambios
     * ------------------------------------------------------------------ */
    public function delete(){
        Auth::start();

        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0){
            header('Location: default.php');
            exit;
        }

        /* ¿De quién es la reserva? */
        $stmt = $this->pdo->prepare('SELECT usuario_id FROM reservas WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row){
            header('Location: default.php');       // reserva inexistente
            exit;
        }

        $isOwner = Auth::check() && Auth::user()['id'] == $row['usuario_id'];
        $isAdmin = Auth::isAdmin();

        if (!$isOwner && !$isAdmin){
            header('Location: default.php');       // intento no autorizado
            exit;
        }

        (new ReservationModel($this->pdo))->delete($id);

        if ($isAdmin){
            header('Location: default.php?controller=admin&action=reservations');
        } else {
            header('Location: default.php?controller=user&action=profile');
        }
    }
}
?>
