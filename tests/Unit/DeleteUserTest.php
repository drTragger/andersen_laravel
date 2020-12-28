<?php

namespace Tests\Unit;

use App\Models\User;
use App\services\UserService;
use PHPUnit\Framework\TestCase;

class DeleteUserTest extends \Tests\TestCase
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
    public function testDeleteUser()
    {
        $user = User::factory()->create();

        $deletedUser = $this->service->deleteUser($user->id);

        $this->assertInstanceOf(User::class, $deletedUser);
        $this->assertDatabaseHas('users', ['status' => 2, 'email' => $user->email]);
    }
}
