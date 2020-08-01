<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{

    use HasApiTokens, Notifiable;

    protected $table = 'users';

    /**
     * @var array
     */
    
    protected $fillable = [
        'username', 'email', 'password'
    ];

    /**
     * @var array
     */

    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

    public function post() {
        return $this->hasMany('App\Post');
    }

    public function followers() {
        return $this->belongsToMany(User::class, 'followers', 'following_id', 'follower_id');
    }

    public function following() {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'following_id');
    }
}
