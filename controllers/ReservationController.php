<?php
require_once __DIR__ . '/../helpers/Mail.php';

class ReservationController {
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    /* ------------------------------------------------------------------
     * PASO 1: el formulario de flights.php nos envía aquí (POST) para
     *         validar, calcular precio y mostrar confirm_flight.php
     * ------------------------------------------------------------------ */
    public function summary(){
        Auth::start();
        if (!Auth::check()){
            header('Location: default.php?controller=user&action=login');
            exit;
        }

        // --- Datos recibidos ------------------------------------------
        $region      = $_POST['region_origen']      ?? '';
        $aeropOrig   = $_POST['aeropuerto_origen']  ?? '';
        $tipoPasaj   = $_POST['tipo_pasajero']      ?? '';
        $equipaje    = $_POST['equipaje']           ?? '';
        $clase       = $_POST['clase']              ?? '';
        $mascota     = $_POST['mascota']            ?? '';
        $paisDest    = $_POST['pais_destino']       ?? '';
        $aeropDest   = $_POST['aeropuerto_destino'] ?? '';

        //  Mapas base --------------------------------------------------
        $basePrices = [
          'Argentina'=>470,'Brasil'=>425,'Francia'=>50,'Alemania'=>60,
          'Italia'=>45,'Japón'=>400,'México'=>230,'España'=>25,
          'Reino Unido'=>60,'Estados Unidos'=>190
        ];
        //  Validaciones -----------------------------------------------
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

        //  Cálculo de precio ------------------------------------------
        $precioBase = $basePrices[$paisDest];   // precio “inicial” según el país
        $precio     = $precioBase;

        // –25 % si es menor de edad
        if ($tipoPasaj === 'menor')   $precio *= 0.75; 

        // +25 % si factura equipaje
        if ($equipaje   === 'si')     $precio *= 1.25; 

        // +200 % (total 300%) si elige Business
        if ($clase      === 'business') $precio *= 3; 

        /*  +80 % del precio INICIAL si lleva mascota.
            Se suma sobre el precio que ya llevamos calculado, de modo que
            “80 % del inicial” equivale a 0,80 × $precioBase                */
        if ($mascota === 'si')        $precio += $precioBase * 0.80;

        //  Detalle textual --------------------------------------------
        $detalle = [
            'Origen'          => "$region – $aeropOrig",
            'Tipo Pasajero'   => $tipoPasaj === 'menor' ? 'Menor de edad' : 'Mayor de edad',
            'Equipaje'        => $equipaje === 'si'
                                  ? 'Incluye maleta facturada' : 'Sin maleta facturada',
            'Clase'           => $clase === 'business' ? 'Business' : 'Económica',
            'Mascota'         => $mascota === 'si'
                                  ? 'Incluye mascota en cabina' : 'Sin mascota',
            'Destino'         => "$paisDest – $aeropDest"
        ];

        //  Guardamos en sesión para la vista de confirmación ----------
        $_SESSION['resumen_vuelo'] = [
            'precio'  => $precio,
            'detalle' => $detalle,
            'pais'    => $paisDest            // para crear el registro después
        ];

        require __DIR__ . '/../views/confirm_flight.php';
    }

    /* ------------------------------------------------------------------
     * PASO 2: botón final “Reservar Vuelo” – se crea la reserva,
     *         se manda email y se redirige al perfil.
     * ------------------------------------------------------------------ */
    public function reserveCustom(){
        Auth::start();
        if (!Auth::check() || !isset($_SESSION['resumen_vuelo'])){
            header('Location: default.php');
            exit;
        }

        $data   = $_SESSION['resumen_vuelo'];
        unset($_SESSION['resumen_vuelo']);

        // ----- 1. buscar un vuelo disponible en la tabla 'vuelos' ----
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

        // ----- 2. crear la reserva con los nuevos campos -------------
        $rm = new ReservationModel($this->pdo);
        $codigo = $rm->createDetailed(Auth::user()['id'], $vuelo['id'],
                                      $data['detalle'], $data['precio']);

        // ----- 3. email de confirmación ------------------------------
        $detalleHtml = '';
        foreach ($data['detalle'] as $k => $v){
            $detalleHtml .= "<strong>$k:</strong> ".htmlspecialchars($v).'<br>';
        }
        $detalleHtml .= "<strong>Precio final:</strong> €".number_format($data['precio'],2).'<br>';
        $detalleHtml .= "<strong>Código de reserva:</strong> $codigo";

        sendConfirmation(Auth::user()['email'], Auth::user()['nombre'], $detalleHtml);

        // ----- 4. vista de ok ----------------------------------------
        require __DIR__ . '/../views/confirm.php';
    }

    /* ------------------------------------------------------------------
     *  ACCIÓN ORIGINAL (reserva rápida con el botón antiguo) – sigue
     *  disponible para compatibilidad pero ya no se usa.
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
     * BORRAR una reserva (sin cambios)
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
