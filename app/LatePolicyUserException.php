<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LatePolicyUserException extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = date('Y-m-d', strtotime($value));
    }
}
