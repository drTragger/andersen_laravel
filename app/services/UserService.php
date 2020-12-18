<?php


namespace App\services;


use App\Models\User;

class UserService
{
    public function createUser(array $data)
    {
        return User::create($data);
    }
}
