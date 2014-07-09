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
    'log.enabled' => false,
    'cookies.path' => $config['base_url'],
    'cookies.domain' => isset($config['domain']) ? $config['domain'] : null,
    'cookies.secure' => (bool) $config['ssl'],
    'cookies.httponly' => true,
    'base_url' => $config['api_url']
  ));

// Include Routes
require_once("../system/api_routes.php");

// Run app
$app->run();
