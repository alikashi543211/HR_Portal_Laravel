<?php

namespace Database\Seeders;

use App\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRoleSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('roles')->truncate();

        $roles = array(
            array('id' => 1, 'slug' => 'super_admin',  'title' => 'Super Admin'),
            array('id' => 2, 'slug' => 'admin',  'title' => 'Admin'),
            array('id' => 3, 'slug' => 'human_resource',  'title' => 'Human Resource'),
            array('id' => 4, 'slug' => 'accountant',  'title' => 'Accountant'),
            array('id' => 5, 'slug' => 'manager',  'title' => 'Manager'),
            array('id' => 6, 'slug' => 'employee',  'title' => 'Employee'),
        );
        Role::insert($roles);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
