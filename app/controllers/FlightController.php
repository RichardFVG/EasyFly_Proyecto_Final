<?php
class FlightController extends Controller {
    public function index(){
        $this->view('flights/search');
    }

    public function search(){
        $origin = $_GET['origin'] ?? '';
        $destination = $_GET['destination'] ?? '';
        $date = $_GET['date'] ?? '';
        $flightModel = $this->model('Flight');
        $flights = $flightModel->search($origin, $destination, $date);
        $this->view('flights/results', compact('flights', 'origin', 'destination', 'date'));
    }
}
?>
