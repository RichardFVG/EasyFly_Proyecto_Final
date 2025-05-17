<?php
require_once __DIR__ . '/../helpers/Mail.php';

class ReservationController {
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

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
        // ------------------------------------------------------------------
        // Ahora la función está definida porque ya incluimos helpers/Mail.php
        // ------------------------------------------------------------------
        sendConfirmation(Auth::user()['email'], Auth::user()['nombre'], $details);

        require __DIR__ . '/../views/confirm.php';
    }

    public function delete(){
        if (!Auth::isAdmin()){
            header('Location: default.php');
            exit;
        }
        $id = intval($_GET['id'] ?? 0);
        (new ReservationModel($this->pdo))->delete($id);
        header('Location: default.php?controller=admin&action=reservations');
    }
}
?>
