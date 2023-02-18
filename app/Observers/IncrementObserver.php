<?php

namespace App\Observers;

use App\Increment;
use Illuminate\Support\Facades\Auth;

class IncrementObserver
{
    /**
     * Handle the increment "created" event.
     *
     * @param  \App\Increment  $increment
     * @return void
     */
    public function creating(Increment $increment)
    {
        $increment->created_by = Auth::id();
        $increment->updated_by = Auth::id();
    }

    /**
     * Handle the increment "updated" event.
     *
     * @param  \App\Increment  $increment
     * @return void
     */
    public function updating(Increment $increment)
    {
        $increment->updated_by = Auth::id();
    }

    /**
     * Handle the increment "deleted" event.
     *
     * @param  \App\Increment  $increment
     * @return void
     */
    public function deleted(Increment $increment)
    {
        //
    }

    /**
     * Handle the increment "restored" event.
     *
     * @param  \App\Increment  $increment
     * @return void
     */
    public function restored(Increment $increment)
    {
        //
    }

    /**
     * Handle the increment "force deleted" event.
     *
     * @param  \App\Increment  $increment
     * @return void
     */
    public function forceDeleted(Increment $increment)
    {
        //
    }
}
