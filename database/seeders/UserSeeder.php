<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Akun default untuk development/demo. Butuh RolePermissionSeeder
     * sudah jalan duluan (roles tabel harus terisi).
     */
    public function run(): void
    {
        $adminRoleId = Role::where('slug', 'admin')->value('id');
        $customerRoleId = Role::where('slug', 'customer')->value('id');

        User::updateOrCreate(
            ['email' => 'admin@tapgo.travel'],
            [
                'name' => 'Admin TAPGO',
                'password' => Hash::make('password'),
                'role_id' => $adminRoleId,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'customer@tapgo.travel'],
            [
                'name' => 'Customer Demo',
                'password' => Hash::make('password'),
                'role_id' => $customerRoleId,
                'email_verified_at' => now(),
            ]
        );
    }
}
