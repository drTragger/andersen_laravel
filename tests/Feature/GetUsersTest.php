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

        $user = User::factory()->create();

        Passport::actingAs($user);

        for ($i = 0; $i < 10; $i++) {
            User::factory()->create();
        }

        $response = $this->get('/api/users')->assertStatus(200);
        $response->assertJsonStructure(['users'], $response->original);
        $this->assertCount(11, $response->original['users']);
    }

    public function testGetUser()
    {
        Artisan::call('passport:install', ['-vvv' => true]);

        $user = User::factory()->create();

        Passport::actingAs($user);

        $response = $this->get('/api/users/' . $user->id)->assertStatus(200);
        $response->assertJsonStructure(['user'], $response->original);
    }
}
