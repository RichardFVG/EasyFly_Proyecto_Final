
<?php 
require_once __DIR__ . '/../../helpers/Weather.php';
Auth::start();
$weather=getWeatherData();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>EasyFly - RFVG</title>
<link rel="icon" type="image/png" href="https://i.postimg.cc/dVSggc3v/RFVG.png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-3">
<div class="container">
<a class="navbar-brand" href="default.php">EasyFly</a>
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
<span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="nav">
<ul class="navbar-nav ms-auto">
<?php if(Auth::check()): ?>
<li class="nav-item"><a class="nav-link" href="default.php?controller=user&action=profile">Perfil</a></li>
<?php if(Auth::isAdmin()): ?>
<li class="nav-item"><a class="nav-link" href="default.php?controller=admin&action=reservations">Admin</a></li>
<?php endif; ?>
<li class="nav-item"><a class="nav-link" href="default.php?controller=user&action=logout">Salir</a></li>
<?php else: ?>
<li class="nav-item"><a class="nav-link" href="default.php?controller=user&action=login">Entrar</a></li>
<li class="nav-item"><a class="nav-link" href="default.php?controller=user&action=register">Registro</a></li>
<?php endif; ?>
</ul>
</div>
</div>
</nav>
<?php if($weather): 
$city=$weather['name']; $temp=round($weather['main']['temp']); $desc=$weather['weather'][0]['description']; ?>
<div class="alert alert-info text-center py-1">Clima en <?= htmlspecialchars($city) ?>: <?= $temp ?>°C - <?= htmlspecialchars($desc) ?></div>
<?php endif; ?>
<div class="container">
