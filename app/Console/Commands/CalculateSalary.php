<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendance;

class CalculateSalary extends Command
{
    protected $signature = 'salary:calculate';
    protected $description = 'Calculate salary based on attendance and average wage';

    public function handle()
    {
        $averageWages = [
            "Receptionist" => 17,
            "Office Manager" => 40,
            "Data Entry Clerk" => 22,
            "Executive Assistant" => 35,
            "HR Assistant" => 32,
            "HR Manager" => 55,
            "Training Coordinator" => 38,
            "Recruitment Officer" => 42,
            "Accountant" => 40,
            "Finance Manager" => 75,
            "Payroll Specialist" => 45,
            "Auditor" => 80,
            "Sales Representative" => 28,
            "Marketing Specialist" => 48,
            "Social Media Manager" => 50,
            "Public Relations (PR) Officer" => 55,
            "IT Technician" => 45,
            "Software Developer" => 85,
            "System Administrator" => 50,
            "Cybersecurity Specialist" => 90,
            "Warehouse Supervisor" => 38,
            "Operations Manager" => 70,
            "Supply Chain Coordinator" => 45,
            "Transport Manager" => 65,
            "Legal Advisor" => 115,
            "Compliance Officer" => 75,
            "Contract Manager" => 70,
            "Risk Analyst" => 85,
            "Call Center Agent" => 24,
            "Customer Support Manager" => 55,
            "Technical Support Specialist" => 35,
            "Client Relations Officer" => 45,
            "Mechanical Engineer" => 75,
            "Electrical Engineer" => 80,
            "Civil Engineer" => 85,
            "Industrial Engineer" => 70,
            "Site Supervisor" => 55,
            "Project Manager" => 90,
            "Architect" => 100,
            "Production Manager" => 60,
            "Quality Assurance Inspector" => 45,
            "Factory Worker" => 25,
            "Security Guard" => 22,
            "Maintenance Technician" => 35,
            "Facility Manager" => 50,
            "Teacher" => 40,
            "Trainer" => 50,
            "University Professor" => 100,
            "Nurse" => 50,
            "Doctor" => 150,
            "Pharmacist" => 120,
            "CEO / General Manager" => 200,
            "Chief Operating Officer (COO)" => 180,
            "Chief Financial Officer (CFO)" => 220,
        ];

        $attendances = Attendance::all();

        foreach ($attendances as $attendance) {
            // Assuming 'cin' corresponds to the employee's position
            // You may need to adjust this logic based on your actual data structure
            $position = $this->getPositionByCin($attendance->cin); // Implement this method to retrieve position
            $averageWage = $averageWages[$position] ?? 0;
            $hoursWorked = 8; // Assuming 8 hours worked per day
            $salary = $averageWage * $hoursWorked;

            $this->info("Payroll Statement - CIN: {$attendance->cin}, Position: {$position}, Hours Worked: {$hoursWorked}, Salary: $salary");

        }
    }

    private function getPositionByCin($cin)
    {
        // Implement logic to retrieve the position based on CIN
        // This is a placeholder function
        return "Receptionist"; // Example return value
    }
}
