<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PayRoll extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date', 'created_by'
    ];

    public function getBaseSalaryAttribute($value)
    {
        return round($value, 2);
    }

    public function getGrossSalaryAttribute($value)
    {
        return round($value, 2);
    }

    public function getNetSalaryAttribute($value)
    {
        return round($value, 2);
    }

    public function getLateDeductionAttribute($value)
    {
        return round($value, 2);
    }

    public function getHouseRentAttribute($value)
    {
        return round($value, 2);
    }

    public function getUtilityAttribute($value)
    {
        return round($value, 2);
    }

    public function getAllowanceAttribute($value)
    {
        return round($value, 2);
    }

    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function payRolls()
    {
        return $this->hasMany('App\UserPayRoll', 'pay_roll_id');
    }

    public function getDateAttribute($value)
    {
        $date = explode(',', $value);
        $date = Carbon::parse('01-' . $date[0] . '-' . $date[1])->format('Y-m-d');
        return $date;
        // return str_replace(',', '', $value);
    }

    public function payRollTaxes()
    {
        return $this->hasMany(PayRollTax::class, 'pay_roll_id', 'id');
    }
}
