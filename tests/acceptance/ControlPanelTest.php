<?php

namespace Test\Acceptance;

use Guzzle\Http\Client;
use Symfony\Component\EventDispatcher\Event;

/**
 * Test class for Controller_ControlPanel.
 *
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @license MIT http://opensource.org/licenses/MIT
 * @package Test
 * @subpackage Controller
 */
class ControlPanelTest extends \Test_DatabaseTest
{

    /**
     * Guzzle client
     * @var Guzzle\Http\Client $client
     */
    protected $client;

    /**
     * Mink Session
     * @var \Behat\Mink\Session $session
     */
    protected $session;

    /**
     * Site config
     * @var array $config
     */
    protected $config;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        global $config;
        parent::setUp();
        // Register the connection
        \Model_Abstract::setConnection($this->dbh);
        $url = $config['test']['hostname'] . $config['test']['base_url'];
        $this->client = new \Guzzle\Http\Client($url);
        $this->client->getEventDispatcher()->addListener('request.before_send', function(Event $event) {
              $event['request']->addHeader('X-SERVER-MODE', 'test')->setAuth('admin', 'password');
          });
        $client = new \Behat\Mink\Driver\Goutte\Client();
        $client->setClient($this->client);
        $driver = new \Behat\Mink\Driver\GoutteDriver($client);
        $this->session = new \Behat\Mink\Session($driver);
        $this->session->start();
        $this->config = $config;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        \Model_Abstract::close();
        $this->session->restart();
        parent::tearDown();
    }

    /**
     * Controller_ControlPanel::listAction
     */
    public function testListAction()
    {
        $website_id = 1;
        $url = $this->config['test']['hostname'] . $this->config['test']['base_url'] . "website/{$website_id}";
        $this->session->visit($url);
        $page = $this->session->getPage();
        $el = $page->findAll('css', '#website-controlpanel > li');
        $this->assertCount(2, $el);
    }

    /**
     * Controller_ControlPanel::detailsAction
     */
    public function testDetailsAction()
    {
        $id = 1;
        $website_id = 1;
        $url = $this->config['test']['hostname'] . $this->config['test']['base_url'];
        $url .= "controlpanel/{$website_id}/{$id}/edit";
        $this->session->visit($url);
        $page = $this->session->getPage();
        $this->assertEquals('username', $page->findById('form-controlpanel-username')->getValue());
        $this->assertEquals('password', $page->findById('form-controlpanel-password')->getValue());
    }

    /**
     * Controller_ControlPanel::addAction
     */
    public function testAddAction()
    {
        $website_id = 1;
        $result = array(
          'username' => 'pickle',
          'password' => 'password',
          'url' => 'http://url.com'
        );
        $url = $this->config['test']['hostname'] . $this->config['test']['base_url'];
        $url .= "controlpanel/{$website_id}/add";
        $this->session->visit($url);
        $page = $this->session->getPage();
        $page->findById('form-controlpanel-username')->setValue($result['username']);
        $page->findById('form-controlpanel-password')->setValue($result['password']);
        $page->findById('form-controlpanel-url')->setValue($result['url']);
        $page->find('css', '.form-actions > .btn-primary')->press();
        $el = $page->find('css', '.flash-messages .alert');
        $this->assertEquals('alert alert-success', $el->getAttribute('class'));
        $this->assertContains('Control panel 3 added for website First Website.', $el->getText());
    }

    /**
     * Controller_ControlPanel::updateAction
     */
    public function testUpdateAction()
    {
        $id = 1;
        $website_id = 1;
        $result = array(
          'username' => 'pickle',
          'password' => 'password',
          'url' => 'http://url.com'
        );
        $url = $this->config['test']['hostname'] . $this->config['test']['base_url'];
        $url .= "controlpanel/{$website_id}/{$id}/edit";
        $this->session->visit($url);
        $page = $this->session->getPage();
        $page->findById('form-controlpanel-username')->setValue($result['username']);
        $page->findById('form-controlpanel-username')->setValue($result['password']);
        $page->findById('form-controlpanel-url')->setValue($result['url']);
        $page->find('css', '.form-actions > .btn-primary')->press();
        $el = $page->find('css', '.flash-messages .alert');
        $this->assertEquals('alert alert-success', $el->getAttribute('class'));
        $this->assertContains("Control panel credentials {$id} have been updated for website First Website.", $el->getText());
    }

    /**
     * Controller_ControlPanel::deleteAction
     */
    public function testDeleteAction()
    {
        $id = 1;
        $website_id = 1;
        $url = $this->config['test']['hostname'] . $this->config['test']['base_url'];
        $url .= "controlpanel/{$website_id}/{$id}/delete";
        $this->session->visit($url);
        $page = $this->session->getPage();
        $page->find('css', '.form-actions > .btn-danger')->press();
        $el = $page->find('css', '.flash-messages .alert');
        $this->assertEquals('alert alert-info', $el->getAttribute('class'));
        $this->assertContains("Control panel {$id} deleted from website First Website.", $el->getText());
    }

}


