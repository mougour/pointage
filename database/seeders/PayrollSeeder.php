<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Payroll;
use Carbon\Carbon;

class PayrollSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all employees
        $employees = Employee::all();
        
        // Get current month and year
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        // Base salary ranges by department
        $departmentBaseSalaries = [
            'IT & Technical' => ['min' => 3500, 'max' => 7200],
            'Human Resources' => ['min' => 2800, 'max' => 6200],
            'Marketing & Sales' => ['min' => 3000, 'max' => 6500],
            'Finance & Accounting' => ['min' => 3200, 'max' => 6800],
            'Operations & Logistics' => ['min' => 2500, 'max' => 5900]
        ];
        
        foreach ($employees as $employee) {
            // Get base salary range for department
            $salaryRange = $departmentBaseSalaries[$employee->department] ?? ['min' => 2500, 'max' => 5000];
            
            // Generate random base salary within range
            $baseSalary = rand($salaryRange['min'], $salaryRange['max']);
            
            // Generate random overtime hours (0-20)
            $overtimeHours = rand(0, 20);
            
            // Overtime rate (1.5x base salary per hour)
            $overtimeRate = $baseSalary / 160 * 1.5;
            
            // Random bonus (0-500)
            $bonus = rand(0, 500);
            
            // Random benefits (200-800)
            $benefits = rand(200, 800);
            
            // Calculate taxes (20% of base salary + overtime + bonus)
            $taxes = ($baseSalary + ($overtimeHours * $overtimeRate) + $bonus) * 0.20;
            
            // Random deductions (100-400)
            $deductions = rand(100, 400);
            
            // Calculate net salary
            $netSalary = $baseSalary + ($overtimeHours * $overtimeRate) + $bonus + $benefits - $taxes - $deductions;
            
            // Create payroll record
            Payroll::create([
                'cin' => $employee->cin,
                'base_salary' => $baseSalary,
                'overtime_hours' => $overtimeHours,
                'overtime_rate' => $overtimeRate,
                'bonus' => $bonus,
                'benefits' => $benefits,
                'taxes' => $taxes,
                'deductions' => $deductions,
                'net_salary' => $netSalary,
                'pay_date' => Carbon::create($currentYear, $currentMonth, 1),
                'payment_status' => 'paid',
                'notes' => 'Monthly payroll for ' . Carbon::create($currentYear, $currentMonth, 1)->format('F Y')
            ]);
        }
    }
}
