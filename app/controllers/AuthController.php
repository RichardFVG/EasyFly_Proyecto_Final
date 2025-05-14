<?php
class AuthController extends Controller {
    public function login(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $userModel = $this->model('User');
            $user = $userModel->findByEmail($email);
            if($user && password_verify($password, $user['password'])){
                $_SESSION['user_id'] = $user['id'];
                header('Location: default.php');
                exit;
            }
            $error = 'Credenciales inválidas';
            return $this->view('auth/login', compact('error'));
        }
        $this->view('auth/login');
    }

    public function register(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $userModel = $this->model('User');

            if($userModel->findByEmail($email)){
                $error = 'El email ya existe';
                return $this->view('auth/register', compact('error'));
            }
            $userModel->create($name, $email, $password);
            header('Location: default.php?url=auth/login');
            exit;
        }
        $this->view('auth/register');
    }

    public function logout(){
        session_destroy();
        header('Location: default.php');
    }
}
?>
