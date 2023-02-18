<?php

namespace App\Observers;

use App\LatePolicyUser;
use Illuminate\Support\Facades\Auth;

class LatePolicyUserObserver
{
    /**
     * Handle the late policy user exception "created" event.
     *
     * @param  \App\LatePolicyUser  $LatePolicyUser
     * @return void
     */
    public function creating(LatePolicyUser $LatePolicyUser)
    {
        $LatePolicyUser->created_by = Auth::id();
        $LatePolicyUser->updated_by = Auth::id();
    }

    /**
     * Handle the late policy user exception "updated" event.
     *
     * @param  \App\LatePolicyUser  $LatePolicyUser
     * @return void
     */
    public function updating(LatePolicyUser $LatePolicyUser)
    {
        $LatePolicyUser->updated_by = Auth::id();
    }

    /**
     * Handle the late policy user exception "deleted" event.
     *
     * @param  \App\LatePolicyUser  $LatePolicyUser
     * @return void
     */
    public function deleted(LatePolicyUser $LatePolicyUser)
    {
        //
    }

    /**
     * Handle the late policy user exception "restored" event.
     *
     * @param  \App\LatePolicyUser  $LatePolicyUser
     * @return void
     */
    public function restored(LatePolicyUser $LatePolicyUser)
    {
        //
    }

    /**
     * Handle the late policy user exception "force deleted" event.
     *
     * @param  \App\LatePolicyUser  $LatePolicyUser
     * @return void
     */
    public function forceDeleted(LatePolicyUser $LatePolicyUser)
    {
        //
    }
}
