<?php

use App\LatePolicy;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('users')->truncate();
        \DB::table('late_policy')->truncate();


        $defaultUser = new User();
        $defaultUser->first_name = 'Mr';
        $defaultUser->last_name = 'Admin';
        $defaultUser->email = 'admin@devstudio.us';
        $defaultUser->password = Hash::make('admin123');
        $defaultUser->role_id = 1;
        $defaultUser->save();

        $latePolicy = new LatePolicy();
        $latePolicy->start_time = '09:15';
        $latePolicy->end_time = '18:15';
        $latePolicy->relax_time = '09:30';
        $latePolicy->type = '0';
        $latePolicy->per_minute = 5;
        $latePolicy->save();

        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
