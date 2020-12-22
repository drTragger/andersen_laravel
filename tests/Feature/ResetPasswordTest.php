<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetToken()
    {
        $data = [
            'email' => 'email@example.com',
        ];

        $response = $this->json('POST', '/api/password-reset', $data)->assertStatus(200);
        $response->assertJsonStructure(['message'], $response->original);
    }

    public function testSetNewPassword()
    {
        $data = [
            'token' => 'rfUq071qsK',
            'password' => 'qwerty',
            'confirmPassword' => 'qwerty',
        ];

        $response = $this->json('POST', '/api/new-password', $data)->assertStatus(200);
        $response->assertJsonStructure(['message'], $response->original);
    }
}
