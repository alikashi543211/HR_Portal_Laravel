<?php

namespace App\Observers;

use App\LatePolicy;
use Illuminate\Support\Facades\Auth;

class LatePolicyObserver
{
    /**
     * Handle the late policy "created" event.
     *
     * @param  \App\LatePolicy  $latePolicy
     * @return void
     */
    public function creating(LatePolicy $latePolicy)
    {
        $latePolicy->created_by = Auth::id();
        $latePolicy->updated_by = Auth::id();
    }

    /**
     * Handle the late policy "updated" event.
     *
     * @param  \App\LatePolicy  $latePolicy
     * @return void
     */
    public function updating(LatePolicy $latePolicy)
    {
        $latePolicy->updated_by = Auth::id();
    }

    /**
     * Handle the late policy "deleted" event.
     *
     * @param  \App\LatePolicy  $latePolicy
     * @return void
     */
    public function deleted(LatePolicy $latePolicy)
    {
        //
    }

    /**
     * Handle the late policy "restored" event.
     *
     * @param  \App\LatePolicy  $latePolicy
     * @return void
     */
    public function restored(LatePolicy $latePolicy)
    {
        //
    }

    /**
     * Handle the late policy "force deleted" event.
     *
     * @param  \App\LatePolicy  $latePolicy
     * @return void
     */
    public function forceDeleted(LatePolicy $latePolicy)
    {
        //
    }
}
