<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Web extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'login_url',
        'post_new_url',
        'post_save_url',
        'admin',
        'password'
    ];
}
