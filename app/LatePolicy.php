<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class LatePolicy extends Model
{
    protected $table = 'late_policy';
    protected $appends = ['add_variation_allow'];
    public function getVariationsAttribute($value)
    {
        return !empty($value) ? json_decode($value) : [];
    }
    public function getAddVariationAllowAttribute($value)
    {
        $return = true;
        foreach ($this->variations as $key => $var) {
            if ($var->type == 1) {
                $return = false;
                break;
            }
        }

        return $return;
    }

    public function user()
    {
        return $this->belongsToMany('App\User', 'late_policy_users', 'late_policy_id', 'user_id');
    }
}
