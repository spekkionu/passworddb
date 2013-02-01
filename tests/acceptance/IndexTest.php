<?php

namespace Test\Acceptance;

use Symfony\Component\EventDispatcher\Event;

/**
 * Test class for Controller_Index.
 *
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @license MIT http://opensource.org/licenses/MIT
 * @package Test
 * @subpackage Controller
 */
class IndexTest extends \Test_DatabaseTest
{

    /**
     * Guzzle client
     * @var \Guzzle\Http\Client $client
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
     * Test HTTP Authentication
     */
    public function testAuth()
    {
        $url = $this->config['test']['hostname'] . $this->config['test']['base_url'];
        $client = new \Guzzle\Http\Client($url);
        $request = $client->get('/');
        $request->addHeader('X-SERVER-MODE', 'test');
        $request->setAuth('admin', 'password');
        $response = $request->send();
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Test request with missing authentication
     * @expectedException \Guzzle\Http\Exception\ClientErrorResponseException
     */
    public function testAuthMissing()
    {
        try {
            $url = $this->config['test']['hostname'] . $this->config['test']['base_url'];
            $client = new \Guzzle\Http\Client($url);
            $request = $client->get('/');
            $request->addHeader('X-SERVER-MODE', 'test');
            $request->setAuth('bad_username', 'bad_password');
            $response = $request->send();
        } catch (\Guzzle\Http\Exception\BadResponseException $e) {
            $response = $e->getResponse();
            $this->assertEquals(401, $response->getStatusCode());
            throw $e;
        }
    }

    /**
     * Test request with invalid login credentials
     * @expectedException \Guzzle\Http\Exception\ClientErrorResponseException
     */
    public function testAuthFail()
    {
        try {
            $url = $this->config['test']['hostname'] . $this->config['test']['base_url'];
            $client = new \Guzzle\Http\Client($url);
            $request = $client->get('/');
            $request->addHeader('X-SERVER-MODE', 'test');
            $request->setAuth('bad_username', 'bad_password');
            $response = $request->send();
        } catch (\Guzzle\Http\Exception\BadResponseException $e) {
            $response = $e->getResponse();
            $this->assertEquals(401, $response->getStatusCode());
            throw $e;
        }
    }
}
