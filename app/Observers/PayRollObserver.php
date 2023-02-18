<?php

namespace App\Observers;

use App\PayRoll;
use Illuminate\Support\Facades\Auth;

class PayRollObserver
{
    /**
     * Handle the pay roll "created" event.
     *
     * @param  \App\PayRoll  $payRoll
     * @return void
     */
    public function creating(PayRoll $payRoll)
    {
        $payRoll->created_by = Auth::id();
        $payRoll->updated_by = Auth::id();
    }

    /**
     * Handle the pay roll "updated" event.
     *
     * @param  \App\PayRoll  $payRoll
     * @return void
     */
    public function updating(PayRoll $payRoll)
    {
        $payRoll->updated_by = Auth::id();
    }

    /**
     * Handle the pay roll "deleted" event.
     *
     * @param  \App\PayRoll  $payRoll
     * @return void
     */
    public function deleted(PayRoll $payRoll)
    {
        //
    }

    /**
     * Handle the pay roll "restored" event.
     *
     * @param  \App\PayRoll  $payRoll
     * @return void
     */
    public function restored(PayRoll $payRoll)
    {
        //
    }

    /**
     * Handle the pay roll "force deleted" event.
     *
     * @param  \App\PayRoll  $payRoll
     * @return void
     */
    public function forceDeleted(PayRoll $payRoll)
    {
        //
    }
}
