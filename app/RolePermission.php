<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $table = 'role_permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id', 'permission_id', 'action_id'
    ];

    public function getActionIdAttribute($value)
    {
        return $value == 1 ? 'Read' : 'Write';
    }

    public function permissions()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
}
