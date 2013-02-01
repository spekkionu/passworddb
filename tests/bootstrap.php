<?php
if (!defined('SERVER_MODE')) {
    define('SERVER_MODE', 'test');
}

require dirname(dirname(__FILE__)) . '/system/application.php';

$config['secure'] = true;

// Login Credentials
$config['logins'] = array(
  'admin' => 'password'
);
