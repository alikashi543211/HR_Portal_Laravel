<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayRollTax extends Model
{
    protected $fillable = [
        'id',
        'name',
        'amount',
        'image',
        'pay_roll_id'
    ];

    public function payRoll()
    {
        return  $this->belongsTo(PayRoll::class, 'pay_roll_id', 'id');
    }
}
