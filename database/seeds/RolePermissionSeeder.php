<?php

namespace Database\Seeders;

use App\RolePermission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('role_permissions')->truncate();

        // super admin
        $superAdminPermissions = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16];
        $superAdminRolePermissions = [];
        foreach ($superAdminPermissions as $superAdminPermission) {
            $superAdminRolePermissions[] = [
                "permission_id" => $superAdminPermission,
                "role_id" => 1,
                "action_id" => 1
            ];
            $superAdminRolePermissions[] = [
                "permission_id" => $superAdminPermission,
                "role_id" => 1,
                "action_id" => 2
            ];
        }
        RolePermission::insert($superAdminRolePermissions);

        // admin
        $adminPermissions = [1, 2, 3, 4, 5, 6, 7, 8];
        $adminRolePermissions = [];
        foreach ($adminPermissions as $adminPermission) {
            $adminRolePermissions[] = [
                "permission_id" => $adminPermission,
                "role_id" => 2,
                "action_id" => 1
            ];
            $adminRolePermissions[] = [
                "permission_id" => $adminPermission,
                "role_id" => 2,
                "action_id" => 2
            ];
        }
        RolePermission::insert($adminRolePermissions);

        // hr
        $hrPermissions = [1, 2, 3, 4, 5];
        $hrRolePermissions = [];
        foreach ($hrPermissions as $hrPermission) {
            $hrRolePermissions[] = [
                "permission_id" => $hrPermission,
                "role_id" => 3,
                "action_id" => 1
            ];
            $hrRolePermissions[] = [
                "permission_id" => $hrPermission,
                "role_id" => 3,
                "action_id" => 2
            ];
        }
        RolePermission::insert($hrRolePermissions);

        // accountant
        $hrPermissions = [1, 2, 3, 4, 5, 7, 8];
        $hrRolePermissions = [];
        foreach ($hrPermissions as $hrPermission) {
            $hrRolePermissions[] = [
                "permission_id" => $hrPermission,
                "role_id" => 4,
                "action_id" => 1
            ];
            $hrRolePermissions[] = [
                "permission_id" => $hrPermission,
                "role_id" => 4,
                "action_id" => 2
            ];
        }
        RolePermission::insert($hrRolePermissions);

        // manager
        $managerPermissions = [1, 2];
        $managerRolePermissions = [];
        foreach ($managerPermissions as $managerPermission) {
            $managerRolePermissions[] = [
                "permission_id" => $managerPermission,
                "role_id" => 5,
                "action_id" => 1
            ];
        }
        RolePermission::insert($managerRolePermissions);
    }
}
