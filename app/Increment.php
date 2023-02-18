<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Increment extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'previous', 'increment', 'month'];
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by', 'id');
    }

    /**
     * Set the Month
     *
     * @param  string  $value
     * @return void
     */
    public function setMonthAttribute($value)
    {
        return $this->attributes['month'] = date('Y-m-d', strtotime($value));
    }

    /**
     * Get the month
     *
     * @param  string  $value
     * @return string
     */
    public function getMonthAttribute($value)
    {
        return date('M, Y', strtotime($value));
    }
}
