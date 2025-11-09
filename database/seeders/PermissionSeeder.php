<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',

            'view_roles',
            'create_roles',
            'edit_roles',
            'delete_roles',

            'view_categories',
            'create_categories',
            'edit_categories',
            'delete_categories',

            'view_products',
            'create_products',
            'edit_products',
            'delete_products',

            'view_orders',
            'edit_orders',
            'delete_orders',

            'view reports',
            'manage settings',

            // Customer specific permissions
            'customer_view_products',
            'customer_view_categories',
            'customer_create_orders',
            'customer_view_orders'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $editorRole = Role::firstOrCreate(['name' => 'editor']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);

        $adminRole->givePermissionTo($permissions);

        $editorPermissions = [
            'view_categories',
            'create_categories',
            'edit_categories',
            'view_products',
            'create_products',
            'edit_products',
            'view_orders',
            'edit_orders'
        ];
        $editorRole->givePermissionTo($editorPermissions);

        $customerPermissions = [
            'customer_view_products',
            'customer_view_categories',
            'customer_create_orders',
            'customer_view_orders'
        ];
        $customerRole->givePermissionTo($customerPermissions);

        $this->command->info('Permissions and roles have been seeded successfully.');
    }
}
