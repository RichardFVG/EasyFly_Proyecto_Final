<?php
class Controller {
    protected function view($view, $data = []){
        extract($data);
        require 'app/views/layouts/header.php';
        require "app/views/{$view}.php";
        require 'app/views/layouts/footer.php';
    }

    protected function model($model){
        require_once "app/models/{$model}.php";
        return new $model;
    }
}
?>
