<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAttendanceException extends Model
{
    protected $table = "user_attendance_exceptions";

    protected $fillabe = ['date'];

    public function updatedBy()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }
}
