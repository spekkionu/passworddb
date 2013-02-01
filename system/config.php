<?php
$config = array();

// Set to true to force ssl
$config['ssl'] = false;

// The directory the application is installed in.
// Must begin and end with a slash.
// If application is in a subdirectory should be "/subdirectory/"
$config['base_url'] = '/';

// If true force login to access
$config['secure'] = false;

// Default logins, should be overwritten in config.local.php with desired usernames and passwords
$config['logins'] = array(
  'admin' => 'password',
);

// Settings for testing
$config['test']['hostname'] = 'http://127.0.0.1:8000';
$config['test']['base_url'] = '/api.php/';

// Include local config with overrides
if (file_exists(dirname(__FILE__) . '/config.local.php')) {
    include(dirname(__FILE__) . '/config.local.php');
}

return $config;