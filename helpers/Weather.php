
<?php
require_once __DIR__ . '/../config/config.php';

function getWeatherData(){
    if(isset($_SESSION['weather']) && $_SESSION['weather_time'] > time() - 1800){
        return $_SESSION['weather'];
    }
    $ip = $_SERVER['REMOTE_ADDR'];
    $geo = json_decode(@file_get_contents("http://ip-api.com/json/{$ip}"), true);
    if(!$geo || $geo['status'] !== 'success'){
        return null;
    }
    $city = $geo['city'];
    $country = $geo['countryCode'];
    $owmUrl = "https://api.openweathermap.org/data/2.5/weather?q={$city},{$country}&units=metric&lang=es&appid=".OPENWEATHER_KEY;
    $weather = json_decode(@file_get_contents($owmUrl), true);
    if($weather && isset($weather['main'])){
        $_SESSION['weather'] = $weather;
        $_SESSION['weather_time'] = time();
        return $weather;
    }
    return null;
}
?>
