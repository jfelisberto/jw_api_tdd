<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResetTokens extends Model
{
    use HasFactory;
    const UPDATED_AT = 'created_at';

    protected $table = 'password_reset_tokens';

    protected $fillable = [
        'email',
        'token'
    ];
}
