
<?php
class UserModel {
    private $db;
    public function __construct($pdo){ $this->db = $pdo; }
    public function create($name,$email,$pass){
        $sql = 'INSERT INTO usuarios (nombre,email,password) VALUES (?,?,?)';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$name,$email,password_hash($pass,PASSWORD_BCRYPT)]);
    }
    public function findByEmail($email){
        $stmt=$this->db->prepare('SELECT * FROM usuarios WHERE email=?');
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function find($id){
        $stmt=$this->db->prepare('SELECT * FROM usuarios WHERE id=?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
