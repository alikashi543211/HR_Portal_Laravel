<?php

namespace App\Observers;

use App\UserLeave;
use Illuminate\Support\Facades\Auth;

class UserLeaveObserver
{
    /**
     * Handle the user leave "created" event.
     *
     * @param  \App\UserLeave  $userLeave
     * @return void
     */
    public function creating(UserLeave $userLeave)
    {
        $userLeave->created_by = Auth::id();
        $userLeave->updated_by = Auth::id();
    }

    /**
     * Handle the user leave "updated" event.
     *
     * @param  \App\UserLeave  $userLeave
     * @return void
     */
    public function updating(UserLeave $userLeave)
    {
        $userLeave->updated_by = Auth::id();
    }

    /**
     * Handle the user leave "deleted" event.
     *
     * @param  \App\UserLeave  $userLeave
     * @return void
     */
    public function deleted(UserLeave $userLeave)
    {
        //
    }

    /**
     * Handle the user leave "restored" event.
     *
     * @param  \App\UserLeave  $userLeave
     * @return void
     */
    public function restored(UserLeave $userLeave)
    {
        //
    }

    /**
     * Handle the user leave "force deleted" event.
     *
     * @param  \App\UserLeave  $userLeave
     * @return void
     */
    public function forceDeleted(UserLeave $userLeave)
    {
        //
    }
}
