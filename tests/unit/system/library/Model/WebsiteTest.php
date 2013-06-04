<?php

/**
 * Test class for Model_Website.
 *
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @license MIT http://opensource.org/licenses/MIT
 * @package Test
 * @subpackage Model
 */
class Model_WebsiteTest extends Test_DatabaseTest
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
   * Model_Website::countWebsites
   */
  public function testCountWebsites()
  {
    $mgr = new Model_Website();
    $result = $mgr->countWebsites();
    $this->assertEquals(5, $result, "There should be 5 websites");
  }

    /**
     * Model_Website::getWebsites
     */
    public function testGetWebsiteList()
    {
        $mgr = new Model_Website();
        $websites = $mgr->getWebsites(false);
        $this->assertEquals(5, count($websites), "There should be 5 websites");
    }

    /**
     * Model_Website::getWebsites
     */
    public function testGetWebsiteListWithDetails()
    {
        $mgr = new Model_Website();
        $websites = $mgr->getWebsites(true);
        $website = array_shift($websites);
        $this->assertArrayHasKey('admin', $website);
    }

    /**
     * Model_Website::getWebsite
     */
    public function testGetWebsite()
    {
        $mgr = new Model_Website();
        $id = 1;
        $website = $mgr->getWebsite($id, false);
        $this->assertEquals($id, $website['id']);
    }

    /**
     * Model_Website::websiteExists
     */
    public function testWebsiteExists()
    {
        $mgr = new Model_Website();
        $id = 1;
        $exists = $mgr->websiteExists($id);
        $this->assertTrue($exists);
    }

    /**
     * Model_Website::websiteExists
     */
    public function testWebsiteNotExists()
    {
        $mgr = new Model_Website();
        $id = 'pickle';
        $exists = $mgr->websiteExists($id);
        $this->assertFalse($exists);
    }

    /**
     * Model_Website::addWebsite
     */
    public function testAddWebsite()
    {
        $mgr = new Model_Website();
        $website = array(
          'name' => 'New Website',
          'domain' => 'domain.com',
          'url' => 'http://url.com'
        );
        $added = $mgr->addWebsite($website);
        $new = $mgr->getWebsite($added['id'], false);
        $this->assertEquals(array(
          'id' => $added['id'],
          'name' => $website['name'],
          'domain' => $website['domain'],
          'url' => $website['url']
          ), array(
          'id' => $new['id'],
          'name' => $new['name'],
          'domain' => $new['domain'],
          'url' => $new['url']
        ));
    }

    /**
     * Model_Website::addWebsite
     * @expectedException Validate_Exception
     */
    public function testAddWebsiteFail()
    {
        $mgr = new Model_Website();
        $website = array(
          'name' => null,
          'domain' => 'domain.com',
          'url' => 'http://url.com'
        );
        $mgr->addWebsite($website);
    }

    /**
     * Model_Website::updateWebsite
     */
    public function testUpdateWebsite()
    {
        $mgr = new Model_Website();
        $id = 1;
        $website = $mgr->getWebsite($id, false);
        $this->assertEquals($id, $website['id']);
        $website['name'] = 'New Name';
        $website['domain'] = 'newdomain.com';
        $mgr->updateWebsite($id, $website);
        $new = $mgr->getWebsite($id, false);
        $this->assertEquals(array(
          'id' => $id,
          'name' => $website['name'],
          'domain' => $website['domain'],
          'url' => $website['url']
          ), array(
          'id' => $new['id'],
          'name' => $new['name'],
          'domain' => $new['domain'],
          'url' => $new['url']
        ));
    }

    /**
     * Model_Website::updateWebsite
     * @expectedException Validate_Exception
     */
    public function testUpdateWebsiteFail()
    {
        $mgr = new Model_Website();
        $id = 1;
        $website = $mgr->getWebsite($id, false);
        $this->assertEquals($id, $website['id']);
        $website['name'] = null;
        $website['domain'] = 'newdomain.com';
        $mgr->updateWebsite($id, $website);
    }

    /**
     * Model_Website::deleteWebsite
     */
    public function testDeleteWebsite()
    {
        $mgr = new Model_Website();
        $id = 1;
        $website = $mgr->getWebsite($id, false);
        $this->assertEquals($website['id'], $id);
        $mgr->deleteWebsite($id);
        $website = $mgr->getWebsite($id, false);
        $this->assertFalse($website);
    }

    /**
     * Model_Website::validate
     */
    public function testValidateWebsite()
    {
        $mgr = new Model_Website();
        $data = array(
          'name' => 'website name',
          'domain' => 'domain.com',
          'url' => 'http://url.com',
          'notes' => null
        );
        $errors = $mgr->validate($data);
        $this->assertInstanceOf('Error_Stack', $errors);
        $this->assertFalse($errors->hasErrors());
    }

    /**
     * Model_Website::validate
     */
    public function testFailedWebsiteValidation()
    {
        $mgr = new Model_Website();
        $data = array(
          'name' => null,
          'domain' => 'domain.com',
          'url' => 'http://url.com',
          'notes' => null
        );
        $errors = $mgr->validate($data);
        $this->assertInstanceOf('Error_Stack', $errors);
        $this->assertTrue($errors->hasErrors());
        $messages = $errors->getErrors();
        $this->assertEquals(array('name' => array('required' => 'Website name is required.')), $messages);
    }

    /**
     * Model_Website::validate
     */
    public function testFailedWebsiteValidationLength()
    {
        $mgr = new Model_Website();
        $data = array(
          'name' => str_repeat('-', 101),
          'domain' => str_repeat('-', 101),
          'url' => str_repeat('-', 256),
          'notes' => null
        );
        $errors = $mgr->validate($data);
        $this->assertInstanceOf('Error_Stack', $errors);
        $this->assertTrue($errors->hasErrors());
        $messages = $errors->getErrors();
        $this->assertEquals(array('maxlength' => 'Website name must not be more than 100 characters.'), $messages['name']);
        $this->assertEquals(array('maxlength' => 'Website domain must not be more than 100 characters.'), $messages['domain']);
        $this->assertEquals(array('maxlength' => 'Website URL must not be more than 255 characters.'), $messages['url']);
    }

    /**
     * Model_Website::validate
     */
    public function testFailedWebsiteValidationDuplicate()
    {
        $mgr = new Model_Website();
        $data = array(
          'name' => 'First Website',
          'domain' => 'domain.com',
          'url' => 'http://url.com',
          'notes' => null
        );
        $errors = $mgr->validate($data);
        $this->assertInstanceOf('Error_Stack', $errors);
        $this->assertTrue($errors->hasErrors());
        $messages = $errors->getErrors();
        $this->assertEquals(array('name' => array('unique' => 'Website with name First Website already exists.')), $messages);
    }

}

