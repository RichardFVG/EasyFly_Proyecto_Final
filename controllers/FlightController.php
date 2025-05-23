<?php
class FlightController {
    private $fm;
    public function __construct($pdo){ $this->fm=new FlightModel($pdo); }
    public function list(){
        $flights=$this->fm->all();
        require __DIR__.'/../views/flights.php';
    }
}
?>
