<?php

namespace Test\Acceptance;

use Guzzle\Http\Client;
use Symfony\Component\EventDispatcher\Event;

/**
 * Test class for Controller_Website.
 *
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @license MIT http://opensource.org/licenses/MIT
 * @package Test
 * @subpackage Controller
 */
class WebsiteTest extends \Test_DatabaseTest
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
     * Controller_Website::listAction
     */
    public function testListAction()
    {
        $url = $this->config['test']['hostname'] . $this->config['test']['base_url'];
        $this->session->visit($url);
        $this->assertEquals(200, $this->session->getStatusCode());
        $page = $this->session->getPage();
        $el = $page->find('css', '.page-header > h1');
        // Make sure the page has the right header
        $this->assertEquals('Websites', $el->getText());
        $el = $page->findAll('css', 'table > tbody > tr');
        // Make sure there are 5 websites
        $this->assertCount(5, $el);
        $first = array_shift($el);
        // Make sure the first website matches
        $this->assertEquals('Fifth Website', $first->find('css', 'td:nth-child(2)')->getText());
    }

    /**
     * Controller_Website::detailsAction
     */
    public function testDetailsAction()
    {
        $id = 1;
        $url = $this->config['test']['hostname'] . $this->config['test']['base_url'];
        $url .= "website/{$id}";
        $this->session->visit($url);
        $page = $this->session->getPage();
        $this->assertEquals('First Website', $page->find('css', '.page-header > h1')->getText());
    }

    /**
     * Controller_Website::addAction
     */
    public function testAddAction()
    {
        $result = array(
          'name' => 'New Website',
          'domain' => 'domain.com',
          'url' => 'http://url.com',
          'notes' => 'new website'
        );
        $url = $this->config['test']['hostname'] . $this->config['test']['base_url'];
        $url .= "website/add";
        $this->session->visit($url);
        $page = $this->session->getPage();
        $page->findById('form-website-name')->setValue($result['name']);
        $page->findById('form-website-domain')->setValue($result['domain']);
        $page->findById('form-website-url')->setValue($result['url']);
        $page->findById('form-website-notes')->setValue($result['notes']);
        $page->find('css', '.form-actions > .btn-primary')->press();
        $el = $page->find('css', '.flash-messages .alert');
        $this->assertEquals('alert alert-success', $el->getAttribute('class'));
        $this->assertContains("Website {$result['name']} added.", $el->getText());
    }

    /**
     * Controller_Website::updateAction
     */
    public function testUpdateAction()
    {
        $id = 1;
        $result = array(
          'name' => 'New Website',
          'domain' => 'domain.com',
          'url' => 'http://url.com',
          'notes' => 'new website'
        );
        $url = $this->config['test']['hostname'] . $this->config['test']['base_url'];
        $url .= "website/{$id}/edit";
        $this->session->visit($url);
        $page = $this->session->getPage();
        $page->findById('form-website-name')->setValue($result['name']);
        $page->findById('form-website-domain')->setValue($result['domain']);
        $page->findById('form-website-url')->setValue($result['url']);
        $page->findById('form-website-notes')->setValue($result['notes']);
        $page->find('css', '.form-actions > .btn-primary')->press();
        $el = $page->find('css', '.flash-messages .alert');
        $this->assertEquals('alert alert-success', $el->getAttribute('class'));
        $this->assertContains("Website {$result['name']} updated.", $el->getText());
    }

    /**
     * Controller_Website::deleteAction
     */
    public function testDeleteAction()
    {
        $id = 1;
        $url = $this->config['test']['hostname'] . $this->config['test']['base_url'];
        $url .= "website/{$id}/delete";
        $this->session->visit($url);
        $page = $this->session->getPage();
        $page->find('css', '.form-actions > .btn-danger')->press();
        $el = $page->find('css', '.flash-messages .alert');
        $this->assertEquals('alert alert-info', $el->getAttribute('class'));
        $this->assertContains("Website deleted.", $el->getText());
    }
}
