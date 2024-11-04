<?php

namespace Database\Seeders\UserSeeder;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample data for users
        $users = [
            [
                'name' => 'Yazid Kurnia Ramadhan',
                'email' => 'yazid@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'), // Use a hashed password
                'roleid' => 1,
                'employeid' => 'EMP001',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Roni Arnanda',
                'email' => 'roni@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'), // Use a hashed password
                'roleid' => 1,
                'employeid' => 'EMP002',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more users as needed
        ];

        // Insert users into the database
        DB::table('users')->insert($users);

        // Sample data for password_reset_tokens
        $passwordResetTokens = [
            [
                'email' => 'yazid@gmail.com',
                'token' => Str::random(60),
                'created_at' => now(),
            ],
            [
                'email' => 'roni@gmail.com',
                'token' => Str::random(60),
                'created_at' => now(),
            ],
            // Add more tokens as needed
        ];

        // Insert password reset tokens into the database
        DB::table('password_reset_tokens')->insert($passwordResetTokens);

        // Sample data for sessions
        $sessions = [
            [
                'id' => Str::random(40),
                'user_id' => 1,
                'ip_address' => '192.168.1.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3',
                'payload' => 'session_payload_data',
                'last_activity' => time(),
            ],
            [
                'id' => Str::random(40),
                'user_id' => 2,
                'ip_address' => '192.168.1.2',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3',
                'payload' => 'session_payload_data',
                'last_activity' => time(),
            ],
            // Add more sessions as needed
        ];

        // Insert sessions into the database
        DB::table('sessions')->insert($sessions);
    }
}