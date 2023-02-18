<?php

namespace App\Console\Commands;

use App\Attendance;
use App\User;
use Illuminate\Console\Command;

class AdjustCheckInForTodayCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adjust:checkin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::whereDoesntHave('checkOut', function($q){
            $q->whereActionTime(date('Y-m-d'));
        })->whereHas('checkIn', function($q){
            $q->whereActionTime(date('Y-m-d'));
        })->get();

        foreach($users AS $key => $user)
        {
            $new = new Attendance();
            $new->user_id = $user->id;
            $new->type = CHECK_OUT;
            $new->created_by = $user->id;
            $new->action_time = date('Y-m-d H:i', strtotime("+13 hours", strtotime(date('Y-m-d'))));
            $new->save();
        }

    }
}
