<?php
class HomeController {
    public function index(){
        require_once __DIR__.'/../views/home.php';
    }

    /* ------------------------------------------------------------------
     * Página “Sobre Nosotros”
     * ------------------------------------------------------------------ */
    public function about(){
        require_once __DIR__.'/../views/about.php';
    }
}
?>
