<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewPasswordRequest;
use App\Http\Requests\PasswordResetRequest;
use App\Http\Requests\UpdateDataRequest;
use App\Mail\DeleteAccount;
use App\Mail\ResetPassword;
use App\Models\User;
use App\Services\UserService;
use Barryvdh\DomPDF\Facade as PDF;
use Barryvdh\DomPDF\ServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\User as UserResource;

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

        if ($user->exists) {
            if ($user->status == User::ACTIVE) {
                if (Hash::check($request->password, $user->password)) {
                    $token = $user->createToken('authToken')->accessToken;
                    return response(['access_token' => $token], 202);
                }
                return response(['message' => 'Wrong password'], 422);
            }
            return response(['message' => 'User was deleted'], 422);
        }
        return response(['message' => 'User does not exist'], 404);
    }

    public function resetPassword(PasswordResetRequest $request)
    {
        $data = $request->all();
        $user = $this->userService->getUserByEmail($data['email']);

        if ($user) {
            $passwordResetData = $this->userService->generatePasswordResetToken($user->id);
            Mail::to($user->email)->send(new ResetPassword($passwordResetData->token));
            return response(['message' => 'Password reset code was sent to ' . $user->email]);
        }
        return response(['message' => 'User does not exist'], 422);
    }

    public function setNewPassword(NewPasswordRequest $request)
    {
        $token = $request->token;
        $password = $request->password;
        $status = $this->userService->resetPassword($token, $password);
        return ($status)
            ? response(['message' => 'Password reset successfully'])
            : response(['message' => 'This token is no longer available'], 408);
    }

    public function updateUserData(UpdateDataRequest $request)
    {
        if (Gate::allows('update', $request->user())) {
            $status = $this->userService->updateUserData($request->toArray(), $request->id);
            return ($status)
                ? response(['message' => 'User data updated successfully'])
                : response(['message' => 'Something went wrong'], 422);
        }
        return response(['message' => 'You are not allowed to do this'], 403);
    }

    public function getUsers(Request $response)
    {
        if (Gate::allows('get_users', $response->user())) {
            $emails = $this->userService->getUsers();
            return response(['users' => $emails]);
        }
        return response(['message' => 'You are not allowed to do this'], 403);
    }

    public function getUser($userId)
    {
        if ($this->userService->getUserById($userId)) {
            if (Gate::allows('get_user', (int)$userId)) {
                return response(['user' => new UserResource(User::find($userId))]);
            }
            return response(['message' => 'You are not allowed to do this'], 403);
        }
        return response(['message' => 'User does not exist'], 404);
    }

    public function deleteUser($userId)
    {
        if ($this->userService->getUserById($userId)) {
            if (Gate::allows('delete_user', (int)$userId)) {
                $deletedUser = $this->userService->deleteUser($userId);
                if ($deletedUser->status === User::INACTIVE) {
                    return response(['message' => 'Account was deleted']);
                }
                return response(['message' => 'Could not delete this user'], 422);
            }
            return response(['message' => 'You are not allowed to do this'], 403);
        }
        return response(['message' => 'User does not exist'], 404);
    }
}
