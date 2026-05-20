<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Enums\Permission;
use App\Enums\UserRole;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission as SpatiePermission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create all permissions
        foreach (Permission::values() as $permission) {
            SpatiePermission::firstOrCreate(['name' => $permission]);
        }

        // 2. Create roles and assign permissions
        $adminRole = Role::firstOrCreate(['name' => UserRole::ADMIN->value]);
        $adminRole->syncPermissions(Permission::adminPermissions());

        $customerRole = Role::firstOrCreate(['name' => UserRole::CUSTOMER->value]);
        $customerRole->syncPermissions(Permission::customerPermissions());

        // 3. Assign admin role to admin user
        $admin = User::where('email', 'admin@example.com')->first();
        if ($admin) {
            $admin->assignRole(UserRole::ADMIN->value);
        }

        // 4. Assign customer role to all other users
        User::where('email', '!=', 'admin@example.com')
            ->get()
            ->each(fn ($user) => $user->assignRole(UserRole::CUSTOMER->value));

        $this->command->info('Roles and Permissions seeded successfully!');
    }
}
