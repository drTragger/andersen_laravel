<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogInTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testLogIn()
    {
        $data = [
            'email' => 'email10@example.com',
            'password' => '1m2i3s4h5a',
        ];

        $response = $this->json('POST', '/api/login', $data)->assertStatus(202);
        $response->assertJsonStructure(['access_token'], $response->original);
    }
}
