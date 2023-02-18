<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Allowance extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'type', 'value', 'for_all', 'created_by'
    ];

    protected $appends = ['type_name'];

    public function getTypeNameAttribute()
    {
        return $this->type == ALLOWANCES_PERCENTAGE ? 'Percentage' : 'Fixed';
    }

    public function getForAllAttribute($value)
    {
        return $value ? 'Yes' : 'No';
    }

    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by');
    }


    public function userPayRolls()
    {
        return $this->belongsToMany('App\UserPayRoll', 'user_pay_roll_allowances', 'allowance_id', 'user_pay_roll_id');
    }
}
