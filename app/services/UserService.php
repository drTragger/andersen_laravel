<?php


namespace App\services;


use App\Models\ResetPassword;
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
     * @param string $email
     * @return mixed
     */
    public function getUserByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    public function generatePasswordResetToken(int $userId)
    {
        $data = [
            'token' => str_random(10),
            'user_id' => $userId,
        ];
        return ResetPassword::create($data);
    }

    public function getTokenData(string $token)
    {
        return ResetPassword::where('token', $token)->first();
    }

    public function getUserById(int $id)
    {
        return User::where('id', $id)->first();
    }

    public function updatePassword(User $user, string $password)
    {
        $user->password = bcrypt($password);
        $user->update();
    }

    public function removeToken(string $token)
    {
        return ResetPassword::where('token', $token)->delete();
    }
}
