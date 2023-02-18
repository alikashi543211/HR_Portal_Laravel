<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserLeaveQuota extends Model
{
    protected $table = 'user_leaves_quota';
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
