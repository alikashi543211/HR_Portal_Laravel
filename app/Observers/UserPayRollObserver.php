<?php

namespace App\Observers;

use App\UserPayRoll;
use Illuminate\Support\Facades\Auth;

class UserPayRollObserver
{
    /**
     * Handle the user pay roll "created" event.
     *
     * @param  \App\UserPayRoll  $userPayRoll
     * @return void
     */
    public function creating(UserPayRoll $userPayRoll)
    {
        $userPayRoll->created_by = Auth::id();
        $userPayRoll->updated_by = Auth::id();
    }

    /**
     * Handle the user pay roll "updated" event.
     *
     * @param  \App\UserPayRoll  $userPayRoll
     * @return void
     */
    public function updating(UserPayRoll $userPayRoll)
    {
        $userPayRoll->updated_by = Auth::id();
    }

    /**
     * Handle the user pay roll "deleted" event.
     *
     * @param  \App\UserPayRoll  $userPayRoll
     * @return void
     */
    public function deleted(UserPayRoll $userPayRoll)
    {
        //
    }

    /**
     * Handle the user pay roll "restored" event.
     *
     * @param  \App\UserPayRoll  $userPayRoll
     * @return void
     */
    public function restored(UserPayRoll $userPayRoll)
    {
        //
    }

    /**
     * Handle the user pay roll "force deleted" event.
     *
     * @param  \App\UserPayRoll  $userPayRoll
     * @return void
     */
    public function forceDeleted(UserPayRoll $userPayRoll)
    {
        //
    }
}
