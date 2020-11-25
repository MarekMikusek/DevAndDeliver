<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function registerUserTest()
    {
        $response = $this->get('/test');

        $response->assertStatus(200);
    }
}
