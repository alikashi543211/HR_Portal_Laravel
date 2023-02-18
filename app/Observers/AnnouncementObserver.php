<?php

namespace App\Observers;

use App\Announcement;
use Illuminate\Support\Facades\Auth;

class AnnouncementObserver
{
    /**
     * Handle the announcement "creating" event.
     *
     * @param  \App\Announcement  $announcement
     * @return void
     */
    public function creating(Announcement $announcement)
    {
        $announcement->created_by = Auth::id();
        $announcement->updated_by = Auth::id();
    }

    /**
     * Handle the announcement "created" event.
     *
     * @param  \App\Announcement  $announcement
     * @return void
     */
    public function created(Announcement $announcement)
    {
        //
    }

    /**
     * Handle the announcement "updating" event.
     *
     * @param  \App\Announcement  $announcement
     * @return void
     */
    public function updating(Announcement $announcement)
    {
        $announcement->updated_by = Auth::id();
    }

    /**
     * Handle the announcement "updated" event.
     *
     * @param  \App\Announcement  $announcement
     * @return void
     */
    public function updated(Announcement $announcement)
    {
        //
    }

    /**
     * Handle the announcement "deleted" event.
     *
     * @param  \App\Announcement  $announcement
     * @return void
     */
    public function deleted(Announcement $announcement)
    {
        //
    }

    /**
     * Handle the announcement "restored" event.
     *
     * @param  \App\Announcement  $announcement
     * @return void
     */
    public function restored(Announcement $announcement)
    {
        //
    }

    /**
     * Handle the announcement "force deleted" event.
     *
     * @param  \App\Announcement  $announcement
     * @return void
     */
    public function forceDeleted(Announcement $announcement)
    {
        //
    }
}
