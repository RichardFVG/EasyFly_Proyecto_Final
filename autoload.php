<?php
spl_autoload_register(function($class){
    $paths = [
        __DIR__ . '/controllers/' . $class . '.php',
        __DIR__ . '/models/' . $class . '.php',
        __DIR__ . '/helpers/' . $class . '.php',
    ];
    foreach($paths as $p){
        if(file_exists($p)){ require_once $p; return; }
    }
});
?>
