<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class DatabaseTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(){
        parent::setUp();
        $this->runDatabaseMigrations();
    }

    /**
     * Test pulling a list of database logins for a website
     */
    public function testListDatabaseLogins()
    {
        $website = factory('App\Models\Website')->create();
        $record = factory('App\Models\Database')->create(['website_id' => $website->id]);
        $record2 = factory('App\Models\Database')->create(['website_id' => $website->id]);

        $response = $this->get("/api/database/{$website->id}");
        $response->seeJson($record->toArray());
        $response->seeJson($record2->toArray());
    }
    /**
     * Test pulling a single database login for a website
     */
    public function testShowDatabaseLogin(){
        $website = factory('App\Models\Website')->create();
        $record = factory('App\Models\Database')->create(['website_id' => $website->id]);

        $response = $this->get("/api/database/{$website->id}/{$record->id}");
        $response->seeJson($record->toArray());
    }

    /**
     * Test trying to pull a single database login that does not exist
     */
    public function testShowMissingDatabaseLogin(){
        $website = factory('App\Models\Website')->create();

        $response = $this->get("/api/database/{$website->id}/404");
        $response->seeStatusCode(404);
    }

    /**
     * Test trying to pull an database login without a website
     */
    public function testShowWithoutWebsite()
    {
        $website = factory('App\Models\Website')->create();
        $record = factory('App\Models\Database')->create(['website_id' => $website->id]);

        $website_id = $website->id + 5;
        $response = $this->get("/api/database/{$website_id}/{$record->id}");
        $response->seeStatusCode(404);
    }

    /**
     * Test trying to pull an database login when the website does not match
     */
    public function testShowWithInvalidWebsite()
    {
        $website = factory('App\Models\Website')->create();
        $website2 = factory('App\Models\Website')->create();
        $record = factory('App\Models\Database')->create(['website_id' => $website->id]);

        $response = $this->get("/api/database/{$website2->id}/{$record->id}");
        $response->seeStatusCode(404);
    }

    /**
     * Test creating a new database login
     */
    public function testCreateDatabaseLogin()
    {
        $website = factory('App\Models\Website')->create();
        $record = factory('App\Models\Database')->make();
        $response = $this->post("/api/database/{$website->id}", $record->toArray());
        $response->seeStatusCode(201);
        $this->seeInDatabase('database_data', $record->toArray());
    }

    /**
     * Test trying to create an database login with an invalid website
     */
    public function testCreateDatabaseLoginWithoutWebsite()
    {
        $record = factory('App\Models\Database')->make();
        $response = $this->post("/api/database/546", $record->toArray());

        $response->seeStatusCode(404);
    }

    /**
     * Test updating an existing database login
     */
    public function testUpdateDatabaseLogin()
    {
        $website = factory('App\Models\Website')->create();
        $record = factory('App\Models\Database')->create(['username' => 'oldname', 'website_id' => $website->id]);
        $record = $record->toArray();
        $record['username'] = 'newname';

        $this->put("/api/database/{$website->id}/{$record['id']}", $record);
        $this->seeInDatabase('database_data', $record);
    }

    /**
     * Test trying to update a single database login that does not exist
     */
    public function testUpdateDatabaseLoginMissing()
    {
        $website = factory('App\Models\Website')->create();
        $response = $this->put("/api/database/{$website->id}/45345");
        $response->seeStatusCode(404);
    }

    /**
     * Test deleting a single database login
     */
    public function testDeleteDatabaseLogin()
    {
        $website = factory('App\Models\Website')->create();
        $record = factory('App\Models\Database')->create(['website_id' => $website->id]);

        $response = $this->delete("/api/database/{$website->id}/{$record->id}");
        $response->seeStatusCode(204);
        $this->notSeeInDatabase('database_data', ['id' => $record->id]);
    }

    /**
     * Test trying to delete a single database login that does not exist
     */
    public function testDeleteDatabaseLoginMissing()
    {
        $website = factory('App\Models\Website')->create();

        $response = $this->delete("/api/database/{$website->id}/545");
        $response->seeStatusCode(404);
    }

}
