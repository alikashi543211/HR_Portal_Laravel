<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    protected $fillable = [
        'name',
        'address',
        'phone',
        'authorized_name',
        'authorized_designation',
        'phone',
    ];
}
