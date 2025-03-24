<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LoginTest;
use Illuminate\Support\Facades\Hash;

class LoginTestSeeder extends Seeder
{
    public function run(): void
    {
        LoginTest::create([
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);
    }
} 