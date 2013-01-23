<?php
if (!defined('WEBROOT')) {
    define('WEBROOT', dirname(__FILE__));
}

// Init autoloader
require '../system/application.php';

if (SERVER_MODE == 'test') {
  $log = @fopen('php://stderr', 'w');
  $logger = new Slim_LogWriter($log);
}else{
  // Init logging
  $log = @fopen(SYSTEM . "/logs/" . date('y-m-d') . ".log", "a");
  if ($log === false) {
      $log = @fopen('php://stderr', 'w');
      $logger = new Slim_LogWriter($log);
      $logger->write("Log file is not writable.", Slim_Log::ERROR);
  } else {
      $logger = new Slim_LogWriter($log);
  }
}

// Prepare app
$app = new Slim(array(
    'mode' => SERVER_MODE,
    'debug' => (SERVER_MODE == 'development') ? true : false,
    'templates.path' => SYSTEM . '/templates',
    'log.level' => 4,
    'log.enabled' => (SERVER_MODE != 'test') ? true : false,
    'log.writer' => (SERVER_MODE != 'test') ? $logger : null,
));

// Add HTTP Authentication middleware
$app->add(new Middleware_HttpApplicationAuth("API Authentication"));

// Include Routes
require_once("../system/api_routes.php");

// Run app
$app->run();
