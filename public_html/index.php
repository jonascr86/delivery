<?php

session_start();

define('PROJECT_NAME', 'SofitCooks');
define('PUBLIC_DIR', __DIR__);
define('TEMPLATES_DIR', PUBLIC_DIR . '/templates');

define('ROOT_DIR', __DIR__ . '/..');
define('APP_DIR', ROOT_DIR . '/app');

define('ROOT_URL', 'http://localhost/delivery/public_html/');
define('IDLE_TIME_SECS', 30);

date_default_timezone_set('America/Sao_Paulo');

include( ROOT_DIR . '/vendor/autoload.php');

try {
    $dbconfig = include(APP_DIR . '/Config/database.php');

    $dbConn = new \Simplon\Mysql\Mysql(
            $dbconfig['host'], 
            $dbconfig['user'], 
            $dbconfig['password'], 
            $dbconfig['database'], 
            $dbconfig['fetchMode'], 
            $dbconfig['charset'], 
            $dbconfig['options']
    );
    Delivery\Registry::add($dbConn, 'appdb');

    $router = new Delivery\Router\GETRouter('action', new \Delivery\Helpers\URL\GETURLBuilder());


    Delivery\Registry::add($router, 'approuter');
    include(APP_DIR . '/Config/routes.php');

    Delivery\Registry::get('approuter')->dispatch();
    
} catch (\Delivery\Exceptions\RouterException $e) {
    echo "Erro de rotas: " . $e->getMessage();
} catch (\Exception $e) {
    echo $e->getMessage();
}