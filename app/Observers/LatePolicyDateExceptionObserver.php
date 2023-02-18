<?php

namespace App\Observers;

use App\LatePolicyDateException;
use Illuminate\Support\Facades\Auth;

class LatePolicyDateExceptionObserver
{
    /**
     * Handle the late policy date exception "created" event.
     *
     * @param  \App\LatePolicyDateException  $latePolicyDateException
     * @return void
     */
    public function creating(LatePolicyDateException $latePolicyDateException)
    {
        $latePolicyDateException->created_by = Auth::id();
        $latePolicyDateException->updated_by = Auth::id();
    }

    /**
     * Handle the late policy date exception "updated" event.
     *
     * @param  \App\LatePolicyDateException  $latePolicyDateException
     * @return void
     */
    public function updating(LatePolicyDateException $latePolicyDateException)
    {
        $latePolicyDateException->updated_by = Auth::id();
    }

    /**
     * Handle the late policy date exception "deleted" event.
     *
     * @param  \App\LatePolicyDateException  $latePolicyDateException
     * @return void
     */
    public function deleted(LatePolicyDateException $latePolicyDateException)
    {
        //
    }

    /**
     * Handle the late policy date exception "restored" event.
     *
     * @param  \App\LatePolicyDateException  $latePolicyDateException
     * @return void
     */
    public function restored(LatePolicyDateException $latePolicyDateException)
    {
        //
    }

    /**
     * Handle the late policy date exception "force deleted" event.
     *
     * @param  \App\LatePolicyDateException  $latePolicyDateException
     * @return void
     */
    public function forceDeleted(LatePolicyDateException $latePolicyDateException)
    {
        //
    }
}
