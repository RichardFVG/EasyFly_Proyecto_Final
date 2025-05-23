<?php
require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/helpers/Auth.php';
$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';
$controllerClass = ucfirst($controller) . 'Controller';
if(!class_exists($controllerClass)){
    die('Controlador no encontrado');
}
$ctr = new $controllerClass($pdo ?? null);
if(!method_exists($ctr,$action)){
    die('Acción no encontrada');
}
$ctr->$action();
?>
