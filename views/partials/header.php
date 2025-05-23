<?php 
require_once __DIR__ . '/../../helpers/Weather.php';
Auth::start();

/* ================================================================
 *  Aviso de Cookies
 * ================================================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* ---- El usuario ACEPTA ------------------------------------- */
    if (isset($_POST['aceptar_cookies'])) {
        /* Creamos la cookie válida un año, accesible en todo el dominio */
        setcookie(
            'politica_cookies',
            '2do CFGS DAW, Richard Francisco Vaca Garcia',   // ← nuevo valor
            time() + 365 * 24 * 60 * 60,
            '/'
        );

    /* ---- El usuario CANCELA ------------------------------------ */
    } elseif (isset($_POST['cancelar_cookies'])) {
        /* Solo ocultamos el aviso en esta carga */
        $_SESSION['ocultar_aviso'] = true;
    }

    /* ---- Redirección segura al mismo recurso ------------------- */
    $target = $_SERVER['PHP_SELF'];
    if (!empty($_SERVER['QUERY_STRING'])) {
        $target .= '?' . $_SERVER['QUERY_STRING'];
    }
    header('Location: ' . $target);
    exit;
}

/* ¿Debemos mostrar el aviso? */
$showCookieBanner = !isset($_COOKIE['politica_cookies'])
                    && empty($_SESSION['ocultar_aviso']);

/* Restablecemos la variable para próximas cargas */
if (isset($_SESSION['ocultar_aviso'])) {
    unset($_SESSION['ocultar_aviso']);
}

$weather = getWeatherData();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">

<!--  Mantiene la anchura lógica del viewport y evita zooms raros   -->
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<title>EasyFly - RFVG</title>

<link rel="icon" type="image/png" href="https://i.postimg.cc/dVSggc3v/RFVG.png">

<!-- Bootstrap + CSS propio -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-primary mb-3"><!-- sin “navbar-expand” ⇒ siempre colapsado -->
  <div class="container">

    <!-- Marca (logo + texto) ------------------------------------------------->
    <a class="navbar-brand d-flex align-items-center" href="default.php">
        <img src="assets/img/RFVG.png" alt="Logo EasyFly" class="logo-img">
        <span>EasyFly</span>
    </a>

    <!-- Nombre del usuario (admin ⇒ “Administrador”) ------------------------->
    <?php if (Auth::check()): ?>
      <span class="navbar-text user-name ms-auto me-2 d-none d-sm-inline">
        <?= Auth::isAdmin() ? 'Administrador' : htmlspecialchars(Auth::user()['nombre']) ?>
      </span>
    <?php endif; ?>

    <!-- Botón hamburguesa ----------------------------------------------------->
    <button class="navbar-toggler border-0" type="button"
            data-bs-toggle="collapse" data-bs-target="#navMenu"
            aria-controls="navMenu" aria-expanded="false" aria-label="Menú de navegación">
        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"
             fill="none" stroke="#ffffff" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
          <path d="M4 6l16 0" />
          <path d="M4 12l16 0" />
          <path d="M4 18l16 0" />
        </svg>
    </button>

    <!-- Opciones de usuario --------------------------------------------------->
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto text-center">

        <!-- NUEVO ▸ Sobre Nosotros (visible siempre) -->
        <li class="nav-item">
          <a class="nav-link" href="default.php?controller=home&action=about">
            Sobre&nbsp;Nosotros
          </a>
        </li>

        <?php if (Auth::check()): ?>
          <li class="nav-item">
            <a class="nav-link" href="default.php?controller=user&action=profile">Perfil</a>
          </li>

          <?php if (Auth::isAdmin()): ?>
          <li class="nav-item">
            <a class="nav-link" href="default.php?controller=admin&action=reservations">Admin</a>
          </li>
          <?php endif; ?>

          <li class="nav-item">
            <a class="nav-link" href="default.php?controller=user&action=logout">Salir</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link" href="default.php?controller=user&action=login">Entrar</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="default.php?controller=user&action=register">Registro</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Aviso de cookies -------------------------------------------------------->
<?php if ($showCookieBanner): ?>
  <div style="background-color:#000000;color:#39FF14;
              border-top-left-radius:25px;border-top-right-radius:25px;
              padding:20px;position:fixed;bottom:0;width:100%;
              text-align:center;z-index:1050;">
    <form method="POST" style="display:inline;">
        <span style="font-size:larger;">
            Este sitio web utiliza cookies para mejorar su experiencia.
        </span><br><br>
        <button type="submit" name="aceptar_cookies"
                style="background-color:red;color:#0021F3;border-radius:10px;
                       font-size:large;cursor:pointer;padding:5px 10px;
                       border: solid #39FF14 2px;">
            Aceptar
        </button>
        <button type="submit" name="cancelar_cookies"
                style="background-color:gray;color:white;border-radius:10px;
                       font-size:large;cursor:pointer;padding:5px 10px;
                       border: solid #39FF14 2px;margin-left:10px;">
            Cancelar
        </button>
    </form>
  </div>
<?php endif; ?>

<?php if ($weather):
      $city = $weather['name'];
      $temp = round($weather['main']['temp']);
      $desc = $weather['weather'][0]['description']; ?>
  <div class="alert alert-info text-center py-1">
    Clima en <?= htmlspecialchars($city) ?>: <?= $temp ?>°C - <?= htmlspecialchars($desc) ?>
  </div>
<?php endif; ?>

<div class="container">
