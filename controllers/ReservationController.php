<?php
require_once __DIR__ . '/../helpers/Mail.php';

class ReservationController {
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    /* ------------------------------------------------------------------
     * CREAR UNA NUEVA RESERVA
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
     * ELIMINAR UNA RESERVA
     *
     * • El administrador puede borrar cualquier reserva y regresa
     *   al panel de administración.
     * • Un usuario corriente sólo puede borrar sus propias reservas
     *   y vuelve a su perfil.
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
