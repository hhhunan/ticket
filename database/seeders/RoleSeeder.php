<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'create manager']);
        Permission::create(['name' => 'view manager']);
        Permission::create(['name' => 'edit manager']);
        Permission::create(['name' => 'delete manager']);
        Permission::create(['name' => 'view ticket']);
        Permission::create(['name' => 'edit ticket']);
        Permission::create(['name' => 'reply to ticket']);
        Permission::create(['name' => 'delete ticket']);
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $role = Role::create(['name' => 'manager']);
        $role->givePermissionTo(['edit ticket', 'view ticket', 'reply to ticket', 'delete ticket']);

        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());
    }
}
