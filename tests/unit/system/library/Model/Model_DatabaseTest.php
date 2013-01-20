<?php

/**
 * Test class for Model_Database.
 *
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @license MIT http://opensource.org/licenses/MIT
 * @package Test
 * @subpackage Model
 */
class Model_DatabaseTest extends Test_DatabaseTest
{

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();
        // Register the connection
        Model_Abstract::setConnection($this->dbh);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        Model_Abstract::close();
        parent::tearDown();
    }

    /**
     * Model_Database::getDBLogins
     */
    public function testGetDatabseList()
    {
        $mgr = new Model_Database();
        $website_id = 1;
        $results = $mgr->getDBLogins($website_id);
        $this->assertCount(1, $results, "Make sure 1 database login is returned");
    }

    /**
     * Model_Database::getDBDetails
     */
    public function testGetDatabaseDetails()
    {
        $mgr = new Model_Database();
        $id = 1;
        $website_id = 1;
        $result = $mgr->getDBDetails($id, $website_id);
        $this->assertEquals($result['id'], $id);
    }

    /**
     * Model_Database::getDBDetails
     */
    public function testGetBadDatabaseLogin()
    {
        $mgr = new Model_Database();
        $id = 'hat';
        $website_id = 1;
        $result = $mgr->getDBDetails($id, $website_id);
        $this->assertEquals(null, $result, "Make sure no database login is returned.");
    }

    /**
     * Model_Database::addDB
     */
    public function testAddDatabaseLogin()
    {
        $mgr = new Model_Database();
        $website_id = 1;
        $result = array(
          'type' => 'mysql',
          'hostname' => 'localhost',
          'username' => 'pickle',
          'password' => 'password',
          'database' => 'dbname',
          'url' => 'http://url.com'
        );
        $added = $mgr->addDB($website_id, $result);
        $result = $mgr->getDBDetails($added['id'], $website_id);
        $this->assertEquals($added, $result);
        $this->assertEquals('pickle', $result['username']);
    }

    /**
     * Model_Database::updateDB
     */
    public function testUpdateDatabaseLogin()
    {
        $mgr = new Model_Database();
        $website_id = 1;
        $id = 1;
        $result = $mgr->getDBDetails($id, $website_id);
        $result['hostname'] = 'newhostname';
        $result['username'] = 'newusername';
        $result['password'] = 'newpassword';
        $result['database'] = 'newdatabase';
        $mgr->updateDB($id, $result, $website_id);
        $updated = $mgr->getDBDetails($id, $website_id);
        $this->assertEquals($updated, $result);
        $this->assertEquals('newusername', $updated['username']);
    }

    /**
     * Model_Database::deleteDB
     */
    public function testDeleteDatabaseLogin()
    {
        $mgr = new Model_Database();
        $website_id = 1;
        $id = 1;
        $result = $mgr->getDBDetails($id, $website_id);
        $this->assertEquals($id, $result['id']);
        $mgr->deleteDB($id);
        $result = $mgr->getDBDetails($id, $website_id);
        $this->assertFalse($result);
    }

    /**
     * Model_Database::validate
     */
    public function testValidateDatabaseLogin()
    {
        $mgr = new Model_Admin();
        $website_id = 1;
        $data = array(
          'website_id' => $website_id,
          'type' => 'mysql',
          'hostname' => 'localhost',
          'username' => 'pickel',
          'password' => 'password',
          'database' => 'dbname',
          'url' => 'http://url.com',
          'notes' => null
        );
        $errors = $mgr->validate($data);
        $this->assertInstanceOf('Error_Stack', $errors);
        $this->assertFalse($errors->hasErrors());
    }

    /**
     * Model_Database::validate
     */
    public function testDatabaseLoginFailedValidation()
    {
        $mgr = new Model_Database();
        $website_id = 'bad-id';
        $data = array(
          'website_id' => $website_id,
          'type' => 'mysql',
          'hostname' => 'localhost',
          'username' => 'pickel',
          'password' => 'password',
          'url' => 'http://url.com',
          'database' => 'dbname',
          'notes' => null
        );
        $errors = $mgr->validate($data);
        $this->assertInstanceOf('Error_Stack', $errors);
        $this->assertTrue($errors->hasErrors());
        $messages = $errors->getErrors();
        $this->assertEquals(array('website_id' => array('Website does not exist.')), $messages);
    }

}

