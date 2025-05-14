<?php
class User extends Model {
    public function create($name, $email, $password){
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password) VALUES (?,?,?)");
        $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
    }

    public function findByEmail($email){
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function find($id){
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
