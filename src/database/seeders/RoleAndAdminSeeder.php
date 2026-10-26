<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleAndAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Spatie roles if needed
        foreach (UserRole::cases() as $role) {
            Role::firstOrCreate(['name' => $role->value, 'guard_name' => 'web']);
        }

        // 1. Akun Admin FixHome Plus
        $admin = User::firstOrCreate(
            ['email' => 'admin@fixhome.test'],
            [
                'name' => 'Admin FixHome Plus',
                'password' => Hash::make('password'),
                'role' => UserRole::ADMIN,
                'phone' => '081234567890',
                'status' => 'aktif',
            ]
        );
        $admin->syncRoles([UserRole::ADMIN->value]);

        // Akun Pelanggan Demo
        User::firstOrCreate(
            ['email' => 'pelanggan@fixhome.test'],
            [
                'name' => 'Rina Pelanggan',
                'password' => Hash::make('password'),
                'role' => UserRole::PELANGGAN,
                'phone' => '081298765432',
                'status' => 'aktif',
            ]
        );
    }
}
