<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Role\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\RoleAccesses\RoleAccessces;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        # seeder table user
        User::factory()->create(
            [
                'name'              => 'Yazid Kurnia Ramadhan',
                'email'             => 'yazid@gmail.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('password123'), // Use a hashed password
                'roleid'            => 1,
                'employeid'         => 1,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            );

        // Role::factory()->create(
        //     [
        //         [
        //             'role_name' => 'spadmin',
        //         ],
        //     ]
        // );

        // RoleAccessces::factory()->create(
        //     [
        //         [
        //             'roleid'   => 1,
        //             'accessid' => 1,
        //         ],
        //     ]
        // );

        // Accesses::factory()->create([
        //     [
        //         'access_name'      => 'Admin'
        //     ],
        // ]);
    }
}
