<?php
class ReservationModel {
    private $db;
    public function __construct($pdo){ $this->db=$pdo; }

    private function genCode($pref){
        return strtoupper($pref).substr(bin2hex(random_bytes(3)),0,6);
    }

    /*  -----  creación detallada con fecha_vuelo  -------------- */
    public function createDetailed($userId, $flightId,
                                   array $detalle, $precio, $fechaVuelo){
        $this->db->beginTransaction();
        $fm  = new FlightModel($this->db);
        $ok  = $fm->decrementSeat($flightId);
        if(!$ok){
            $this->db->rollBack();
            return false;
        }
        $flight = $fm->get($flightId);
        $code   = $this->genCode(substr($flight['pais_destino'],0,2));

        $sql = 'INSERT INTO reservas (
                    usuario_id, vuelo_id, codigo_reserva, fecha_vuelo,
                    origen, destino_detalle, pasajero_tipo, equipaje,
                    clase, mascota, precio_final
                ) VALUES (?,?,?,?,?,?,?,?,?,?,?)';
        $this->db->prepare($sql)->execute([
            $userId,
            $flightId,
            $code,
            $fechaVuelo,
            $detalle['Origen'],
            $detalle['Destino'],
            $detalle['Tipo Pasajero'],
            $detalle['Equipaje'],
            $detalle['Clase'],
            $detalle['Mascota'],
            $precio
        ]);
        $this->db->commit();
        return $code;
    }

    /*  -----  MÉTODOS EXISTENTES  ----------------------- */
    public function create($userId,$flight){
        $this->db->beginTransaction();
        $ok = (new FlightModel($this->db))->decrementSeat($flight['id']);
        if(!$ok){
            $this->db->rollBack();
            return false;
        }
        $code=$this->genCode(substr($flight['pais_destino'],0,2));
        $stmt=$this->db->prepare('INSERT INTO reservas (usuario_id, vuelo_id, codigo_reserva)
                                  VALUES (?,?,?)');
        $stmt->execute([$userId,$flight['id'],$code]);
        $this->db->commit();
        return $code;
    }

    public function all(){
        return $this->db->query(
            'SELECT r.id,u.nombre,u.email,
                    v.pais_destino,r.codigo_reserva,
                    r.fecha_vuelo,r.fecha_reserva
             FROM reservas r
             JOIN usuarios u ON u.id=r.usuario_id
             JOIN vuelos v   ON v.id=r.vuelo_id
             ORDER BY r.fecha_reserva DESC'
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public function userReservations($userId){
        $stmt=$this->db->prepare(
            'SELECT r.id,v.pais_destino,r.codigo_reserva,
                    r.fecha_vuelo,r.fecha_reserva
             FROM reservas r
             JOIN vuelos v ON v.id=r.vuelo_id
             WHERE r.usuario_id=?
             ORDER BY r.fecha_reserva DESC'
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id){
        $this->db->beginTransaction();
        $stmt=$this->db->prepare('SELECT vuelo_id FROM reservas WHERE id=?');
        $stmt->execute([$id]);
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        if(!$row){ $this->db->rollBack(); return; }
        (new FlightModel($this->db))->incrementSeat($row['vuelo_id']);
        $this->db->prepare('DELETE FROM reservas WHERE id=?')->execute([$id]);
        $this->db->commit();
    }
}
?>
