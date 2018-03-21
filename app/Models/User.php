<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Article;

/**
 *
 */
class User extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'username',
        'password',
    ];

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
