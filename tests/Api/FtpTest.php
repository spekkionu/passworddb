<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class FtpTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(){
        parent::setUp();
        $this->runDatabaseMigrations();
    }

    /**
     * Test pulling a list of ftp logins for a website
     */
    public function testListFtpLogins()
    {
        $website = factory('App\Models\Website')->create();
        $record = factory('App\Models\FTP')->create(['website_id' => $website->id]);
        $record2 = factory('App\Models\FTP')->create(['website_id' => $website->id]);

        $response = $this->get("/api/ftp/{$website->id}");
        $response->seeJson($record->toArray());
        $response->seeJson($record2->toArray());
    }

    /**
     * Test pulling a single ftp login for a website
     */
    public function testShowFtpLogin(){
        $website = factory('App\Models\Website')->create();
        $record = factory('App\Models\FTP')->create(['website_id' => $website->id]);

        $response = $this->get("/api/ftp/{$website->id}/{$record->id}");
        $response->seeJson($record->toArray());
    }

    /**
     * Test trying to pull a single ftp login that does not exist
     */
    public function testShowMissingFtpLogin(){
        $website = factory('App\Models\Website')->create();

        $response = $this->get("/api/ftp/{$website->id}/404");
        $response->seeStatusCode(404);
    }

    /**
     * Test trying to pull an ftp login without a website
     */
    public function testShowWithoutWebsite()
    {
        $website = factory('App\Models\Website')->create();
        $record = factory('App\Models\FTP')->create(['website_id' => $website->id]);

        $website_id = $website->id + 5;
        $response = $this->get("/api/ftp/{$website_id}/{$record->id}");
        $response->seeStatusCode(404);
    }

    /**
     * Test trying to pull an ftp login when the website does not match
     */
    public function testShowWithInvalidWebsite()
    {
        $website = factory('App\Models\Website')->create();
        $website2 = factory('App\Models\Website')->create();
        $record = factory('App\Models\FTP')->create(['website_id' => $website->id]);

        $response = $this->get("/api/ftp/{$website2->id}/{$record->id}");
        $response->seeStatusCode(404);
    }

    /**
     * Test creating a new ftp login
     */
    public function testCreateFtpLogin()
    {
        $website = factory('App\Models\Website')->create();
        $record = factory('App\Models\FTP')->make();
        $response = $this->post("/api/ftp/{$website->id}", $record->toArray());
        $response->seeStatusCode(201);
        $this->seeInDatabase('ftp_data', $record->toArray());
    }

    /**
     * Test trying to create an ftp login with an invalid website
     */
    public function testCreateFtpLoginWithoutWebsite()
    {
        $record = factory('App\Models\FTP')->make();
        $response = $this->post("/api/ftp/546", $record->toArray());

        $response->seeStatusCode(404);
    }

    /**
     * Test updating an existing ftp login
     */
    public function testUpdateFtpLogin()
    {
        $website = factory('App\Models\Website')->create();
        $record = factory('App\Models\FTP')->create(['username' => 'oldname', 'website_id' => $website->id]);
        $record = $record->toArray();
        $record['username'] = 'newname';

        $this->put("/api/ftp/{$website->id}/{$record['id']}", $record);
        $this->seeInDatabase('ftp_data', $record);
    }

    /**
     * Test trying to update a single ftp login that does not exist
     */
    public function testUpdateFtpLoginMissing()
    {
        $website = factory('App\Models\Website')->create();
        $response = $this->put("/api/ftp/{$website->id}/45345");
        $response->seeStatusCode(404);
    }

    /**
     * Test deleting a single ftp login
     */
    public function testDeleteFtpLogin()
    {
        $website = factory('App\Models\Website')->create();
        $record = factory('App\Models\FTP')->create(['website_id' => $website->id]);

        $response = $this->delete("/api/ftp/{$website->id}/{$record->id}");
        $response->seeStatusCode(204);
        $this->notSeeInDatabase('ftp_data', ['id' => $record->id]);
    }

    /**
     * Test trying to delete a single ftp login that does not exist
     */
    public function testDeleteFtpLoginMissing()
    {
        $website = factory('App\Models\Website')->create();

        $response = $this->delete("/api/ftp/{$website->id}/545");
        $response->seeStatusCode(404);
    }

}
