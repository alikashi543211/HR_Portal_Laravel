<?php

namespace Database\Seeders;

use App\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('permissions')->truncate();
        //
        $permissions = array(
            // users
            array('id' => 1, 'module' => 'users', 'name' => 'Users', 'code' => 'users'),
            array('id' => 2, 'module' => 'attendances', 'name' => 'Attendance', 'code' => 'attendances'),
            array('id' => 3, 'module' => 'late_policy', 'name' => 'Late Policy', 'code' => 'late-policy'),
            array('id' => 4, 'module' => 'holidays', 'name' => 'Holidays', 'code' => 'holidays'),
            array('id' => 5, 'module' => 'leaves', 'name' => 'Leaves', 'code' => 'leaves'),
            array('id' => 6, 'module' => 'permissions', 'name' => 'Permissions', 'code' => 'permissions'),
            array('id' => 7, 'module' => 'allowances', 'name' => 'Allowances', 'code' => 'allowances'),
            array('id' => 8, 'module' => 'pay_rolls', 'name' => 'Pay Rolls', 'code' => 'pay-rolls'),
            array('id' => 9, 'module' => 'increments', 'name' => 'Increments', 'code' => 'increments'),
            array('id' => 10, 'module' => 'announcements', 'name' => 'Announcements', 'code' => 'announcements'),
            array('id' => 11, 'module' => 'emails', 'name' => 'Send Email', 'code' => 'emails'),
            array('id' => 12, 'module' => 'leave_request', 'name' => 'Leave Requests', 'code' => 'leave-requests'),
            array('id' => 13, 'module' => 'company', 'name' => 'Company Profile', 'code' => 'company'),
            array('id' => 14, 'module' => 'loans', 'name' => 'Loans', 'code' => 'loans'),
            array('id' => 15, 'module' => 'letters', 'name' => 'Letters', 'code' => 'letters'),
            array('id' => 16, 'module' => 'inventories', 'name' => 'Inventory', 'code' => 'inventories'),

        );
        Permission::insert($permissions);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
