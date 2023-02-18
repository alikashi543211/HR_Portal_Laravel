<?php

namespace App\Observers;

use App\UserPayRollExtra;
use Illuminate\Support\Facades\Auth;

class UserPayRollExtraObserver
{
    /**
     * Handle the user pay roll extra "created" event.
     *
     * @param  \App\UserPayRollExtra  $userPayRollExtra
     * @return void
     */
    public function creating(UserPayRollExtra $userPayRollExtra)
    {
        $userPayRollExtra->created_by = Auth::id();
        $userPayRollExtra->updated_by = Auth::id();
    }

    /**
     * Handle the user pay roll extra "updated" event.
     *
     * @param  \App\UserPayRollExtra  $userPayRollExtra
     * @return void
     */
    public function updating(UserPayRollExtra $userPayRollExtra)
    {
        $userPayRollExtra->updated_by = Auth::id();
    }

    /**
     * Handle the user pay roll extra "deleted" event.
     *
     * @param  \App\UserPayRollExtra  $userPayRollExtra
     * @return void
     */
    public function deleted(UserPayRollExtra $userPayRollExtra)
    {
        //
    }

    /**
     * Handle the user pay roll extra "restored" event.
     *
     * @param  \App\UserPayRollExtra  $userPayRollExtra
     * @return void
     */
    public function restored(UserPayRollExtra $userPayRollExtra)
    {
        //
    }

    /**
     * Handle the user pay roll extra "force deleted" event.
     *
     * @param  \App\UserPayRollExtra  $userPayRollExtra
     * @return void
     */
    public function forceDeleted(UserPayRollExtra $userPayRollExtra)
    {
        //
    }
}
