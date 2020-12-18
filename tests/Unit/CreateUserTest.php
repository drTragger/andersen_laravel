<?php

namespace Tests\Unit;

use App\services\UserService;
use PHPUnit\Framework\TestCase;

class CreateUserTest extends \Tests\TestCase
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
    public function testCreateUser()
    {
        $data = [
            'name' => 'Steve',
            'email' => 'coolmail@mail.com',
            'password' => 'qwerty',
        ];

        $createdUser = $this->service->createUser($data);
        $this->assertInstanceOf(UserService::class, new UserService());
        $this->assertDatabaseHas('users', ['email' => $data['email'], 'name' => $data['name'], 'password' => $data['password']]);
    }
}
