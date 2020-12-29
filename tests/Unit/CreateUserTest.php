<?php

namespace Tests\Unit;

use App\Services\UserService;
use PHPUnit\Framework\TestCase;
use App\Models\User;

class CreateUserTest extends \Tests\TestCase
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
    public function testCreateUser()
    {
        $user = User::factory()->make()->attributesToArray();

        $createdUser = $this->service->createUser($user);
        $this->assertInstanceOf(User::class, $createdUser);
        $this->assertDatabaseHas('users', ['name'=>$user['name'], 'email'=>$user['email']]);
    }
}
