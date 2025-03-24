<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    // Create Employee
    public function store(Request $request)
    {
        $request->validate([
            'cin' => 'required|unique:employees',
            'full_name' => 'required|string',
            'email' => 'required|email|unique:employees',
            'post' => 'required|string',
            'date_creation' => 'required|date',
        ]);

        // Mass assignment protection (Make sure $fillable is set in Employee model)
        $employee = Employee::create($request->only(['cin', 'full_name', 'email', 'post', 'date_creation']));

        return response()->json([
            'message' => 'Employee created successfully',
            'data' => $employee,
        ], 201); // Created status code
    }

    // Get All Employees
    public function index()
    {
        $employees = Employee::with(['attendances' => function($query) {
            $query->whereDate('check_in', Carbon::today());
        }])->get();

        return view('employees.index', compact('employees'));
    }

    // Update Employee
    public function update(Request $request, $id)
    {
        $request->validate([
            'cin' => 'required|unique:employees,cin,' . $id,
            'full_name' => 'required|string',
            'email' => 'required|email|unique:employees,email,' . $id,
            'post' => 'required|string',
            'date_creation' => 'required|date',
        ]);

        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'message' => 'Employee not found',
            ], 404); // Not Found status code
        }

        $employee->update($request->only(['cin', 'full_name', 'email', 'post', 'date_creation']));

        return response()->json([
            'message' => 'Employee updated successfully',
            'data' => $employee,
        ]);
    }

    // Delete Employee
    public function destroy($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'message' => 'Employee not found',
            ], 404); // Not Found status code
        }

        $employee->delete();

        return response()->json([
            'message' => 'Employee deleted successfully',
        ]);
    }

    public function show(Employee $employee)
    {
        $today = Carbon::today();
        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('check_in', $today)
            ->first();

        $recentActivity = Attendance::where('employee_id', $employee->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get salary data based on position and department
        $salaryData = $this->getSalaryData($employee->position, $employee->department);

        return view('employees.show', compact('employee', 'attendance', 'recentActivity', 'salaryData'));
    }

    private function getSalaryData($position, $department)
    {
        $salaryRates = [
            ["Department" => "Administration", "Position" => "Receptionist", "Average_Wage_per_Hour" => 17],
            ["Department" => "Administration", "Position" => "Office Manager", "Average_Wage_per_Hour" => 40],
            ["Department" => "Administration", "Position" => "Data Entry Clerk", "Average_Wage_per_Hour" => 22],
            ["Department" => "Administration", "Position" => "Executive Assistant", "Average_Wage_per_Hour" => 35],
            ["Department" => "Human Resources (HR)", "Position" => "HR Assistant", "Average_Wage_per_Hour" => 32],
            ["Department" => "Human Resources (HR)", "Position" => "HR Manager", "Average_Wage_per_Hour" => 55],
            ["Department" => "Human Resources (HR)", "Position" => "Training Coordinator", "Average_Wage_per_Hour" => 38],
            ["Department" => "Human Resources (HR)", "Position" => "Recruitment Officer", "Average_Wage_per_Hour" => 42],
            ["Department" => "Finance & Accounting", "Position" => "Accountant", "Average_Wage_per_Hour" => 40],
            ["Department" => "Finance & Accounting", "Position" => "Finance Manager", "Average_Wage_per_Hour" => 75],
            ["Department" => "Finance & Accounting", "Position" => "Payroll Specialist", "Average_Wage_per_Hour" => 45],
            ["Department" => "Finance & Accounting", "Position" => "Auditor", "Average_Wage_per_Hour" => 80],
            ["Department" => "Sales & Marketing", "Position" => "Sales Representative", "Average_Wage_per_Hour" => 28],
            ["Department" => "Sales & Marketing", "Position" => "Marketing Specialist", "Average_Wage_per_Hour" => 48],
            ["Department" => "Sales & Marketing", "Position" => "Social Media Manager", "Average_Wage_per_Hour" => 50],
            ["Department" => "Sales & Marketing", "Position" => "Public Relations (PR) Officer", "Average_Wage_per_Hour" => 55],
            ["Department" => "IT & Technical", "Position" => "IT Technician", "Average_Wage_per_Hour" => 45],
            ["Department" => "IT & Technical", "Position" => "Software Developer", "Average_Wage_per_Hour" => 85],
            ["Department" => "IT & Technical", "Position" => "System Administrator", "Average_Wage_per_Hour" => 50],
            ["Department" => "IT & Technical", "Position" => "Cybersecurity Specialist", "Average_Wage_per_Hour" => 90],
            ["Department" => "Operations & Logistics", "Position" => "Warehouse Supervisor", "Average_Wage_per_Hour" => 38],
            ["Department" => "Operations & Logistics", "Position" => "Operations Manager", "Average_Wage_per_Hour" => 70],
            ["Department" => "Operations & Logistics", "Position" => "Supply Chain Coordinator", "Average_Wage_per_Hour" => 45],
            ["Department" => "Operations & Logistics", "Position" => "Transport Manager", "Average_Wage_per_Hour" => 65],
            ["Department" => "Legal & Compliance", "Position" => "Legal Advisor", "Average_Wage_per_Hour" => 115],
            ["Department" => "Legal & Compliance", "Position" => "Compliance Officer", "Average_Wage_per_Hour" => 75],
            ["Department" => "Legal & Compliance", "Position" => "Contract Manager", "Average_Wage_per_Hour" => 70],
            ["Department" => "Legal & Compliance", "Position" => "Risk Analyst", "Average_Wage_per_Hour" => 85],
            ["Department" => "Customer Service", "Position" => "Call Center Agent", "Average_Wage_per_Hour" => 24],
            ["Department" => "Customer Service", "Position" => "Customer Support Manager", "Average_Wage_per_Hour" => 55],
            ["Department" => "Customer Service", "Position" => "Technical Support Specialist", "Average_Wage_per_Hour" => 35],
            ["Department" => "Customer Service", "Position" => "Client Relations Officer", "Average_Wage_per_Hour" => 45],
            ["Department" => "Engineering", "Position" => "Mechanical Engineer", "Average_Wage_per_Hour" => 75],
            ["Department" => "Engineering", "Position" => "Electrical Engineer", "Average_Wage_per_Hour" => 80],
            ["Department" => "Engineering", "Position" => "Civil Engineer", "Average_Wage_per_Hour" => 85],
            ["Department" => "Engineering", "Position" => "Industrial Engineer", "Average_Wage_per_Hour" => 70],
            ["Department" => "Construction", "Position" => "Site Supervisor", "Average_Wage_per_Hour" => 55],
            ["Department" => "Construction", "Position" => "Project Manager", "Average_Wage_per_Hour" => 90],
            ["Department" => "Construction", "Position" => "Architect", "Average_Wage_per_Hour" => 100],
            ["Department" => "Manufacturing", "Position" => "Production Manager", "Average_Wage_per_Hour" => 60],
            ["Department" => "Manufacturing", "Position" => "Quality Assurance Inspector", "Average_Wage_per_Hour" => 45],
            ["Department" => "Manufacturing", "Position" => "Factory Worker", "Average_Wage_per_Hour" => 25],
            ["Department" => "Security & Maintenance", "Position" => "Security Guard", "Average_Wage_per_Hour" => 22],
            ["Department" => "Security & Maintenance", "Position" => "Maintenance Technician", "Average_Wage_per_Hour" => 35],
            ["Department" => "Security & Maintenance", "Position" => "Facility Manager", "Average_Wage_per_Hour" => 50],
            ["Department" => "Education & Training", "Position" => "Teacher", "Average_Wage_per_Hour" => 40],
            ["Department" => "Education & Training", "Position" => "Trainer", "Average_Wage_per_Hour" => 50],
            ["Department" => "Education & Training", "Position" => "University Professor", "Average_Wage_per_Hour" => 100],
            ["Department" => "Healthcare", "Position" => "Nurse", "Average_Wage_per_Hour" => 50],
            ["Department" => "Healthcare", "Position" => "Doctor", "Average_Wage_per_Hour" => 150],
            ["Department" => "Healthcare", "Position" => "Pharmacist", "Average_Wage_per_Hour" => 120],
            ["Department" => "Senior Management", "Position" => "CEO / General Manager", "Average_Wage_per_Hour" => 200],
            ["Department" => "Senior Management", "Position" => "Chief Operating Officer (COO)", "Average_Wage_per_Hour" => 180],
            ["Department" => "Senior Management", "Position" => "Chief Financial Officer (CFO)", "Average_Wage_per_Hour" => 220]
        ];

        // Find matching salary rate
        $salaryRate = collect($salaryRates)->first(function ($rate) use ($position, $department) {
            return $rate['Position'] === $position && $rate['Department'] === $department;
        });

        if ($salaryRate) {
            return [
                'position' => $position,
                'department' => $department,
                'hourly_rate' => $salaryRate['Average_Wage_per_Hour'],
                'overtime_rate' => $salaryRate['Average_Wage_per_Hour'] * 1.5
            ];
        }

        // Default values if no match found
        return [
            'position' => $position,
            'department' => $department,
            'hourly_rate' => 25, // Default hourly rate
            'overtime_rate' => 37.5 // Default overtime rate (1.5x)
        ];
    }

    public function checkIn(Employee $employee)
    {
        $today = Carbon::today();

        // Check if already checked in today
        $existingAttendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('check_in', $today)
            ->first();

        if ($existingAttendance) {
            return back()->with('error', 'Employee has already checked in today.');
        }

        // Create new attendance record
        Attendance::create([
            'employee_id' => $employee->id,
            'check_in' => Carbon::now(),
            'status' => 'present'
        ]);

        return back()->with('success', 'Successfully checked in!');
    }

    public function checkOut(Employee $employee)
    {
        $today = Carbon::today();

        // Get today's attendance
        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('check_in', $today)
            ->first();

        if (!$attendance) {
            return back()->with('error', 'Employee must check in before checking out.');
        }

        if ($attendance->check_out) {
            return back()->with('error', 'Employee has already checked out today.');
        }

        // Update attendance record
        $attendance->update([
            'check_out' => Carbon::now()
        ]);

        return back()->with('success', 'Successfully checked out!');
    }

    public function calculateHours(Request $request, Employee $employee)
    {
        try {
            \Log::info('Calculating hours for employee: ' . $employee->id);
            \Log::info('Start date: ' . $request->query('start_date'));
            \Log::info('End date: ' . $request->query('end_date'));

            $startDate = Carbon::parse($request->query('start_date', Carbon::now()->startOfMonth()))->startOfDay();
            $endDate = Carbon::parse($request->query('end_date', Carbon::now()))->endOfDay();

            \Log::info('Parsed start date: ' . $startDate);
            \Log::info('Parsed end date: ' . $endDate);

            // Get all attendance records for the period with only needed fields
            $attendances = Attendance::select('check_in', 'check_out')
                ->where('employee_id', $employee->id)
                ->where('check_in', '>=', $startDate)
                ->where('check_in', '<=', $endDate)
                ->whereNotNull('check_out')
                ->orderBy('check_in', 'asc')
                ->get();

            \Log::info('Found ' . $attendances->count() . ' attendance records');

            $totalHours = 0;
            $totalDays = 0;
            $dailyBreakdown = [];

            foreach ($attendances as $attendance) {
                $hours = $attendance->check_in->diffInHours($attendance->check_out, false);
                $totalHours += $hours;
                $totalDays++;

                $dailyBreakdown[] = [
                    'date' => $attendance->check_in->format('M d, Y'),
                    'hours' => round($hours, 2)
                ];
            }

            $averageHours = $totalDays > 0 ? round($totalHours / $totalDays, 2) : 0;

            \Log::info('Calculation results:', [
                'total_hours' => round($totalHours, 2),
                'total_days' => $totalDays,
                'average_hours' => $averageHours,
                'daily_breakdown' => $dailyBreakdown
            ]);

            return response()->json([
                'total_hours' => round($totalHours, 2),
                'total_days' => $totalDays,
                'average_hours' => $averageHours,
                'daily_breakdown' => $dailyBreakdown
            ]);
        } catch (\Exception $e) {
            \Log::error('Error calculating hours: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return response()->json([
                'error' => 'Error calculating hours: ' . $e->getMessage()
            ], 500);
        }
    }
}
