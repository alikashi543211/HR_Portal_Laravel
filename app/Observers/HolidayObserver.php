<?php

namespace App\Observers;

use App\Holiday;
use Illuminate\Support\Facades\Auth;

class HolidayObserver
{
    /**
     * Handle the holiday "created" event.
     *
     * @param  \App\Holiday  $holiday
     * @return void
     */
    public function creating(Holiday $holiday)
    {
        $holiday->created_by = Auth::id();
        $holiday->updated_by = Auth::id();
    }

    /**
     * Handle the holiday "updated" event.
     *
     * @param  \App\Holiday  $holiday
     * @return void
     */
    public function updating(Holiday $holiday)
    {
        $holiday->updated_by = Auth::id();
    }

    /**
     * Handle the holiday "deleted" event.
     *
     * @param  \App\Holiday  $holiday
     * @return void
     */
    public function deleted(Holiday $holiday)
    {
        //
    }

    /**
     * Handle the holiday "restored" event.
     *
     * @param  \App\Holiday  $holiday
     * @return void
     */
    public function restored(Holiday $holiday)
    {
        //
    }

    /**
     * Handle the holiday "force deleted" event.
     *
     * @param  \App\Holiday  $holiday
     * @return void
     */
    public function forceDeleted(Holiday $holiday)
    {
        //
    }
}
