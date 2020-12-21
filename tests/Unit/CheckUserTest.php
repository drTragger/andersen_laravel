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
            'name' => 'john',
            'email' => 'email10@example.com',
            'password' => '1m2i3s4h5a',
        ];

        $response = $this->service->checkUser($data);
        $this->assertInstanceOf(User::class, $response);
        $this->assertDatabaseHas('users', ['name'=>$data['name'], 'email'=>$data['email']]);
    }
}
