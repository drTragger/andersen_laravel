<?php

namespace Tests\Unit;

use App\Models\User;
use App\services\UserService;
use Tests\TestCase;

class CheckUserTest extends TestCase
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
    public function testCheckUser()
    {
        $data = [
            'email' => 'email10@example.com',
        ];

        $response = $this->service->getUserByEmail($data['email']);
        $this->assertInstanceOf(User::class, $response);
        $this->assertDatabaseHas('users', ['email' => $data['email']]);
    }
}
