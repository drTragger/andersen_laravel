<?php

namespace Tests\Feature;

use App\Models\User;
use App\services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class UpdateUserDataTest extends TestCase
{
    protected $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = app()->make(UserService::class);
    }

    /**
     * @test
     */
    public function testUpdateData()
    {
        Artisan::call('passport:install', ['-vvv' => true]);

        $user = User::factory()->make();
        $userData = $this->service->createUser($user->attributesToArray());

        $token = $userData->createToken('authToken')->accessToken;

        $data = User::factory()->make()->only('name', 'email');

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->json('PUT', '/api/users/' . $userData->id, $data)
            ->assertStatus(200);

        $response->assertJsonStructure(['name', 'email'], $data);
    }
}
