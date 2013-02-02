<?php

namespace Test\Acceptance\Api;

use Guzzle\Http\Client;
use Symfony\Component\EventDispatcher\Event;

/**
 * Test class for Controller_Error.
 *
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @license MIT http://opensource.org/licenses/MIT
 * @package Test
 * @subpackage Controller
 */
class ErrorTest extends \Test_DatabaseTest
{

    /**
     * Guzzle client
     * @var Guzzle\Http\Client $client
     */
    protected $client;

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
        $url = $config['test']['hostname'] . $config['test']['api_url'];
        $this->client = new Client($url);
        $this->client->getEventDispatcher()->addListener('request.before_send', function(Event $event) {
              $event['request']->addHeader('X-SERVER-MODE', 'test')->setAuth('admin', 'password');
          });
        $this->config = $config;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        \Model_Abstract::close();
        parent::tearDown();
    }

    /**
     * Test 404 Errors
     * @expectedException Guzzle\Http\Exception\ClientErrorResponseException
     */
    public function testNotfoundAction()
    {
        try {
            $request = $this->client->get('api/bad-request');
            $request->send();
        } catch (Guzzle\Http\Exception\ClientErrorResponseException $e) {
            $response = $e->getResponse();
            $this->assertEquals(404, $response->getStatusCode());
            throw $e;
        }
    }

}
