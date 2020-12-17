<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;

class UserRegistration extends Controller
{
    public function register(RegisterRequest $request)
    {
//        $validatedData = $request->validate([
//            'email' => 'email|required',
//            'password' => 'required|required_with:confirmPassword|same:confirmPassword',
//            'confirmPassword' => 'required',
//        ]);
        $data = $request->only(['password', 'email', 'name']);

        $data['password'] = bcrypt($request->password);

        $user = User::create($data);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['access_token' => $accessToken], 201);
    }
}
