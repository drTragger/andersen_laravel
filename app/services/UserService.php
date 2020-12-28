<?php


namespace App\services;


use App\Mail\DeleteAccount;
use App\Models\ResetPassword;
use App\Models\User;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use phpDocumentor\Reflection\Types\Boolean;

class UserService
{
    /**
     * @param array $data
     * @return User
     */
    public function createUser(array $data): User
    {
        $data['password'] = bcrypt($data['password']);
        return User::create($data);
    }

    /**
     * @param string $email
     * @return User
     */
    public function getUserByEmail(string $email): User
    {
        return User::where('email', $email)->first();
    }

    /**
     * @param int $userId
     * @return ResetPassword
     */
    public function generatePasswordResetToken(int $userId): ResetPassword
    {
        $data = [
            'token' => str_random(10),
            'user_id' => $userId,
        ];
        return ResetPassword::create($data);
    }

    /**
     * @param string $token
     * @param string $password
     * @return bool
     */
    public function resetPassword(string $token, string $password): bool
    {
        $resetPasswordData = ResetPassword::where('token', $token)->first();
        $start = Carbon::parse($resetPasswordData->updated_at);
        $finish = Carbon::parse(Carbon::now());
        $duration = $finish->diffInSeconds($start);
        if ($duration / 60 <= 120) {
            $user = User::where('id', $resetPasswordData->user_id)->first();
            $user->password = bcrypt($password);
            $user->update();
            ResetPassword::where('token', $token)->first()->delete();
            return true;
        }
        ResetPassword::where('token', $token)->first()->delete();
        return false;
    }

    /**
     * @param array $newData
     * @param int $userId
     * @return mixed
     */
    public function updateUserData(array $newData, int $userId)
    {
        $user = User::where('id', $userId)->first();
        $user->name = $newData['name'];
        $user->email = $newData['email'];
        return $user->update();
    }

    /**
     * @return array
     */
    public function getUsers(): array
    {
        $users = User::all();
        $plucked = $users->pluck('email');
        return $plucked->all();
    }

    /**
     * @param int $userId
     * @return mixed
     */
    public function getUserById(int $userId)
    {
        return User::where('id', '=', $userId)->first();
    }

    /**
     * @param int $userId
     * @return mixed
     */
    public function deleteUser(int $userId)
    {
        $user = User::where('id', $userId)->first();
        $user->status = User::INACTIVE;
        $user->update();
        $data = [
            'username' => $user->name,
            'email' => $user->email,
            'subject' => 'Delete account',
        ];
        $pdf = PDF::loadView('pdf.delete', $data);
        Mail::to($user->email)->send(new DeleteAccount($pdf->output(), $data));
        return $user;
    }
}
