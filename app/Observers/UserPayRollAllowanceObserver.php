<?php

namespace App\Observers;

use App\UserPayRollAllowance;
use Illuminate\Support\Facades\Auth;

class UserPayRollAllowanceObserver
{
    /**
     * Handle the user pay roll allowance "created" event.
     *
     * @param  \App\UserPayRollAllowance  $userPayRollAllowance
     * @return void
     */
    public function creating(UserPayRollAllowance $userPayRollAllowance)
    {
        $userPayRollAllowance->created_by = Auth::id();
        $userPayRollAllowance->updated_by = Auth::id();
    }

    /**
     * Handle the user pay roll allowance "updated" event.
     *
     * @param  \App\UserPayRollAllowance  $userPayRollAllowance
     * @return void
     */
    public function updating(UserPayRollAllowance $userPayRollAllowance)
    {
        $userPayRollAllowance->updated_by = Auth::id();
    }

    /**
     * Handle the user pay roll allowance "deleted" event.
     *
     * @param  \App\UserPayRollAllowance  $userPayRollAllowance
     * @return void
     */
    public function deleted(UserPayRollAllowance $userPayRollAllowance)
    {
        //
    }

    /**
     * Handle the user pay roll allowance "restored" event.
     *
     * @param  \App\UserPayRollAllowance  $userPayRollAllowance
     * @return void
     */
    public function restored(UserPayRollAllowance $userPayRollAllowance)
    {
        //
    }

    /**
     * Handle the user pay roll allowance "force deleted" event.
     *
     * @param  \App\UserPayRollAllowance  $userPayRollAllowance
     * @return void
     */
    public function forceDeleted(UserPayRollAllowance $userPayRollAllowance)
    {
        //
    }
}
