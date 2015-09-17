<?php

class ApiTest extends TestCase {

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBaseEndpoint()
    {
        $response = $this->get('/api');
        $response->seeJson(['/api/website']);
    }

}
