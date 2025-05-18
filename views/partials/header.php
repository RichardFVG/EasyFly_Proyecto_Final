<?php 
require_once __DIR__ . '/../../helpers/Weather.php';
Auth::start();
$weather = getWeatherData();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>EasyFly - RFVG</title>

<!-- Favicon ----------------------------------------------------------------->
<link rel="icon" type="image/png" href="https://i.postimg.cc/dVSggc3v/RFVG.png">

<!-- Bootstrap & hoja de estilos -------------------------------------------->
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

    <!-- Nombre del usuario (admin ⇒ “Administrador”) ------------------------>
    <?php if (Auth::check()): ?>
      <span class="navbar-text user-name ms-auto me-2 d-none d-sm-inline">
        <?= Auth::isAdmin() ? 'Administrador' : htmlspecialchars(Auth::user()['nombre']) ?>
      </span>
    <?php endif; ?>

    <!-- Botón hamburguesa ---------------------------------------------------->
    <button class="navbar-toggler border-0" type="button"
            data-bs-toggle="collapse" data-bs-target="#navMenu"
            aria-controls="navMenu" aria-expanded="false" aria-label="Menú de navegación">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="40"
          height="40"
          viewBox="0 0 24 24"
          fill="none"
          stroke="#ffffff"
          stroke-width="1.75"
          stroke-linecap="round"
          stroke-linejoin="round"
        >
          <path d="M4 6l16 0" />
          <path d="M4 12l16 0" />
          <path d="M4 18l16 0" />
        </svg>
    </button>

    <!-- Opciones de usuario -------------------------------------------------->
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto text-center">
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

<?php if ($weather):
      $city = $weather['name'];
      $temp = round($weather['main']['temp']);
      $desc = $weather['weather'][0]['description']; ?>
  <div class="alert alert-info text-center py-1">
    Clima en <?= htmlspecialchars($city) ?>: <?= $temp ?>°C - <?= htmlspecialchars($desc) ?>
  </div>
<?php endif; ?>

<div class="container">
