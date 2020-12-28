<?php

namespace Tests\Unit;

use App\Models\User;
use App\services\UserService;
use PHPUnit\Framework\TestCase;

class UpdateUserDataTest extends \Tests\TestCase
{
    protected $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = app()->make(UserService::class);
    }

    /**
     *@test
     */
    public function testUpdateData()
    {
        $user = User::factory()->make();
        $userData = $this->service->createUser($user->attributesToArray());

        $data = User::factory()->make()->only('name', 'email');

        $status = $this->service->updateUserData($data, $userData->id);

        $this->assertTrue($status);
        $this->assertDatabaseHas('users', ['name' => $data['name'], 'email' => $data['email']]);
        $this->assertDatabaseMissing('users', ['email' => $userData['email']]);
    }
}
