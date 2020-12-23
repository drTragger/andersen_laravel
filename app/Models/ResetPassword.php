<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ResetPassword extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'token',
        'created_at',
    ];

    protected $table = 'reset_password';
}
