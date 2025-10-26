<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. reset cached roles and permissions
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // 2. create permissions
        $manageProducts = Permission::create(['name' => 'manage-products']);
        $manageCategories = Permission::create(['name' => 'manage-categories']);
        $manageOrders = Permission::create(['name' => 'manage-orders']);
        $placeOrders = Permission::create(['name' => 'place-orders']);

        // 3. create roles
        $adminRole = Role::create(['name' => 'admin']);
        $customerRole = Role::create(['name' => 'customer']);

        // 4. assign all permissions to admin
        $adminRole->givePermissionTo([
            $manageProducts,
            $manageCategories,
            $manageOrders,
            $placeOrders
        ]);

        // 5. assign place orders to customer
        $customerRole->givePermissionTo($placeOrders);
    }
}
