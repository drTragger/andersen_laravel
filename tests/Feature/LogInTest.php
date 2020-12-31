<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class LogInTest extends TestCase
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
    public function testLogIn()
    {
        Artisan::call('passport:install', ['-vvv' => true]);
        $user = User::factory()->make();
        $this->service->createUser($user->attributesToArray());

        $response = $this->json('POST', '/api/login', $user->only('email', 'password'))->assertStatus(202);
        $response->assertJsonStructure(['access_token'], $response->original);
    }
}
