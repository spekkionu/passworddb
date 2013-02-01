<?php

/**
 * Test class for Model_ControlPanel.
 *
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @license MIT http://opensource.org/licenses/MIT
 * @package Test
 * @subpackage Model
 */
class Model_ControlPanelTest extends Test_DatabaseTest
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
     * Model_ControlPanel::getControlPanelLogins
     */
    public function testGetConrolPanelList()
    {
        $mgr = new Model_ControlPanel();
        $website_id = 1;
        $results = $mgr->getControlPanelLogins($website_id);
        $this->assertCount(2, $results, "Make sure 2 control panel is returned");
    }

    /**
     * Model_ControlPanel::getControlPanelDetails
     */
    public function testGetConrolPanelLoginDetails()
    {
        $mgr = new Model_ControlPanel();
        $id = 1;
        $website_id = 1;
        $result = $mgr->getControlPanelDetails($id, $website_id);
        $this->assertEquals($result['id'], $id);
    }

    /**
     * Model_ControlPanel::getControlPanelDetails
     */
    public function testGetBadControlPanelLogin()
    {
        $mgr = new Model_ControlPanel();
        $id = 'hat';
        $website_id = 1;
        $result = $mgr->getControlPanelDetails($id, $website_id);
        $this->assertEquals(null, $result, "Make sure no control panel login is returned.");
    }

    /**
     * Model_ControlPanel::addControlPanelLogin
     */
    public function testAddControlPanelLogin()
    {
        $mgr = new Model_ControlPanel();
        $website_id = 1;
        $result = array(
          'username' => 'pickle',
          'password' => 'password',
          'url' => 'http://url.com'
        );
        $added = $mgr->addControlPanelLogin($website_id, $result);
        $result = $mgr->getControlPanelDetails($added['id'], $website_id);
        $this->assertEquals($added, $result);
        $this->assertEquals('pickle', $result['username']);
    }

    /**
     * Model_ControlPanel::addControlPanelLogin
     * @expectedException Validate_Exception
     */
    public function testAddControlPanelLoginFail()
    {
        $mgr = new Model_ControlPanel();
        $website_id = 'bad-id';
        $result = array(
          'username' => 'pickle',
          'password' => 'password',
          'url' => 'http://url.com'
        );
        $mgr->addControlPanelLogin($website_id, $result);
    }

    /**
     * Model_ControlPanel::updateControlPanelLogin
     */
    public function testUpdateControlPanelLogin()
    {
        $mgr = new Model_ControlPanel();
        $website_id = 1;
        $id = 1;
        $result = $mgr->getControlPanelDetails($id, $website_id);
        $result['username'] = 'newusername';
        $result['password'] = 'newpassword';
        $mgr->updateControlPanelLogin($id, $result, $website_id);
        $updated = $mgr->getControlPanelDetails($id, $website_id);
        $this->assertEquals($updated, $result);
        $this->assertEquals('newusername', $updated['username']);
    }

    /**
     * Model_ControlPanel::updateControlPanelLogin
     * @expectedException Validate_Exception
     */
    public function testUpdateControlPanelLoginFail()
    {
        $mgr = new Model_ControlPanel();
        $website_id = null;
        $id = 1;
        $result = $mgr->getControlPanelDetails($id, $website_id);
        $result['username'] = 'newusername';
        $result['password'] = 'newpassword';
        $mgr->updateControlPanelLogin($id, $result, $website_id);
        $updated = $mgr->getControlPanelDetails($id, $website_id);
        $this->assertEquals($updated, $result);
        $this->assertEquals('newusername', $updated['username']);
    }

    /**
     * Model_ControlPanel::deleteControlPanelLogin
     */
    public function testDeleteControlPanelLogin()
    {
        $mgr = new Model_ControlPanel();
        $website_id = 1;
        $id = 1;
        $result = $mgr->getControlPanelDetails($id, $website_id);
        $this->assertEquals($id, $result['id']);
        $mgr->deleteControlPanelLogin($id);
        $result = $mgr->getControlPanelDetails($id, $website_id);
        $this->assertFalse($result);
    }

    /**
     * Model_ControlPanel::validate
     */
    public function testValidateControlPanelLogin()
    {
        $mgr = new Model_ControlPanel();
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
     * Model_ControlPanel::validate
     */
    public function testControlPanelLoginFailedValidation()
    {
        $mgr = new Model_ControlPanel();
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
        $this->assertEquals(array('Website does not exist.'), $messages['website_id']);
        $this->assertEquals(array('Username must not be more than 100 characters.'), $messages['username']);
        $this->assertEquals(array('Password must not be more than 100 characters.'), $messages['password']);
        $this->assertEquals(array('URL must not be more than 255 characters.'), $messages['url']);
    }

}

