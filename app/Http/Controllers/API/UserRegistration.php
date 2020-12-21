<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewPasswordRequest;
use App\Http\Requests\PasswordResetRequest;
use App\Mail\ResetPassword;
use App\Models\User;
use App\services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserRegistration extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->only(['password', 'email', 'name']);

        $user = $this->userService->createUser($data);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['access_token' => $accessToken], 201);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->all();

        $user = $this->userService->getUserByEmail($data['email']);

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('LogIn')->accessToken;
                return response(['access_token' => $token], 202);
            } else {
                return response(['message' => 'Wrong password'], 422);
            }
        } else {
            return response(['message' => 'User does not exist'], 422);
        }
    }

    public function resetPassword(PasswordResetRequest $request)
    {
        $email = $request->all();
        $user = $this->userService->getUserByEmail($email['email']);

        if ($user) {
            $passwordResetData = $this->userService->generatePasswordResetToken($user->id);
            Mail::to($user->email)->send(new ResetPassword($passwordResetData->token));
            return response(['message' => 'Password reset code was sent to ' . $user->email]);
        }
        return response(['message' => 'User does not exist'], 422);
    }

    public function setNewPassword(NewPasswordRequest $request)
    {
        $tokenData = $this->userService->getTokenData($request->token);
        if ($tokenData) {
            $startTime = Carbon::parse($tokenData->updated_at);
            $finishTime = Carbon::parse(Carbon::now());
            $totalDuration = $finishTime->diffinSeconds($startTime);
            if ($totalDuration / 60 >= 120) {
                $user = $this->userService->getUserById($tokenData->user_id);
                $this->userService->updatePassword($user, $request->password);
                if ($this->userService->removeToken($request->token)) {
                    return response(['message' => 'Password reset successfully']);
                }
            }
            return response(['message' => 'You will be able to reset your password in 2 hours after you last updated it'], 403);
        }
        return response(['message' => 'Wrong password reset code'], 422);
    }
}
