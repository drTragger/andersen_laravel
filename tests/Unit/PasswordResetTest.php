<?php

namespace Tests\Unit;

use App\Models\ResetPassword;
use App\Models\User;
use App\services\UserService;
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
     * A basic unit test example.
     *
     * @return void
     */
    public function testGetUserByEmail()
    {
        $email = 'email@example.com';

        $user = $this->service->getUserByEmail($email);
        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas('users', ['email' => $email]);
    }

    public function testGeneratePasswordResetToken()
    {
        $userId = 1;

        $result = $this->service->generatePasswordResetToken($userId);
        $this->assertInstanceOf(ResetPassword::class, $result);
        $this->assertDatabaseHas('reset_password', ['user_id' => $userId, 'token' => $result->token]);
    }

    public function testResetPassword()
    {
        $token = 'MlNGkWa99m';
        $password = 'qwerty';

        $this->service->resetPassword($token, $password);
        $this->assertDatabaseMissing('reset_password', ['token' => $token]);
    }
}
