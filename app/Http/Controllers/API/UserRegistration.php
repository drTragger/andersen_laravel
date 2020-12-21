<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\services\UserService;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;

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

        $user = $this->userService->checkUser($data);

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
}
