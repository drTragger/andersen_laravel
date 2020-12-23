<?php

namespace Tests\Unit;

use App\Models\ResetPassword;
use App\Models\User;
use App\services\UserService;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\TestCase;

class PasswordResetTest extends \Tests\TestCase
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
    public function testGetUserByEmail()
    {
        $user = User::factory()->make();
        $this->service->createUser($user->attributesToArray());

        $user = $this->service->getUserByEmail($user->email);
        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas('users', ['email' => $user->email]);
    }

    /**
     * @test
     */
    public function testGeneratePasswordResetToken()
    {
        $user = User::factory()->make();
        $userData = $this->service->createUser($user->attributesToArray());

        $result = $this->service->generatePasswordResetToken($userData->id);
        $this->assertInstanceOf(ResetPassword::class, $result);
        $this->assertDatabaseHas('reset_password', ['user_id' => $userData->id, 'token' => $result->token]);
    }

    /**
     * @test
     */
    public function testResetPassword()
    {
        $user = User::factory()->make();
        $userData = $this->service->createUser($user->attributesToArray());

        $result = $this->service->generatePasswordResetToken($userData->id);

        $this->service->resetPassword($result->token, $user->password);
        $this->assertDatabaseMissing('reset_password', ['token' => $result->token]);
    }
}
