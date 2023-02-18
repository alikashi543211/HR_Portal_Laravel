<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPayRollExtra extends Model
{

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function userPayRoll()
    {
        return $this->belongsTo('App\UserPayRoll', 'user_pay_roll_id');
    }
}
