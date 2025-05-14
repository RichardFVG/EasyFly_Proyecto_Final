<?php
class ReservationController extends Controller {
    public function book($flightId){
        if(!isset($_SESSION['user_id'])){
            header('Location: default.php?url=auth/login');
            exit;
        }
        $flightModel = $this->model('Flight');
        $flight = $flightModel->find($flightId);
        if(!$flight || $flight['seats'] < 1){
            die('Vuelo no disponible');
        }
        $reservationModel = $this->model('Reservation');
        $reservationModel->create($_SESSION['user_id'], $flightId);
        $flightModel->decrementSeats($flightId);
        $this->view('reservations/confirm', compact('flight'));
    }

    public function history(){
        if(!isset($_SESSION['user_id'])){
            header('Location: default.php?url=auth/login');
            exit;
        }
        $reservationModel = $this->model('Reservation');
        $reservations = $reservationModel->byUser($_SESSION['user_id']);
        $this->view('reservations/history', compact('reservations'));
    }
}
?>
