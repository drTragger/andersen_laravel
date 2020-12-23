<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /**
     * @test
     */
    public function testRegister()
    {
        Artisan::call('passport:install', ['-vvv' => true]);
        $data = User::factory()->make()->attributesToArray();
        $data['confirmPassword'] = $data['password'];

        $response = $this->json('POST', '/api/users', $data)->assertStatus(201);
        $response->assertJsonStructure(['access_token'], $response->original);
    }
}
