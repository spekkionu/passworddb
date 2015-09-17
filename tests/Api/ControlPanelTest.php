<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class ControlPanelTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(){
        parent::setUp();
        $this->runDatabaseMigrations();
    }

    /**
     * Test pulling a list of control panel logins for a website
     */
    public function testListControlPanelLogins()
    {
        $website = factory('App\Models\Website')->create();
        $record = factory('App\Models\ControlPanel')->create(['website_id' => $website->id]);
        $record2 = factory('App\Models\ControlPanel')->create(['website_id' => $website->id]);

        $response = $this->get("/api/controlpanel/{$website->id}");
        $response->seeJson($record->toArray());
        $response->seeJson($record2->toArray());
    }
    /**
     * Test pulling a single control panel login for a website
     */
    public function testShowControlPanelLogin(){
        $website = factory('App\Models\Website')->create();
        $record = factory('App\Models\ControlPanel')->create(['website_id' => $website->id]);

        $response = $this->get("/api/controlpanel/{$website->id}/{$record->id}");
        $response->seeJson($record->toArray());
    }

    /**
     * Test trying to pull a single control panel login that does not exist
     */
    public function testShowMissingControlPanelLogin(){
        $website = factory('App\Models\Website')->create();

        $response = $this->get("/api/controlpanel/{$website->id}/404");
        $response->seeStatusCode(404);
    }

    /**
     * Test trying to pull an control panel login without a website
     */
    public function testShowWithoutWebsite()
    {
        $website = factory('App\Models\Website')->create();
        $record = factory('App\Models\ControlPanel')->create(['website_id' => $website->id]);

        $website_id = $website->id + 5;
        $response = $this->get("/api/controlpanel/{$website_id}/{$record->id}");
        $response->seeStatusCode(404);
    }

    /**
     * Test trying to pull an control panel login when the website does not match
     */
    public function testShowWithInvalidWebsite()
    {
        $website = factory('App\Models\Website')->create();
        $website2 = factory('App\Models\Website')->create();
        $record = factory('App\Models\ControlPanel')->create(['website_id' => $website->id]);

        $response = $this->get("/api/controlpanel/{$website2->id}/{$record->id}");
        $response->seeStatusCode(404);
    }

    /**
     * Test creating a new control panel login
     */
    public function testCreateControlPanelLogin()
    {
        $website = factory('App\Models\Website')->create();
        $record = factory('App\Models\ControlPanel')->make();
        $response = $this->post("/api/controlpanel/{$website->id}", $record->toArray());
        $response->seeStatusCode(201);
        $this->seeInDatabase('control_panels', $record->toArray());
    }

    /**
     * Test trying to create an control panel login with an invalid website
     */
    public function testCreateControlPanelLoginWithoutWebsite()
    {
        $record = factory('App\Models\ControlPanel')->make();
        $response = $this->post("/api/controlpanel/546", $record->toArray());

        $response->seeStatusCode(404);
    }

    /**
     * Test updating an existing control panel login
     */
    public function testUpdateControlPanelLogin()
    {
        $website = factory('App\Models\Website')->create();
        $record = factory('App\Models\ControlPanel')->create(['username' => 'oldname', 'website_id' => $website->id]);
        $record = $record->toArray();
        $record['username'] = 'newname';

        $this->put("/api/controlpanel/{$website->id}/{$record['id']}", $record);
        $this->seeInDatabase('control_panels', $record);
    }

    /**
     * Test trying to update a single control panel login that does not exist
     */
    public function testUpdateControlPanelLoginMissing()
    {
        $website = factory('App\Models\Website')->create();
        $response = $this->put("/api/controlpanel/{$website->id}/45345");
        $response->seeStatusCode(404);
    }

    /**
     * Test deleting a single control panel login
     */
    public function testDeleteControlPanelLogin()
    {
        $website = factory('App\Models\Website')->create();
        $record = factory('App\Models\ControlPanel')->create(['website_id' => $website->id]);

        $response = $this->delete("/api/controlpanel/{$website->id}/{$record->id}");
        $response->seeStatusCode(204);
        $this->notSeeInDatabase('control_panels', ['id' => $record->id]);
    }

    /**
     * Test trying to delete a single control panel login that does not exist
     */
    public function testDeleteControlPanelLoginMissing()
    {
        $website = factory('App\Models\Website')->create();

        $response = $this->delete("/api/controlpanel/{$website->id}/545");
        $response->seeStatusCode(404);
    }

}
