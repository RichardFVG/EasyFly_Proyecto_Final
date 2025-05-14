<?php
class Database {
    private static $instance = null;
    private $pdo;

    private function __construct(){
        require __DIR__ . '/../../config/config.php';
        $this->pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function getInstance(){
        if(self::$instance === null){
            self::$instance = new self();
        }
        return self::$instance->pdo;
    }
}
?>
