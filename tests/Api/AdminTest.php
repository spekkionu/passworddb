<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(){
        parent::setUp();
        $this->runDatabaseMigrations();
    }

    /**
     * Test pulling a list of admin logins for a website
     */
    public function testListAdminLogins()
    {
        $website = factory('App\Models\Website')->create();
        $record = factory('App\Models\Admin')->create(['website_id' => $website->id]);
        $record2 = factory('App\Models\Admin')->create(['website_id' => $website->id]);

        $response = $this->get("/api/admin/{$website->id}");
        $response->seeJson($record->toArray());
        $response->seeJson($record2->toArray());
    }

    /**
     * Test pulling a single admin login for a website
     */
    public function testShowAdminLogin(){
        $website = factory('App\Models\Website')->create();
        $record = factory('App\Models\Admin')->create(['website_id' => $website->id]);

        $response = $this->get("/api/admin/{$website->id}/{$record->id}");
        $response->seeJson($record->toArray());
    }

    /**
     * Test trying to pull a single admin login that does not exist
     */
    public function testShowMissingAdminLogin(){
        $website = factory('App\Models\Website')->create();

        $response = $this->get("/api/admin/{$website->id}/404");
        $response->seeStatusCode(404);
    }

    /**
     * Test trying to pull an admin login without a website
     */
    public function testShowWithoutWebsite()
    {
        $website = factory('App\Models\Website')->create();
        $record = factory('App\Models\Admin')->create(['website_id' => $website->id]);

        $website_id = $website->id + 5;
        $response = $this->get("/api/admin/{$website_id}/{$record->id}");
        $response->seeStatusCode(404);
    }

    /**
     * Test trying to pull an admin login when the website does not match
     */
    public function testShowWithInvalidWebsite()
    {
        $website = factory('App\Models\Website')->create();
        $website2 = factory('App\Models\Website')->create();
        $record = factory('App\Models\Admin')->create(['website_id' => $website->id]);

        $response = $this->get("/api/admin/{$website2->id}/{$record->id}");
        $response->seeStatusCode(404);
    }

    /**
     * Test creating a new admin login
     */
    public function testCreateAdminLogin()
    {
        $website = factory('App\Models\Website')->create();
        $record = factory('App\Models\Admin')->make();
        $response = $this->post("/api/admin/{$website->id}", $record->toArray());
        $response->seeStatusCode(201);
        $this->seeInDatabase('admin_logins', $record->toArray());
    }

    /**
     * Test trying to create an admin login with an invalid website
     */
    public function testCreateAdminLoginWithoutWebsite()
    {
        $record = factory('App\Models\Admin')->make();
        $response = $this->post("/api/admin/546", $record->toArray());

        $response->seeStatusCode(404);
    }

    /**
     * Test updating an existing admin login
     */
    public function testUpdateAdminLogin()
    {
        $website = factory('App\Models\Website')->create();
        $record = factory('App\Models\Admin')->create(['username' => 'oldname', 'website_id' => $website->id]);
        $record = $record->toArray();
        $record['username'] = 'newname';

        $this->put("/api/admin/{$website->id}/{$record['id']}", $record);
        $this->seeInDatabase('admin_logins', $record);
    }

    /**
     * Test trying to update a single admin login that does not exist
     */
    public function testUpdateAdminLoginMissing()
    {
        $website = factory('App\Models\Website')->create();
        $response = $this->put("/api/admin/{$website->id}/45345");
        $response->seeStatusCode(404);
    }

    /**
     * Test deleting a single admin login
     */
    public function testDeleteAdminLogin()
    {
        $website = factory('App\Models\Website')->create();
        $record = factory('App\Models\Admin')->create(['website_id' => $website->id]);

        $response = $this->delete("/api/admin/{$website->id}/{$record->id}");
        $response->seeStatusCode(204);
        $this->notSeeInDatabase('admin_logins', ['id' => $record->id]);
    }

    /**
     * Test trying to delete a single admin login that does not exist
     */
    public function testDeleteAdminLoginMissing()
    {
        $website = factory('App\Models\Website')->create();

        $response = $this->delete("/api/admin/{$website->id}/545");
        $response->seeStatusCode(404);
    }
}
