<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Seed the users table with TFA sample records and Breeze auth credentials.
     */
    public function run(): void
    {
        $passwordHash = Hash::make('password');
        $now = now();

        $users = [
            [
                'username' => 'admin.reyes',
                'full_name' => 'Carlos Reyes',
                'email' => 'admin.reyes@example.com',
                'email_verified_at' => $now,
                'password' => $passwordHash,
                'role' => 'Store Administrator',
                'remember_token' => Str::random(10),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'username' => 'mgr.castro',
                'full_name' => 'Beatriz Castro',
                'email' => 'mgr.castro@example.com',
                'email_verified_at' => $now,
                'password' => $passwordHash,
                'role' => 'Shift Supervisor',
                'remember_token' => Str::random(10),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'username' => 'cashier.valdez',
                'full_name' => 'Daniel Valdez',
                'email' => 'cashier.valdez@example.com',
                'email_verified_at' => $now,
                'password' => $passwordHash,
                'role' => 'Cashier',
                'remember_token' => Str::random(10),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'username' => 'cashier.santos',
                'full_name' => 'Camille Santos',
                'email' => 'cashier.santos@example.com',
                'email_verified_at' => $now,
                'password' => $passwordHash,
                'role' => 'Cashier',
                'remember_token' => Str::random(10),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'username' => 'inv.navarro',
                'full_name' => 'Leo Navarro',
                'email' => 'inv.navarro@example.com',
                'email_verified_at' => $now,
                'password' => $passwordHash,
                'role' => 'Inventory Clerk',
                'remember_token' => Str::random(10),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('users')->insertOrIgnore($users);
    }
}
