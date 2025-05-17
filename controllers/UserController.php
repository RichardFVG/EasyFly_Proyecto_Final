
<?php
class UserController {
    private $model;
    public function __construct($pdo){ $this->model=new UserModel($pdo); }
    public function login(){
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $email=$_POST['email']??'';
            $pass=$_POST['password']??'';
            $u=$this->model->findByEmail($email);
            if($u && password_verify($pass,$u['password'])){
                Auth::login($u);
                header('Location: default.php');
                exit;
            }
            $error='Credenciales incorrectas';
        }
        require __DIR__.'/../views/login.php';
    }
    public function register(){
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $name=$_POST['name']??'';
            $email=$_POST['email']??'';
            $pass=$_POST['password']??'';
            $this->model->create($name,$email,$pass);
            header('Location: default.php?controller=user&action=login');
            exit;
        }
        require __DIR__.'/../views/register.php';
    }
    public function logout(){
        Auth::logout();
        header('Location: default.php');
    }
    public function profile($pdo){
        Auth::start();
        if(!Auth::check()){ header('Location: default.php'); exit; }
        $reservations = (new ReservationModel($pdo))->userReservations(Auth::user()['id']);
        require __DIR__.'/../views/profile.php';
    }
}
?>
