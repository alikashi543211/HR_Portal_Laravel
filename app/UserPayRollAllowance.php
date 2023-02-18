<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPayRollAllowance extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'user_pay_roll_id', 'allowance_id', 'amount'
    ];

    public function getAmountAttribute($value)
    {
        return round($value, 2);
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function userPayRoll()
    {
        return $this->belongsTo('App\UserPayRoll', 'user_pay_roll_id');
    }
}
