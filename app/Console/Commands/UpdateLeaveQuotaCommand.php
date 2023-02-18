<?php

namespace App\Console\Commands;

use App\Attendance;
use App\User;
use Illuminate\Console\Command;

class UpdateLeaveQuotaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:leaves';

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
        $users = User::whereNotNull('dop')->get();

        foreach($users AS $key => $user)
        {
            $user->leaveQuota->remaining_casual_leaves += 1;
            $user->leaveQuota->save();
        }

    }
}
