<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        Employee::create([
            'cin' => 'EE123456',
            'fullName' => 'John Doe',
            'email' => 'john@example.com',
            'position' => 'Developer',
            'department' => 'IT',
            'isActive' => true
        ]);

        Employee::create([
            'cin' => 'EE789012',
            'fullName' => 'Jane Smith',
            'email' => 'jane@example.com',
            'position' => 'Designer',
            'department' => 'Design',
            'isActive' => true
        ]);
    }
} 