<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class WebsiteTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->runDatabaseMigrations();
    }

    /**
     * Test pulling a list of websites
     */
    public function testListWebsites()
    {
        $record = factory('App\Models\Website')->create();

        $response = $this->get("/api/website");
        $response->seeJson($record->toArray());
    }

    /**
     * Test counting the total number of websites
     * Without any existing websites
     */
    public function testCountWebsites()
    {
        $response = $this->get("/api/website/count");
        $response->seeJson(['count' => 0]);
    }

    /**
     * Test countung the total number of websites
     * With existing websites
     */
    public function testCountWebsitesWhenEmpty()
    {
        factory('App\Models\Website', 3)->create();

        $response = $this->get("/api/website/count");
        $response->seeJson(['count' => 3]);
    }

    /**
     * Test pulling a single website
     */
    public function testShowWebsite()
    {
        $record = factory('App\Models\Website')->create();

        $response = $this->get("/api/website/{$record->id}");
        $response->seeJson($record->toArray());
    }

    /**
     * Test trying to pull a website that doesn't exist
     */
    public function testShowWebsiteMissing()
    {
        $response = $this->get("/api/website/4056");
        $response->seeStatusCode(404);
    }

    /**
     * Test creating a new website
     */
    public function testCreateWebsite()
    {
        $record = factory('App\Models\Website')->make();
        $this->post("/api/website", $record->toArray());

        $this->seeInDatabase('websites', $record->toArray());
    }

    public function testTryingToCreateWebsiteWithoutName()
    {
        $record = factory('App\Models\Website')->make(['name' => null]);
        $request = $this->call('POST', "/api/website", $record->toArray());
        $this->seeStatusCode(422);
        $errors = json_decode($request->content(), true);
        $this->assertArrayHasKey('name', $errors);

    }

    /**
     * Test updating a website
     */
    public function testUpdateWebsite()
    {
        $record = factory('App\Models\Website')->create(['name' => 'Site Name']);
        $record = $record->toArray();
        $record['name'] = 'New Site Name';

        $this->put("/api/website/{$record['id']}", $record);
        $this->seeInDatabase('websites', $record);

    }

    /**
     * Test trying to update a website that does not exist
     */
    public function testUpdateWebsiteMissing()
    {
        $response = $this->put("/api/website/4056");
        $response->seeStatusCode(404);
    }

    /**
     * Test deleting a website
     */
    public function testDeleteWebsite()
    {
        $record = factory('App\Models\Website')->make();
        $this->delete("/api/website/{$record->id}");

        $this->notSeeInDatabase('websites', ['id' => $record->id]);
    }

    /**
     * Test trying to delete a website that does not exist
     */
    public function testDeleteWebsiteMissing()
    {
        $response = $this->delete("/api/website/4056");
        $response->seeStatusCode(404);
    }


}
