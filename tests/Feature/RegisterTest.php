<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRegister()
    {
        $data = [
            'name' => 'Jack',
            'email' => 'mymail8@email.com',
            'password' => 'qwerty',
            'confirmPassword' => 'qwerty',
        ];

        $response = $this->json('POST', '/api/users', $data)->assertStatus(201);
        $response->assertJsonStructure(['access_token'], $response->original);
    }
}
