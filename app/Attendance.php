<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Attendance extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'created_by', 'updated_by', 'type', 'action_time'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }
}
