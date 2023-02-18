<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateUsersOfDifferentRoles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $users = array(
            // users
            array('first_name' => 'Mr', 'last_name' => 'Hr', 'dob' => '1990-02-20', 'email' => "hr@devstudio.us", "password" => Hash::make('admin123'), 'designation' => 'Human Resource', 'status' => 'Permanent', 'doj' => '2020-02-18', 'dop' => '2020-05-18', 'employee_id' => 54, 'nationality' => 'Pakistani', 'base_salary' => 60000, 'role_id' => HUMAN_RESOURCE),
            array('first_name' => 'Mr', 'last_name' => 'Accountant', 'dob' => '1990-02-20', 'email' => "accountant@devstudio.us", "password" => Hash::make('admin123'), 'designation' => 'Accountant', 'status' => 'Permanent', 'doj' => '2020-05-02', 'dop' => '2020-08-02', 'employee_id' => 37, 'nationality' => 'Pakistani', 'base_salary' => 60000, 'role_id' => ACCOUNTANT),
            array('first_name' => 'Mr', 'last_name' => 'Manager', 'dob' => '1990-03-17', 'email' => "manager@devstudio.us", "password" => Hash::make('admin123'), 'designation' => 'Manager', 'status' => 'Permanent', 'doj' => '2020-09-23', 'dop' => '2020-12-23', 'employee_id' => 22, 'nationality' => 'Pakistani', 'base_salary' => 60000, 'role_id' => MANAGER)

        );
        User::insert($users);

        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
