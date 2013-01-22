<?php

/**
 * Test class for Model_Abstract.
 *
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @license MIT http://opensource.org/licenses/MIT
 * @package Test
 * @subpackage Model
 */
class Model_AbstractTest extends PHPUnit_Framework_TestCase
{

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();
        Model_Abstract::close();
        Model_Abstract::setCredentials(array());
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        Model_Abstract::close();
        Model_Abstract::setCredentials(array());
        parent::tearDown();
    }

    /**
     * Model_Abstract::setCredentials
     * Model_Abstract::getCredentials
     */
    public function testSetCredentials()
    {
        $credentials = array(
          'phptype' => 'sqlite',
          'hostname' => null,
          'port' => null,
          'username' => null,
          'password' => null,
          'database' => ":memory:",
          'options' => array(),
        );
        Model_Abstract::setCredentials($credentials);
        $result = Model_Abstract::getCredentials();
        $this->assertEquals($result, $credentials);
    }

    /**
     * Model_Abstract::connect
     */
    public function testConnect()
    {
        $credentials = array(
          'phptype' => 'sqlite',
          'hostname' => null,
          'port' => null,
          'username' => null,
          'password' => null,
          'database' => ":memory:",
          'options' => array(),
        );
        $conn = Model_Abstract::connect($credentials);
        $this->assertInstanceOf('PDO', $conn);
    }

    /**
     * Model_Abstract::connect
     * @expectedException PDOException
     */
    public function testConnectFail()
    {
        $credentials = array(
          'phptype' => 'badtype',
          'hostname' => '127.0.0.1',
          'port' => 3306,
          'username' => 'user',
          'password' => 'password',
          'database' => 'database',
          'options' => array(),
        );
        Model_Abstract::connect($credentials);
    }

    /**
     * Model_Abstract::getConnection
     */
    public function testGetConnection()
    {
        $credentials = array(
          'phptype' => 'sqlite',
          'hostname' => null,
          'port' => null,
          'username' => null,
          'password' => null,
          'database' => ":memory:",
          'options' => array(),
        );
        Model_Abstract::setCredentials($credentials);
        $conn = Model_Abstract::getConnection();
        $this->assertInstanceOf('PDO', $conn);
    }

    /**
     * Model_Abstract::setConnection
     */
    public function testSetConnection()
    {
        $dbh = new PDO("sqlite::memory:");
        Model_Abstract::setConnection($dbh);
        $conn = Model_Abstract::getConnection();
        $this->assertInstanceOf('PDO', $conn);
        $this->assertEquals($conn, $dbh);
    }
}

