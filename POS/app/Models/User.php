<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // Add this line
use Illuminate\Notifications\Notifiable; // If you want to use notifications

class User extends Authenticatable // Extend Authenticatable instead of Model
{
    use Notifiable; // Add this line if you want to use notifications

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Add role to fillable
    ];

    // Other methods (if needed)
}
