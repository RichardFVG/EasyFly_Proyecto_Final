
<?php
// Configuración general
define('DB_HOST', 'localhost');
define('DB_NAME', 'easyfly');
define('DB_USER', 'root');
define('DB_PASS', '');

/* Zona horaria de la aplicación (evita desajustes con el cliente) */
date_default_timezone_set('Atlantic/Canary');

/* -----------------  API KEYS  -------------------------------- */
define('MAILJET_API_PUBLIC', 'b202e7778b9637602c4d8fb04b91c499');
define('MAILJET_API_PRIVATE', '80c87ad5670a6b953db04a462f117eef');
define('OPENWEATHER_KEY', 'e99acd182225c930633145d376acb28e');
?>
