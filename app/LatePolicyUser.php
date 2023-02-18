<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class LatePolicyUser extends Model
{
    protected $table = 'late_policy_users';
    public function getVariationsAttribute($value)
    {
        return !empty($value) ? json_decode($value) : [];
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
