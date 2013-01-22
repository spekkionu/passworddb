<?php

/**
 * Base Model Class
 *
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @license MIT http://opensource.org/licenses/MIT
 * @package Model
 */
abstract class Model_Abstract
{

    /**
     * Database Credentials
     * @var array $credentials
     */
    private static $credentials = null;

    /**
     * Database Connection
     * @var PDO $dbh
     */
    private static $dbh = null;

    /**
     * Sets database credentials
     * @param array $config
     * @return void
     */
    public static function setCredentials(array $config)
    {
        // If credentials changed reset connection
        self::$dbh = null;
        $template = array(
          'phptype' => null,
          'hostname' => null,
          'port' => null,
          'username' => null,
          'password' => null,
          'database' => null,
          'options' => array(),
        );
        $config = array_merge($template, array_intersect_key($config, $template));
        self::$credentials = $config;
    }

    /**
     * Returns database credentials
     * @return array
     */
    public static function getCredentials()
    {
        return self::$credentials;
    }

    /**
     * Connects to database
     *
     * @param array $config
     * @return PDO
     */
    public static function connect(array $config = null)
    {
        if ($config) {
            self::setCredentials($config);
        }
        $config = self::getCredentials();
        if ($config['phptype'] != 'sqlite') {
            self::$dbh = new PDO(
              "{$config['phptype']}:host={$config['hostname']}:{$config['port']};dbname={$config['database']}",
              $config['username'],
              $config['password'],
              $config['options']
            );
        } else {
            self::$dbh = new PDO(
              "{$config['phptype']}:{$config['database']}",
              $config['username'],
              $config['password'],
              $config['options']
            );
        }
        self::$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return self::$dbh;
    }

    /**
     * Closes database connection
     * @return void
     */
    public static function close()
    {
        self::$dbh = null;
    }

    /**
     * Sets the database connection
     * @param PDO $dbh
     */
    public static function setConnection(PDO $dbh)
    {
        self::$dbh = $dbh;
    }

    /**
     * Returns database connection
     * Connects if not connected
     * @return PDO
     */
    public static function getConnection()
    {
        if (is_null(self::$dbh)) {
            self::connect();
        }
        return self::$dbh;
    }

    /**
     * Filters array of values
     * @param array $data Data to filter
     * @param array $allowed Allowed columns
     * @return array Filtered data array
     */
    public function filterData(array $data, array $allowed)
    {
        $default = array_fill_keys($allowed, null);
        $data = array_merge($default, array_intersect_key($data, $default));
        array_walk($data, array($this, 'filterValue'));
        return $data;
    }

    /**
     * Filters a single value
     * @param mixed $value
     * @return string Filtered value
     */
    public function filterValue($value)
    {
        $value = trim($value);
        if ($value == '') {
            $value = null;
        }
        return $value;
    }
}
