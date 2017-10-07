<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MenuWebTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_can_see_menu_management_page()
    {
        $response = $this->get('/menu');

        $response->assertStatus(200);
    }
}
