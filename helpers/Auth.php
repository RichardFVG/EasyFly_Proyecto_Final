
<?php
class Auth {
    public static function start() {
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }
    }
    public static function check() {
        self::start();
        return isset($_SESSION['user']);
    }
    public static function user() {
        self::start();
        return $_SESSION['user'] ?? null;
    }
    public static function isAdmin() {
        return self::check() && $_SESSION['user']['is_admin'];
    }
    public static function login($user){
        self::start();
        $_SESSION['user'] = $user;
    }
    public static function logout(){
        self::start();
        session_destroy();
    }
}
?>
