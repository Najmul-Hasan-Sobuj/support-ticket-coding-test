<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define permissions for admin
        $adminPermissions = [
            [
                'group_name' => 'Dashboard',
                'permissions' => [
                    'dashboard.view',
                ],
            ],
            [
                'group_name' => 'User',
                'permissions' => [
                    'admin.index',
                    'admin.create',
                    'admin.edit',
                    'admin.update',
                    'admin.delete',
                ],
            ],
            [
                'group_name' => 'Role',
                'permissions' => [
                    'role.index',
                    'role.create',
                    'role.edit',
                    'role.update',
                    'role.delete',
                ],
            ],
            [
                'group_name' => 'Ticket',
                'permissions' => [
                    'ticket.index',
                    'ticket.create',
                    'ticket.edit',
                    'ticket.update',
                    'ticket.delete',
                    'ticket.close',
                    'ticket.response.create',
                    'ticket.response.delete',
                ],
            ],
        ];

        // Create admin role and assign permissions
        $roleAdmin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        foreach ($adminPermissions as $group) {
            $permissionGroup = $group['group_name'];
            foreach ($group['permissions'] as $permissionName) {
                $permission = Permission::firstOrCreate([
                    'name' => $permissionName,
                    'group_name' => $permissionGroup,
                    'guard_name' => 'web',
                ]);
                $roleAdmin->givePermissionTo($permission);
            }
        }

        // Define permissions for customer
        $customerPermissions = [
            [
                'group_name' => 'Ticket',
                'permissions' => [
                    'ticket.index',  // View their tickets
                    'ticket.create', // Create a ticket
                    'ticket.edit',   // Edit their ticket
                    'ticket.close',  // Close their ticket
                ],
            ],
        ];

        // Create customer role and assign permissions
        $roleCustomer = Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);
        foreach ($customerPermissions as $group) {
            $permissionGroup = $group['group_name'];
            foreach ($group['permissions'] as $permissionName) {
                $permission = Permission::firstOrCreate([
                    'name' => $permissionName,
                    'group_name' => $permissionGroup,
                    'guard_name' => 'web',
                ]);
                $roleCustomer->givePermissionTo($permission);
            }
        }
    }
}
