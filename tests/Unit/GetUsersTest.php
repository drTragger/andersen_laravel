<?php

namespace Tests\Unit;

use App\Models\User;
use App\services\UserService;
use PHPUnit\Framework\TestCase;

class GetUsersTest extends \Tests\TestCase
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
        $user = User::factory()->make();
        $this->service->createUser($user->attributesToArray());
        $users = $this->service->getUsers();

        $this->assertDatabaseHas('users', ['email' => $users]);
    }

    public function testGetUser()
    {
        $user = User::factory()->make();
        $userData = $this->service->createUser($user->attributesToArray());

        $user = $this->service->getUserById($userData->id);

        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }
}
