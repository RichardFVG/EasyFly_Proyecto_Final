<?php
class UserController {
    private $pdo;
    private $model;

    public function __construct($pdo){
        // guardamos la conexión para usarla en otros métodos
        $this->pdo   = $pdo;
        $this->model = new UserModel($pdo);
    }

    /** Login: ahora acepta “identifier” (email o nombre) + password */
    public function login(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $identifier = trim($_POST['identifier'] ?? '');
            $pass       = $_POST['password'] ?? '';

            // nuevo método que busca por email O por nombre
            $u = $this->model->findByIdentifier($identifier);

            if ($u && password_verify($pass, $u['password'])){
                Auth::login($u);
                header('Location: default.php');
                exit;
            }
            $error = 'Credenciales incorrectas';
        }

        require __DIR__ . '/../views/login.php';
    }

    public function register(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $name  = $_POST['name']     ?? '';
            $email = $_POST['email']    ?? '';
            $pass  = $_POST['password'] ?? '';

            $this->model->create($name, $email, $pass);

            header('Location: default.php?controller=user&action=login');
            exit;
        }

        require __DIR__ . '/../views/register.php';
    }

    public function logout(){
        Auth::logout();
        header('Location: default.php');
    }

    public function profile(){
        Auth::start();

        if (!Auth::check()){
            header('Location: default.php');
            exit;
        }

        $reservations = (new ReservationModel($this->pdo))
                            ->userReservations(Auth::user()['id']);

        require __DIR__ . '/../views/profile.php';
    }

    public function deleteAccount(){
        Auth::start();

        if (!Auth::check()){
            header('Location: default.php');
            exit;
        }

        if (Auth::isAdmin()){
            header('Location: default.php');
            exit;
        }

        $id = Auth::user()['id'];
        $this->model->delete($id);
        Auth::logout();
        header('Location: default.php');
        exit;
    }
}
?>
