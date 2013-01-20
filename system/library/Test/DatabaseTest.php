<?php

/**
 * Database test case
 *
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @license MIT http://opensource.org/licenses/MIT
 * @package Test
 */
abstract class Test_DatabaseTest extends PHPUnit_Framework_TestCase
{

    /**
     * Database connection
     * @var PDO $dbh
     */
    protected $dbh;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        // Create the Schema and load fixtures
        $this->dbh = Test_Database::initTestDatabase(TESTDIR . '/_data/schema.sql');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->dbh = null;
    }
}
