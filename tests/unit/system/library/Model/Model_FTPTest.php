<?php

/**
 * Test class for Model_FTP.
 *
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @license MIT http://opensource.org/licenses/MIT
 * @package Test
 * @subpackage Model
 */
class Model_FTPTest extends Test_DatabaseTest
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
     * Model_FTP::getFTPLogins
     */
    public function testGetFTPLoginList()
    {
        $mgr = new Model_FTP();
        $website_id = 1;
        $results = $mgr->getFTPLogins($website_id);
        $this->assertCount(1, $results, "Make sure 1 ftp login id returned");
    }

    /**
     * Model_FTP::getFTPDetails
     */
    public function testGetFTPLoginDetails()
    {
        $mgr = new Model_FTP();
        $id = 1;
        $website_id = 1;
        $result = $mgr->getFTPDetails($id, $website_id);
        $this->assertEquals($result['id'], $id);
    }

    /**
     * Model_FTP::getFTPDetails
     */
    public function testGetBadFTPLogin()
    {
        $mgr = new Model_FTP();
        $id = 'hat';
        $website_id = 1;
        $result = $mgr->getFTPDetails($id, $website_id);
        $this->assertEquals(null, $result, "Make sure no admin login is returned.");
    }

    /**
     * Model_FTP::addFTP
     */
    public function testAddFTPLogin()
    {
        $mgr = new Model_FTP();
        $website_id = 1;
        $result = array(
          'type' => 'ftp',
          'username' => 'pickle',
          'password' => 'password',
          'hostname' => 'example.com',
          'path' => '/var/www',
          'notes' => 'ftp notes'
        );
        $added = $mgr->addFTP($website_id, $result);
        $result = $mgr->getFTPDetails($added['id'], $website_id);
        $this->assertEquals($added, $result);
        $this->assertEquals('pickle', $result['username']);
    }

    /**
     * Model_FTP::updateFTP
     */
    public function testUpdateFTPLogin()
    {
        $mgr = new Model_FTP();
        $website_id = 1;
        $id = 1;
        $result = $mgr->getFTPDetails($id, $website_id);
        $result['username'] = 'newusername';
        $result['password'] = 'newpassword';
        $mgr->updateFTP($id, $result, $website_id);
        $updated = $mgr->getFTPDetails($id, $website_id);
        $this->assertEquals($updated, $result);
        $this->assertEquals('newusername', $updated['username']);
    }

    /**
     * Model_FTP::deleteFTP
     */
    public function testDeleteFTPLogin()
    {
        $mgr = new Model_FTP();
        $website_id = 1;
        $id = 1;
        $result = $mgr->getFTPDetails($id, $website_id);
        $this->assertEquals($id, $result['id']);
        $mgr->deleteFTP($id);
        $result = $mgr->getFTPDetails($id, $website_id);
        $this->assertFalse($result);
    }

    /**
     * Model_FTP::deleteFTP
     */
    public function testValidateFTPLogin()
    {
        $mgr = new Model_FTP();
        $website_id = 1;
        $data = array(
          'website_id' => $website_id,
          'type' => 'ftp',
          'username' => 'pickel',
          'password' => 'password',
          'hostname' => 'example.com',
          'path' => '/var/www',
          'notes' => 'ftp notes'
        );
        $errors = $mgr->validate($data);
        $this->assertInstanceOf('Error_Stack', $errors);
        $this->assertFalse($errors->hasErrors());
    }

    /**
     * Model_FTP::validate
     */
    public function testFTPLoginFailedValidation()
    {
        $mgr = new Model_FTP();
        $website_id = 'bad-id';
        $data = array(
          'website_id' => $website_id,
          'type' => 'ftp',
          'username' => 'pickel',
          'password' => 'password',
          'hostname' => 'example.com',
          'path' => '/var/www',
          'notes' => 'ftp notes'
        );
        $errors = $mgr->validate($data);
        $this->assertInstanceOf('Error_Stack', $errors);
        $this->assertTrue($errors->hasErrors());
        $messages = $errors->getErrors();
        $this->assertEquals(array('website_id' => array('Website does not exist.')), $messages);
    }

}

