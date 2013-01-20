<?php

/**
 * Test class for Controller_Website.
 *
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @license MIT http://opensource.org/licenses/MIT
 * @package Test
 * @subpackage Controller
 */
class Controller_WebsiteTest extends Test_DatabaseTest
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
        Model_Abstract::setConnection($this->dbh);
        $url = $config['test']['hostname'] . $config['test']['base_url'];
        $this->client = new Guzzle\Http\Client($url);
        $this->client->getEventDispatcher()->addListener('request.before_send', function(Symfony\Component\EventDispatcher\Event $event) {
              $event['request']->addHeader('X-SERVER-MODE', 'test');
          });
        $this->config = $config;
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
     * Controller_Website::listAction
     */
    public function testListAction()
    {
        $request = $this->client->get('api/website');
        $response = $request->send();
        $this->assertEquals('application/json', $response->getContentType());
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getBody(true), true);
        $this->assertTrue($body['success']);
        $this->assertCount(5, $body['records']);
    }

    /**
     * Controller_Website::detailsAction
     */
    public function testDetailsAction()
    {
        $id = 1;
        $request = $this->client->get('api/website/' . $id);
        $response = $request->send();
        $this->assertEquals('application/json', $response->getContentType());
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getBody(true), true);
        $this->assertTrue($body['success']);
        $this->assertEquals($id, $body['record']['id']);
    }

    /**
     * Controller_Website::addAction
     */
    public function testAddAction()
    {
        $website = array(
          'name' => 'New Website',
          'domain' => 'domain.com',
          'url' => 'http://url.com',
          'notes' => 'new website'
        );
        $request = $this->client->post('api/website')->addPostFields($website);
        $response = $request->send();
        $this->assertEquals('application/json', $response->getContentType());
        $this->assertEquals(201, $response->getStatusCode());
        $body = json_decode($response->getBody(true), true);
        $this->assertTrue($body['success']);
        $this->assertEquals($website, array(
          'name' => $body['record']['name'],
          'domain' => $body['record']['domain'],
          'url' => $body['record']['url'],
          'notes' => $body['record']['notes']
        ));
    }

    /**
     * Controller_Website::updateAction
     */
    public function testUpdateAction()
    {
        $id = 1;
        $website = array(
          'name' => 'New Website',
          'domain' => 'domain.com',
          'url' => 'http://url.com',
          'notes' => 'new website'
        );
        $request = $this->client->post('api/website/' . $id)->addPostFields($website);
        $request->addHeader('X-HTTP-Method-Override', 'PUT');
        $response = $request->send();
        $this->assertEquals('application/json', $response->getContentType());
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getBody(true), true);
        $this->assertTrue($body['success']);
        $this->assertEquals($website, array(
          'name' => $body['record']['name'],
          'domain' => $body['record']['domain'],
          'url' => $body['record']['url'],
          'notes' => $body['record']['notes']
        ));
    }

    /**
     * Controller_Website::deleteAction
     */
    public function testDeleteAction()
    {
        $id = 1;
        $request = $this->client->post('api/website/' . $id);
        $request->addHeader('X-HTTP-Method-Override', 'DELETE');
        $response = $request->send();
        $this->assertEquals(204, $response->getStatusCode());
    }

}

