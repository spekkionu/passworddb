<?php
$config = array();

$config['test']['hostname'] = 'http://localhost';
$config['test']['base_url'] = '/api.php/';

// Include local config with overrides
if (file_exists(dirname(__FILE__) . '/config.local.php')) {
    include(dirname(__FILE__) . '/config.local.php');
}

return $config;