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

    /** Registro con validación de campos y formato de email */
    public function register(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $name  = trim($_POST['name']     ?? '');
            $email = trim($_POST['email']    ?? '');
            $pass  = $_POST['password']      ?? '';

            /* ----------  Validaciones  ---------- */
            if ($name === '' || $email === '' || $pass === ''){
                $error = 'Todos los campos son obligatorios.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $error = 'El email introducido no es válido.';
            } elseif ($this->model->findByEmail($email)){
                $error = 'Ese email ya está en uso.';
            } else {
                /* Registro correcto */
                $this->model->create($name, $email, $pass);
                header('Location: default.php?controller=user&action=login');
                exit;
            }
        }

        /* Carga de la vista (con posible $error definido arriba) */
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

    /* ================================================================
     *  MÉTODOS DE ACTUALIZACIÓN SEGUROS
     * ================================================================ */

    /** POST: new_name + password */
    public function updateName(){
        Auth::start();
        if (!Auth::check()){
            header('Location: default.php');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
            header('Location: default.php?controller=user&action=profile');
            exit;
        }

        $newName  = trim($_POST['new_name'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($newName === ''){
            $_SESSION['profile_error'] = 'El nombre no puede estar vacío.';
            header('Location: default.php?controller=user&action=profile');
            exit;
        }

        $user = $this->model->find(Auth::user()['id']);
        if (!password_verify($password, $user['password'])){
            $_SESSION['profile_error'] = 'Contraseña incorrecta.';
            header('Location: default.php?controller=user&action=profile');
            exit;
        }

        $ok = $this->model->updateName($user['id'], $newName);
        if ($ok){
            $_SESSION['user']['nombre'] = $newName;           // refrescar sesión
            $_SESSION['profile_success'] = 'Nombre actualizado correctamente.';
        } else {
            $_SESSION['profile_error'] = 'No se pudo actualizar el nombre.';
        }
        header('Location: default.php?controller=user&action=profile');
    }

    /** POST: new_email + password */
    public function updateEmail(){
        Auth::start();
        if (!Auth::check()){
            header('Location: default.php');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
            header('Location: default.php?controller=user&action=profile');
            exit;
        }

        $newEmail = trim($_POST['new_email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($newEmail === '' || !filter_var($newEmail, FILTER_VALIDATE_EMAIL)){
            $_SESSION['profile_error'] = 'Email no válido.';
            header('Location: default.php?controller=user&action=profile');
            exit;
        }

        $user = $this->model->find(Auth::user()['id']);
        if (!password_verify($password, $user['password'])){
            $_SESSION['profile_error'] = 'Contraseña incorrecta.';
            header('Location: default.php?controller=user&action=profile');
            exit;
        }

        // Evitar duplicados
        $exists = $this->model->findByEmail($newEmail);
        if ($exists && $exists['id'] !== $user['id']){
            $_SESSION['profile_error'] = 'Ese email ya está en uso.';
            header('Location: default.php?controller=user&action=profile');
            exit;
        }

        $ok = $this->model->updateEmail($user['id'], $newEmail);
        if ($ok){
            $_SESSION['user']['email'] = $newEmail;           // refrescar sesión
            $_SESSION['profile_success'] = 'Email actualizado correctamente.';
        } else {
            $_SESSION['profile_error'] = 'No se pudo actualizar el email.';
        }
        header('Location: default.php?controller=user&action=profile');
    }
}
?>
