<?php

namespace App\Observers;

use App\UserLeaveQuota;
use Illuminate\Support\Facades\Auth;

class UserLeaveQuotaObserver
{
    /**
     * Handle the user leave quota "created" event.
     *
     * @param  \App\UserLeaveQuota  $userLeaveQuota
     * @return void
     */
    public function creating(UserLeaveQuota $userLeaveQuota)
    {
        $userLeaveQuota->created_by = Auth::id();
        $userLeaveQuota->updated_by = Auth::id();
    }

    /**
     * Handle the user leave quota "updated" event.
     *
     * @param  \App\UserLeaveQuota  $userLeaveQuota
     * @return void
     */
    public function updating(UserLeaveQuota $userLeaveQuota)
    {
        $userLeaveQuota->updated_by = Auth::id();
    }

    /**
     * Handle the user leave quota "deleted" event.
     *
     * @param  \App\UserLeaveQuota  $userLeaveQuota
     * @return void
     */
    public function deleted(UserLeaveQuota $userLeaveQuota)
    {
        //
    }

    /**
     * Handle the user leave quota "restored" event.
     *
     * @param  \App\UserLeaveQuota  $userLeaveQuota
     * @return void
     */
    public function restored(UserLeaveQuota $userLeaveQuota)
    {
        //
    }

    /**
     * Handle the user leave quota "force deleted" event.
     *
     * @param  \App\UserLeaveQuota  $userLeaveQuota
     * @return void
     */
    public function forceDeleted(UserLeaveQuota $userLeaveQuota)
    {
        //
    }
}
