<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'type', 'date', 'created_by'
    ];

    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by');
    }
}
