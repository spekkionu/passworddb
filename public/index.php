<?php
if (!defined('WEBROOT')) {
    define('WEBROOT', dirname(__FILE__));
}

// Init autoloader
require '../system/application.php';

$twigView = new View_Twig();

View_Twig::$twigOptions = array(
  'debug' => false,
  'cache' => (SERVER_MODE != 'test') ? SYSTEM . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'cache' : null,
  'charset' => 'utf-8',
  'auto_reload' => true,
  'strict_variables' => false,
  'autoescape' => 'html',
  'optimizations' => -1,
);

View_Twig::$twigExtensions = array(
  new View_Twig_Extensions_Slim(),
);
View_Twig::$twigTemplateDirs = array(
  SYSTEM . DIRECTORY_SEPARATOR . 'templates'
);

// Prepare app
$app = new Slim(array(
    'mode' => SERVER_MODE,
    'debug' => (SERVER_MODE == 'development') ? true : false,
    'view' => $twigView,
    'templates.path' => SYSTEM . '/templates',
    'log.enabled' => false,
    'cookies.path' => $config['base_url'],
    'cookies.domain' => isset($config['domain']) ? $config['domain'] : null,
    'cookies.secure' => (bool) $config['ssl'],
    'cookies.httponly' => true,
  ));

// Add HTTP Authentication middleware
if (SERVER_MODE == 'test') {
    $app->add(new Middleware_HttpAuth("API Authentication", array('admin' => 'password')));
} elseif ($config['secure']) {
    $app->add(new Middleware_HttpAuth("API Authentication", $config['logins']));
}
if (SERVER_MODE != 'test') {
    $app->add(new Middleware_CsrfGuard());
}
// Include Routes
require_once("../system/app_routes.php");

session_cache_limiter(false);
session_start();

// Run app
$app->run();
