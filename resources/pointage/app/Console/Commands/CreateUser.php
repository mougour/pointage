<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Command
{
    protected $signature = 'user:create {name} {email} {password} {--admin : Whether the user is an admin}';
    protected $description = 'Create a new user';

    public function handle()
    {
        $user = User::create([
            'name' => $this->argument('name'),
            'email' => $this->argument('email'),
            'password' => Hash::make($this->argument('password')),
            'is_admin' => $this->option('admin') ? true : false,
        ]);

        $this->info("User created successfully!");
        $this->table(
            ['Name', 'Email', 'Admin'],
            [[$user->name, $user->email, $user->is_admin ? 'Yes' : 'No']]
        );
    }
} 