<?php

/**
 * Test class for Model_Admin.
 *
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @license MIT http://opensource.org/licenses/MIT
 * @package Test
 * @subpackage Model
 */
class Model_AdminTest extends Test_DatabaseTest
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
     * Model_Admin::getAdminLogins
     */
    public function testGetAdminList()
    {
        $mgr = new Model_Admin();
        $website_id = 1;
        $results = $mgr->getAdminLogins($website_id);
        $this->assertCount(2, $results, "Make sure 2 admins are returned");
    }

    /**
     * Model_Admin::getAdminLoginDetails
     */
    public function testGetAdminLoginDetails()
    {
        $mgr = new Model_Admin();
        $id = 1;
        $website_id = 1;
        $result = $mgr->getAdminLoginDetails($id, $website_id);
        $this->assertEquals($result['id'], $id);
    }

    /**
     * Model_Admin::getAdminLoginDetails
     */
    public function testGetBadAdminLogin()
    {
        $mgr = new Model_Admin();
        $id = 'hat';
        $website_id = 1;
        $result = $mgr->getAdminLoginDetails($id, $website_id);
        $this->assertEquals(null, $result, "Make sure no admin login is returned.");
    }

    /**
     * Model_Admin::addAdminLogin
     */
    public function testAddAdminLogin()
    {
        $mgr = new Model_Admin();
        $website_id = 1;
        $result = array(
          'username' => 'pickle',
          'password' => 'password',
          'url' => 'http://url.com'
        );
        $added = $mgr->addAdminLogin($website_id, $result);
        $result = $mgr->getAdminLoginDetails($added['id'], $website_id);
        $this->assertEquals($added, $result);
        $this->assertEquals('pickle', $result['username']);
    }

    /**
     * Model_Admin::addAdminLogin
     * @expectedException Validate_Exception
     */
    public function testAddAdminLoginFail()
    {
        $this->setExpectedException('Validate_Exception');
        $mgr = new Model_Admin();
        $website_id = 'bad-id';
        $result = array(
          'username' => 'pickle',
          'password' => 'password',
          'url' => 'http://url.com'
        );
        $mgr->addAdminLogin($website_id, $result);
    }

    /**
     * Model_Admin::updateAdminLogin
     */
    public function testUpdateAdminLogin()
    {
        $mgr = new Model_Admin();
        $website_id = 1;
        $id = 1;
        $admin = $mgr->getAdminLoginDetails($id, $website_id);
        $admin['username'] = 'newusername';
        $admin['password'] = 'newpassword';
        $mgr->updateAdminLogin($id, $admin, $website_id);
        $updated = $mgr->getAdminLoginDetails($id, $website_id);
        $this->assertEquals($updated, $admin);
        $this->assertEquals('newusername', $updated['username']);
    }

    /**
     * Model_Admin::updateAdminLogin
     * @expectedException Validate_Exception
     */
    public function testUpdateAdminLoginFail()
    {
        $this->setExpectedException('Validate_Exception');
        $mgr = new Model_Admin();
        $website_id = null;
        $id = 1;
        $admin = $mgr->getAdminLoginDetails($id, $website_id);
        $admin['username'] = 'newusername';
        $admin['password'] = 'newpassword';
        $mgr->updateAdminLogin($id, $admin, $website_id);
    }

    /**
     * Model_Admin::deleteAdminLogin
     */
    public function testDeleteAdminLogin()
    {
        $mgr = new Model_Admin();
        $website_id = 1;
        $id = 1;
        $result = $mgr->getAdminLoginDetails($id, $website_id);
        $this->assertEquals($id, $result['id']);
        $mgr->deleteAdminLogin($id);
        $result = $mgr->getAdminLoginDetails($id, $website_id);
        $this->assertFalse($result);
    }

    /**
     * Model_Admin::validate
     */
    public function testValidateAdminLogin()
    {
        $mgr = new Model_Admin();
        $website_id = 1;
        $data = array(
          'website_id' => $website_id,
          'username' => 'pickel',
          'password' => 'password',
          'url' => 'http://url.com',
          'notes' => null
        );
        $errors = $mgr->validate($data);
        $this->assertInstanceOf('Error_Stack', $errors);
        $this->assertFalse($errors->hasErrors());
    }

    /**
     * Model_Admin::validate
     */
    public function testAdminLoginFailedValidation()
    {
        $mgr = new Model_Admin();
        $website_id = 'bad-id';
        $data = array(
          'website_id' => $website_id,
          'username' => str_repeat('-', 101),
          'password' => str_repeat('-', 101),
          'url' => str_repeat('-', 256),
          'notes' => null
        );
        $errors = $mgr->validate($data);
        $this->assertInstanceOf('Error_Stack', $errors);
        $this->assertTrue($errors->hasErrors());
        $messages = $errors->getErrors();
        $this->assertEquals(array('invalid' => 'Website does not exist.'), $messages['website_id']);
        $this->assertEquals(array('maxlength' => 'Username must not be more than 100 characters.'), $messages['username']);
        $this->assertEquals(array('maxlength' => 'Password must not be more than 100 characters.'), $messages['password']);
        $this->assertEquals(array('maxlength' => 'URL must not be more than 255 characters.'), $messages['url']);
    }

}

