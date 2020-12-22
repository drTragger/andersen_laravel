<?php


namespace App\services;


use App\Models\ResetPassword;
use App\Models\User;
use Carbon\Carbon;
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

    public function resetPassword(string $token, string $password)
    {
        $resetPasswordData = ResetPassword::where('token', $token)->first();
        if (isset($resetPasswordData)) {
            $start = Carbon::parse($resetPasswordData->updated_at);
            $finish = Carbon::parse(Carbon::now());
            $duration = $finish->diffInSeconds($start);
            if ($duration / 60 >= 120) {
                $user = User::where('id', $resetPasswordData->user_id)->first();
                $user->password = $password;
                $user->update();
                ResetPassword::where('token', $token)->first()->delete();
                return response(['message' => 'Password reset successfully']);
            }
            return response(['message' => 'You can reset password in 2 hours after last update'], 425);
        }
        return response(['message' => 'Wrong token'], 418);
    }
}
