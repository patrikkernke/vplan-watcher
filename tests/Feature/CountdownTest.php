<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CountdownTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_access_page()
    {
        $response = $this->get('/countdown');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_informs_about_no_events()
    {
        $response = $this->get('/countdown');

        $response->assertSee('Keine Zusammenkunft');
    }
}
