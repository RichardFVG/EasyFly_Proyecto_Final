<?php
class Router {
    public function run(){
        $url = isset($_GET['url']) ? trim($_GET['url'], '/') : '';
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $parts = explode('/', $url);

        $controllerName = !empty($parts[0]) ? ucfirst($parts[0]) . 'Controller' : 'FlightController';
        $method = $parts[1] ?? 'index';
        $params = array_slice($parts, 2);

        $controllerFile = 'app/controllers/' . $controllerName . '.php';
        if(file_exists($controllerFile)){
            require_once $controllerFile;
            $controller = new $controllerName;
            if(method_exists($controller, $method)){
                call_user_func_array([$controller, $method], $params);
                return;
            }
        }

        require_once 'app/controllers/FlightController.php';
        (new FlightController)->index();
    }
}
?>
