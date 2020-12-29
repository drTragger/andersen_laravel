<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
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
    public function testGetToken()
    {
        Artisan::call('passport:install', ['-vvv' => true]);

        $user = User::factory()->make()->attributesToArray();

        $createdUser = $this->service->createUser($user);

        $response = $this->json('POST', '/api/password-reset', ['email' => $createdUser->email])->assertStatus(200);
        $response->assertJsonStructure(['message'], $response->original);
    }

    /**
     * @test
     */
    public function testSetNewPassword()
    {
        Artisan::call('passport:install', ['-vvv' => true]);

        $user = User::factory()->make()->attributesToArray();

        $createdUser = $this->service->createUser($user);

        $passwordResetData = $this->service->generatePasswordResetToken($createdUser->id);

        $newPassword = User::factory()->make()->only('password')['password'];

        $data = [
            'token' => $passwordResetData->token,
            'password' => $newPassword,
            'confirmPassword' => $newPassword,
        ];

        $response = $this->json('POST', '/api/new-password', $data)->assertStatus(200);
        $response->assertJsonStructure(['message'], $response->original);
    }
}
