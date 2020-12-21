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
    public function testExample()
    {
        $data = [
            'token' => 'NHU4Ej9OOK',
            'password' => 'qwerty',
            'confirmPassword' => 'qwerty',
        ];

        $response = $this->json('POST', '/api/new-password', $data)->assertStatus(200);
    }
}
