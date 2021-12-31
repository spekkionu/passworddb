<?php

namespace Inertia;

use Tests\TestCase;
use App\Models\Site;
use Inertia\Testing\Assert;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SitesListTest extends TestCase
{
    use DatabaseMigrations;

    public function test_list_contains_sites()
    {
        $first = Site::factory()->create(['name' => 'first site']);
        $second = Site::factory()->create(['name' => 'second site']);

        $this->get('/')
             ->assertInertia(fn (Assert $page) => $page
                 ->component('Sites')
                 ->has('records', 2)
                 ->has('records.0', fn (Assert $page) => $page
                     ->where('id', $first->id)
                     ->where('name', $first->name)
                     ->where('domain', $first->domain)
                     ->where('url', $first->url)
                 )
                 ->has('records.1', fn (Assert $page) => $page
                     ->where('id', $second->id)
                     ->where('name', $second->name)
                     ->where('domain', $second->domain)
                     ->where('url', $second->url)
                 )
             );
    }
}
