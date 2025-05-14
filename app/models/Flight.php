<?php
class Flight extends Model {
    public function search($origin, $destination, $date){
        $sql = "SELECT * FROM flights WHERE 1";
        $params = [];
        if($origin){
            $sql .= " AND origin LIKE ?";
            $params[] = "%{$origin}%";
        }
        if($destination){
            $sql .= " AND destination LIKE ?";
            $params[] = "%{$destination}%";
        }
        if($date){
            $sql .= " AND DATE(flight_date) = ?";
            $params[] = $date;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id){
        $stmt = $this->db->prepare("SELECT * FROM flights WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function decrementSeats($id){
        $stmt = $this->db->prepare("UPDATE flights SET seats = seats - 1 WHERE id = ? AND seats > 0");
        $stmt->execute([$id]);
    }
}
?>
