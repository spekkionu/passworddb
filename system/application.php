<?php
// Define Paths
if (!defined('SYSTEM')) {
    define("SYSTEM", dirname(__FILE__));
}
if (!defined('APP_ROOT')) {
    define("APP_ROOT", dirname(SYSTEM));
}
if (!defined('LIBRARY')) {
    define("LIBRARY", SYSTEM . DIRECTORY_SEPARATOR . 'library');
}
if (!defined('VENDOR')) {
    define("VENDOR", LIBRARY . DIRECTORY_SEPARATOR . 'vendor');
}
if (!defined('WEBROOT')) {
    define('WEBROOT', APP_ROOT . DIRECTORY_SEPARATOR . 'public');
}
if (!defined('TESTDIR')) {
    define('TESTDIR', APP_ROOT . DIRECTORY_SEPARATOR . 'tests');
}

// Load Config
$config = require (SYSTEM . '/config.php');

if (!defined('SERVER_MODE')) {
    $mode = 'production';
    if (isset($config['mode']) && $config['mode']) {
        $mode = mb_strtolower($config['mode']);
    }
    if (isset($_SERVER['HTTP_X_SERVER_MODE'])) {
        $mode = mb_strtolower($_SERVER['HTTP_X_SERVER_MODE']);
    }
    if (getenv('SLIM_MODE')) {
        $mode = mb_strtolower(getenv('SLIM_MODE'));
    }
    if (!in_array($mode, array('development', 'production', 'test'))) {
        $mode = 'production';
    }
    define('SERVER_MODE', $mode);
}


if (version_compare(PHP_VERSION, '5.3.2') >= 0) {
    require (VENDOR . '/autoload.php');
} else {
    require_once VENDOR . '/phly/zf1-autoloaders/library/ZendX/Loader/AutoloaderFactory.php';
    ZendX_Loader_AutoloaderFactory::factory(array(
      'ZendX_Loader_ClassMapAutoloader' => array(
        VENDOR . '/composer/autoload_classmap.php',
      ),
      'ZendX_Loader_StandardAutoloader' => array(
        'prefixes' => array(
          "Slim" => VENDOR . "/slim/slim/Slim",
          "Model" => LIBRARY . "/Model",
          "Controller" => LIBRARY . "/Controller",
          "Validate" => LIBRARY . "/Validate",
          "Error" => LIBRARY . "/Error"
        ),
        'fallback_autoloader' => false,
      ),
    ));
}

if (SERVER_MODE == 'test') {
    $config['base_url'] = $config['test']['base_url'];
    $config['api_url'] = $config['test']['api_url'];
    // Init DB
    $dbh = Test_Database::initTestDatabase(TESTDIR . '/_data/schema.sql');
    Model_Abstract::setCredentials(array(
      'phptype' => 'sqlite',
      'database' => ':memory:'
    ));
    Model_Abstract::setConnection($dbh);
} else {
    // Init DB
    Model_Abstract::setCredentials(array(
      'phptype' => 'sqlite',
      'database' => SYSTEM . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'password.sqlite'
    ));
    if (!file_exists(SYSTEM . '/data/database/password.sqlite')) {
        $dbh = Model_Abstract::getConnection();
        Test_Database::initTestDatabase(SYSTEM . '/data/schema.sql', $dbh);
    }
}

if (!function_exists('http_get_request_body')) {

    function http_get_request_body()
    {
        return @file_get_contents('php://input');
    }
}

