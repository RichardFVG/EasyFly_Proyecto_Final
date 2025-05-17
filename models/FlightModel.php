
<?php
class FlightModel {
    private $db;
    public function __construct($pdo){ $this->db=$pdo; }
    public function all(){
        return $this->db->query('SELECT * FROM vuelos')->fetchAll(PDO::FETCH_ASSOC);
    }
    public function get($id){
        $stmt=$this->db->prepare('SELECT * FROM vuelos WHERE id=?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function decrementSeat($id){
        $stmt=$this->db->prepare('UPDATE vuelos SET plazas_disponibles = plazas_disponibles - 1 WHERE id=? AND plazas_disponibles>0');
        $stmt->execute([$id]);
        return $stmt->rowCount()===1;
    }
    public function incrementSeat($id){
        $stmt=$this->db->prepare('UPDATE vuelos SET plazas_disponibles = plazas_disponibles + 1 WHERE id=?');
        $stmt->execute([$id]);
    }
}
?>
