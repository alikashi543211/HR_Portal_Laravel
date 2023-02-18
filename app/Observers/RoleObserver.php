<?php

namespace App\Observers;

use App\Role;
use Illuminate\Support\Facades\Auth;

class RoleObserver
{
    /**
     * Handle the role "created" event.
     *
     * @param  \App\Role  $role
     * @return void
     */
    public function creating(Role $role)
    {
        $role->created_by = Auth::id();
        $role->updated_by = Auth::id();
    }

    /**
     * Handle the role "updated" event.
     *
     * @param  \App\Role  $role
     * @return void
     */
    public function updating(Role $role)
    {
        $role->updated_by = Auth::id();
    }

    /**
     * Handle the role "deleted" event.
     *
     * @param  \App\Role  $role
     * @return void
     */
    public function deleted(Role $role)
    {
        //
    }

    /**
     * Handle the role "restored" event.
     *
     * @param  \App\Role  $role
     * @return void
     */
    public function restored(Role $role)
    {
        //
    }

    /**
     * Handle the role "force deleted" event.
     *
     * @param  \App\Role  $role
     * @return void
     */
    public function forceDeleted(Role $role)
    {
        //
    }
}
