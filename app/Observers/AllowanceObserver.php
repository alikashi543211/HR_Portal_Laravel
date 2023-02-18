<?php

namespace App\Observers;

use App\Allowance;
use Illuminate\Support\Facades\Auth;

class AllowanceObserver
{
    /**
     * Handle the allowance "created" event.
     *
     * @param  \App\Allowance  $allowance
     * @return void
     */
    public function creating(Allowance $allowance)
    {
        $allowance->created_by = Auth::id();
        $allowance->updated_by = Auth::id();
    }

    /**
     * Handle the allowance "updated" event.
     *
     * @param  \App\Allowance  $allowance
     * @return void
     */
    public function updating(Allowance $allowance)
    {
        $allowance->updated_by = Auth::id();
    }

    /**
     * Handle the allowance "deleted" event.
     *
     * @param  \App\Allowance  $allowance
     * @return void
     */
    public function deleted(Allowance $allowance)
    {
        //
    }

    /**
     * Handle the allowance "restored" event.
     *
     * @param  \App\Allowance  $allowance
     * @return void
     */
    public function restored(Allowance $allowance)
    {
        //
    }

    /**
     * Handle the allowance "force deleted" event.
     *
     * @param  \App\Allowance  $allowance
     * @return void
     */
    public function forceDeleted(Allowance $allowance)
    {
        //
    }
}
