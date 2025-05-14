<?php
class Reservation extends Model {
    public function create($userId, $flightId){
        $stmt = $this->db->prepare("INSERT INTO reservations (user_id, flight_id) VALUES (?,?)");
        $stmt->execute([$userId, $flightId]);
    }

    public function byUser($userId){
        $stmt = $this->db->prepare("
            SELECT r.id, r.reserved_at, f.origin, f.destination, f.flight_date, f.price
            FROM reservations r
            JOIN flights f ON f.id = r.flight_id
            WHERE r.user_id = ?
            ORDER BY r.reserved_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
