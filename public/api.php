<?php
if (!defined('WEBROOT')) {
    define('WEBROOT', dirname(__FILE__));
}

// Init autoloader
require '../system/application.php';

// Prepare app
$app = new Slim(array(
    'mode' => SERVER_MODE,
    'debug' => (SERVER_MODE == 'development') ? true : false,
    'templates.path' => SYSTEM . '/templates',
    'log.enabled' => false,
    'cookies.path' => $config['base_url'],
    'cookies.domain' => isset($config['domain']) ? $config['domain'] : null,
    'cookies.secure' => (bool) $config['ssl'],
    'cookies.httponly' => true
  ));

// Add HTTP Authentication middleware
if(SERVER_MODE == 'test'){
    $app->add(new Middleware_HttpAuth("API Authentication", array('admin'=>'password')));
}elseif ($config['secure']) {
    $app->add(new Middleware_HttpAuth("API Authentication", $config['logins']));
}

// Include Routes
require_once("../system/api_routes.php");

// Run app
$app->run();
