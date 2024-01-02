<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResetPassword extends Model
{
    use HasFactory;

    #jika nama table tidak sesuai dengan peraturan laravel
    // protected $table = 'reset_passwords';

    protected $fillable = [
        'id_user', 'token', 'expired', 'is_used'
    ];
}
