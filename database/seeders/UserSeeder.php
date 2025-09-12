<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'is_admin' => User::IS_ADMIN,
            'email' => 'admin@dev.com',
            'password' => bcrypt('admin@dev.com'),
            'phone' => '1234567890',
            
        ]);
        User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'user@dev.com',
            'password' => bcrypt('user@dev.com'),
            'phone' => '12345678909',
        ]);
    }
}
