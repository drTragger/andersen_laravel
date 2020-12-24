<?php

namespace Tests\Feature;

use App\Models\User;
use App\services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;
use Tests\TestCase;

class GetUsersTest extends TestCase
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
    public function testGetUsers()
    {
        Artisan::call('passport:install', ['-vvv' => true]);

        $user = User::factory()->make()->attributesToArray();
        $this->service->createUser($user);

        $response = $this->get('/api/users')->assertStatus(200);
        $response->assertJsonStructure(['users'], $response->original);
    }

    public function testGetUser()
    {
        Artisan::call('passport:install', ['-vvv' => true]);

        $user = User::factory()->make();
        $userData = $this->service->createUser($user->attributesToArray());

        $userData->createToken('authToken')->accessToken;

        Passport::actingAs($userData);

        $response = $this->get('/api/users/' . $userData->id)->assertStatus(200);
        $response->assertJsonStructure(['user'], $response->original);
    }
}
