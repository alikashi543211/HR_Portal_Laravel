<?php

namespace App\Observers;

use App\Attendance;
use Illuminate\Support\Facades\Auth;

class AttendanceObserver
{
    /**
     * Handle the attendance "created" event.
     *
     * @param  \App\Attendance  $attendance
     * @return void
     */
    public function creating(Attendance $attendance)
    {
        if (Auth::check()) {
            $attendance->created_by = Auth::id();
            $attendance->updated_by = Auth::id();
        }
    }

    /**
     * Handle the attendance "updated" event.
     *
     * @param  \App\Attendance  $attendance
     * @return void
     */
    public function updating(Attendance $attendance)
    {
        if (Auth::check()) {
            $attendance->updated_by = Auth::id();
        }
    }

    /**
     * Handle the attendance "deleted" event.
     *
     * @param  \App\Attendance  $attendance
     * @return void
     */
    public function deleted(Attendance $attendance)
    {
        //
    }

    /**
     * Handle the attendance "restored" event.
     *
     * @param  \App\Attendance  $attendance
     * @return void
     */
    public function restored(Attendance $attendance)
    {
        //
    }

    /**
     * Handle the attendance "force deleted" event.
     *
     * @param  \App\Attendance  $attendance
     * @return void
     */
    public function forceDeleted(Attendance $attendance)
    {
        //
    }
}
