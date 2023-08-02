<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roleAdmin = Role::create(['guard_name' => 'admin', 'name' => 'admin']);
        $roleUser = Role::create(['guard_name' => 'web', 'name' => 'user']);
        $permissionAllAdmin= Permission::create(['guard_name' => 'admin', 'name' => '*']);
        $permissionAllUser = Permission::create(['guard_name'=> 'web', 'name' => '*']);
        $roleAdmin->givePermissionTo($permissionAllAdmin);
        $roleUser->givePermissionTo($permissionAllUser);

        $admin = Admin::create([
            'name' => 'admin',
            'email' => 'george.melo7@gmail.com',
            'password' => Hash::make('secret'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $admin->assignRole($roleAdmin);
        $users = User::factory()->count(100)->has(Account::factory()->hasTransactions(10))->create();
        $users->each(function ($user) use ($roleUser) {
            $user->assignRole($roleUser);
        });
        $user = User::factory()->has(Account::factory()->hasTransactions(10))->create([
            'email' => 'george.melo7@gmail.com',
            'password' => Hash::make('secret')
        ]);
        $user->assignRole($roleUser);
    }
}
