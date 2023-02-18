<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'id',
        'name',
        'code',
        'status',
        'request',
        'user_id'
    ];

    public function user()
    {
        return  $this->belongsTo(User::class, 'user_id', 'id');
    }
}
