<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserPayRoll extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'gross_salary', 'net_salary', 'base_salary', 'late_deduction', 'leave_deduction', 'govrt_tax', 'date', 'user_id',
        'pay_roll_id', 'allowances'
    ];

    /**
     * Get the Paid working days
     *
     * @param  string  $value
     * @return string
     */
    // public function getPaidWorkingDaysAttribute($value)
    // {
    //     return round($value - $this->leave_count, 1);
    // }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function getNetSalaryAttribute($value)
    {
        return $value + $this->getTotalAllowances() + $this->getTotalContributions() - $this->getTotalDeductions() - $this->govrt_tax;
    }

    public function getAllowancesAttribute($value)
    {
        return $this->getTotalAllowances();
    }

    public function payRoll()
    {
        return $this->belongsTo('App\PayRoll', 'pay_roll_id');
    }

    public function getAllowances()
    {
        return $this->belongsToMany('App\Allowance', 'user_pay_roll_allowances', 'user_pay_roll_id', 'allowance_id')->withPivot(['id', 'amount']);
    }

    public function extras()
    {
        return $this->hasMany('App\UserPayRollExtra', 'user_pay_roll_id');
    }

    public function otherContributions()
    {
        return $this->extras->where('type', EXTRA_CONTRIBUTIONS)->sum('amount');
    }

    public function otherDeductions()
    {
        return $this->extras->where('type', EXTRA_DEDUCTIONS)->sum('amount');
    }

    public function grossWorkingDays($month)
    {
        $halfDays =  DB::table('user_leaves')->where('user_id', $this->user_id)->where('date', 'LIKE', '%' . $month . '%')->wherePeriod(HALF_DAY_LEAVE)->where('leave_adjust', false)->count() / 2;
        $fullDays =  DB::table('user_leaves')->where('user_id', $this->user_id)->where('date', 'LIKE', '%' . $month . '%')->wherePeriod(FULL_DAY_LEAVE)->where('leave_adjust', false)->count();
        return $halfDays + $fullDays;
    }

    public function getTotalAllowances()
    {
        return DB::table('user_pay_roll_allowances')->where('user_pay_roll_id', $this->id)->sum('amount');
    }

    public function getTotalContributions()
    {
        return DB::table('user_pay_roll_extras')->where('user_pay_roll_id', $this->id)->where('type', EXTRA_CONTRIBUTIONS)->sum('amount');
    }

    public function getTotalDeductions()
    {
        return  DB::table('user_pay_roll_extras')->where('user_pay_roll_id', $this->id)->where('type', EXTRA_DEDUCTIONS)->sum('amount');
    }
}
