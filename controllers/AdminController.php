<?php
class AdminController {
    private $pdo;
    public function __construct($pdo){ $this->pdo=$pdo; }
    public function reservations(){
        if(!Auth::isAdmin()){ header('Location: default.php'); exit; }
        $list=(new ReservationModel($this->pdo))->all();
        require __DIR__.'/../views/admin/reservations_list.php';
    }
}
?>
