<?php

namespace App\Observers;

use App\LatePolicyUserException;
use Illuminate\Support\Facades\Auth;

class LatePolicyUserExceptionObserver
{
    /**
     * Handle the late policy user exception "created" event.
     *
     * @param  \App\LatePolicyUserException  $latePolicyUserException
     * @return void
     */
    public function creating(LatePolicyUserException $latePolicyUserException)
    {
        $latePolicyUserException->created_by = Auth::id();
        $latePolicyUserException->updated_by = Auth::id();
    }

    /**
     * Handle the late policy user exception "updated" event.
     *
     * @param  \App\LatePolicyUserException  $latePolicyUserException
     * @return void
     */
    public function updating(LatePolicyUserException $latePolicyUserException)
    {
        $latePolicyUserException->updated_by = Auth::id();
    }

    /**
     * Handle the late policy user exception "deleted" event.
     *
     * @param  \App\LatePolicyUserException  $latePolicyUserException
     * @return void
     */
    public function deleted(LatePolicyUserException $latePolicyUserException)
    {
        //
    }

    /**
     * Handle the late policy user exception "restored" event.
     *
     * @param  \App\LatePolicyUserException  $latePolicyUserException
     * @return void
     */
    public function restored(LatePolicyUserException $latePolicyUserException)
    {
        //
    }

    /**
     * Handle the late policy user exception "force deleted" event.
     *
     * @param  \App\LatePolicyUserException  $latePolicyUserException
     * @return void
     */
    public function forceDeleted(LatePolicyUserException $latePolicyUserException)
    {
        //
    }
}
