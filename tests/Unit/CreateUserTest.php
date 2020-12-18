<?php

namespace Tests\Unit;

use App\services\UserService;
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
     * A basic unit test example.
     *
     * @return void
     */
    public function testCreateUser()
    {
        $data = [
            'name' => 'Steve',
            'email' => 'coolmail61@mail.com',
            'password' => 'qwerty',
        ];

        $createdUser = $this->service->createUser($data);
        $this->assertInstanceOf(User::class, $createdUser);
        $this->assertDatabaseHas('users', ['name'=>$data['name'], 'email'=>$data['email']]);
    }
}
