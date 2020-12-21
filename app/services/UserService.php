<?php


namespace App\services;


use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function createUser(array $data)
    {
        $data['password'] = bcrypt($data['password']);
        return User::create($data);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function checkUser(array $data)
    {
        return User::where(['email' => $data['email'], 'name' => $data['name']])->first();
    }
}
