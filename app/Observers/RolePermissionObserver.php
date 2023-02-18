<?php

namespace App\Observers;

use App\RolePermission;
use Illuminate\Support\Facades\Auth;

class RolePermissionObserver
{
    /**
     * Handle the role permission "created" event.
     *
     * @param  \App\RolePermission  $rolePermission
     * @return void
     */
    public function creating(RolePermission $rolePermission)
    {
        $rolePermission->created_by = Auth::id();
        $rolePermission->updated_by = Auth::id();
    }

    /**
     * Handle the role permission "updated" event.
     *
     * @param  \App\RolePermission  $rolePermission
     * @return void
     */
    public function updating(RolePermission $rolePermission)
    {;
        $rolePermission->updated_by = Auth::id();
    }

    /**
     * Handle the role permission "deleted" event.
     *
     * @param  \App\RolePermission  $rolePermission
     * @return void
     */
    public function deleted(RolePermission $rolePermission)
    {
        //
    }

    /**
     * Handle the role permission "restored" event.
     *
     * @param  \App\RolePermission  $rolePermission
     * @return void
     */
    public function restored(RolePermission $rolePermission)
    {
        //
    }

    /**
     * Handle the role permission "force deleted" event.
     *
     * @param  \App\RolePermission  $rolePermission
     * @return void
     */
    public function forceDeleted(RolePermission $rolePermission)
    {
        //
    }
}
