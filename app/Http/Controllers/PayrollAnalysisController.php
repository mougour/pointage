<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Payroll;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PayrollAnalysisController extends Controller
{
    /**
     * Display the payroll analysis page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            // Get the current month and year
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
            
            // Get departments from employees table with safety check
            $departments = Employee::distinct()->pluck('department')->filter()->toArray();
            
            // Calculate payroll based on hours worked
            $payrollData = $this->calculatePayrollFromHours($currentMonth, $currentYear);
            
            // Get department costs with safety check
            $departmentCosts = $payrollData['department_costs'] ?? [];
            
            // Get monthly trends with safety check
            $monthlyTrends = $payrollData['monthly_trends'] ?? [];
            
            // Get payroll components with safety checks
            $payrollComponents = [
                'Base Salary' => max(0, $payrollData['base_salary'] ?? 0),
                'Overtime' => max(0, $payrollData['overtime'] ?? 0),
                'Bonuses' => max(0, $payrollData['bonuses'] ?? 0),
                'Benefits' => max(0, $payrollData['benefits'] ?? 0),
                'Taxes' => -max(0, $payrollData['taxes'] ?? 0),
                'Deductions' => -max(0, $payrollData['deductions'] ?? 0)
            ];
            
            // Get payroll summary with safety check
            $payrollSummary = $payrollData['payroll_summary'] ?? [];
            
            // Get top earners with safety check
            $topEarners = $payrollData['top_earners'] ?? [];
            
            // Get recent payments with safety check
            $recentPayments = $payrollData['recent_payments'] ?? [];
            
            // Get taxation data with safety checks
            $taxationData = [
                'Income Tax' => max(0, $payrollData['taxes'] ?? 0),
                'Social Security' => 0,
                'Medicare' => 0,
                'State Tax' => 0,
                'Local Tax' => 0,
            ];
            
            // Get year-to-date comparison with safety check
            $ytdComparison = $payrollData['ytd_comparison'] ?? [];
            
            return view('payroll.analysis', compact(
                'currentMonth',
                'currentYear',
                'departments',
                'departmentCosts',
                'monthlyTrends',
                'payrollComponents',
                'payrollSummary',
                'topEarners',
                'recentPayments',
                'taxationData',
                'ytdComparison'
            ));
        } catch (\Exception $e) {
            // Log the error and return a safe default view
            \Log::error('Payroll Analysis Error: ' . $e->getMessage());
            return view('payroll.analysis', [
                'currentMonth' => Carbon::now()->month,
                'currentYear' => Carbon::now()->year,
                'departments' => [],
                'departmentCosts' => [],
                'monthlyTrends' => [],
                'payrollComponents' => [],
                'payrollSummary' => [],
                'topEarners' => [],
                'recentPayments' => [],
                'taxationData' => [],
                'ytdComparison' => []
            ]);
        }
    }

    /**
     * Calculate payroll based on hours worked
     */
    private function calculatePayrollFromHours($month, $year)
    {
        try {
            // Get all employees with their attendance records for the month
            $employees = Employee::with(['attendances' => function($query) use ($month, $year) {
                $query->whereMonth('date', $month)
                      ->whereYear('date', $year);
            }])->get();

            $totalEmployees = $employees->count();
            $hourlyRate = max(0, 25); // Base hourly rate with safety check
            $overtimeRate = max(0, $hourlyRate * 1.5); // Overtime rate with safety check
            $standardHours = max(1, 160); // Standard hours with safety check

            // Initialize payroll data with default values
            $payrollData = $this->initializePayrollData();

            // If there are no employees, return the empty payroll data
            if ($totalEmployees === 0) {
                return $payrollData;
            }

            // Calculate payroll for each employee
            $employeesWithPayroll = 0;
            foreach ($employees as $employee) {
                $monthlyHours = $this->calculateMonthlyHours($employee);
                
                // Only calculate payroll if there are hours worked
                if ($monthlyHours > 0) {
                    $employeesWithPayroll++;
                    $employeePayroll = $this->calculateEmployeePayroll($monthlyHours, $standardHours, $hourlyRate, $overtimeRate);
                    $this->updatePayrollData($payrollData, $employee, $employeePayroll);
                }
            }

            // Update final calculations
            $this->updateFinalCalculations($payrollData, $employeesWithPayroll);

            // Calculate trends and comparisons
            $this->calculateTrendsAndComparisons($payrollData, $year, $hourlyRate, $overtimeRate, $standardHours);

            return $payrollData;
        } catch (\Exception $e) {
            \Log::error('Payroll Calculation Error: ' . $e->getMessage());
            return $this->initializePayrollData();
        }
    }

    private function initializePayrollData()
    {
        return [
            'base_salary' => 0,
            'overtime' => 0,
            'bonuses' => 0,
            'benefits' => 0,
            'taxes' => 0,
            'deductions' => 0,
            'total_payroll' => 0,
            'overtime_hours' => 0,
            'overtime_cost' => 0,
            'bonus_payments' => 0,
            'tax_deductions' => 0,
            'net_payroll' => 0,
            'department_costs' => [],
            'monthly_trends' => [],
            'top_earners' => [],
            'recent_payments' => [],
            'total_payroll_2025' => 0,
            'ytd_comparison' => [],
            'total_employees' => 0,
            'highest_paid' => 0,
            'lowest_paid' => PHP_FLOAT_MAX,
            'average_salary' => 0,
            'payroll_summary' => [
                'total_employees' => 0,
                'total_payroll' => 0,
                'average_salary' => 0,
                'highest_paid' => 0,
                'lowest_paid' => 0,
                'overtime_hours' => 0,
                'overtime_cost' => 0,
                'bonus_payments' => 0,
                'tax_deductions' => 0,
                'net_payroll' => 0
            ]
        ];
    }

    private function calculateMonthlyHours($employee)
    {
        try {
            $monthlyHours = 0;
            foreach ($employee->attendances as $attendance) {
                if ($attendance->hours_worked > 0) {
                    $monthlyHours += max(0, $attendance->hours_worked);
                }
            }
            return max(0, $monthlyHours);
        } catch (\Exception $e) {
            \Log::error('Monthly Hours Calculation Error: ' . $e->getMessage());
            return 0;
        }
    }

    private function calculateEmployeePayroll($monthlyHours, $standardHours, $hourlyRate, $overtimeRate)
    {
        // Ensure we have valid values and prevent division by zero
        $monthlyHours = max(0, $monthlyHours);
        $standardHours = max(1, $standardHours); // Ensure standard hours is at least 1
        $hourlyRate = max(0, $hourlyRate);
        $overtimeRate = max(0, $overtimeRate);

        // Prevent division by zero in overtime calculation
        $overtimeHours = ($standardHours > 0) ? max(0, $monthlyHours - $standardHours) : 0;
        $regularHours = ($standardHours > 0) ? min($monthlyHours, $standardHours) : 0;

        // Calculate base salary with safety check
        $baseSalary = ($hourlyRate > 0) ? ($regularHours * $hourlyRate) : 0;
        
        // Calculate overtime with safety check
        $overtimePay = ($overtimeRate > 0) ? ($overtimeHours * $overtimeRate) : 0;
        
        // Calculate attendance rate safely with multiple conditions
        $attendanceRate = 0;
        if ($standardHours > 0 && $monthlyHours > 0 && $hourlyRate > 0) {
            $attendanceRate = min(1, $monthlyHours / $standardHours);
        }
        
        // Calculate bonus based on attendance with safety check
        $bonus = 0;
        if ($attendanceRate >= 0.95 && $baseSalary > 0) {
            $bonus = $baseSalary * 0.05;
        }
        
        // Calculate benefits with safety check
        $benefits = ($baseSalary > 0) ? ($baseSalary * 0.10) : 0;
        
        // Calculate total earnings with safety check
        $totalEarnings = $baseSalary + $overtimePay + $bonus + $benefits;
        
        // Calculate taxes with safety check
        $taxes = ($totalEarnings > 0) ? ($totalEarnings * 0.20) : 0;
        
        // Calculate deductions with safety check
        $deductions = ($baseSalary > 0) ? ($baseSalary * 0.05) : 0;
        
        // Calculate net salary with safety check
        $netSalary = max(0, $totalEarnings - $taxes - $deductions);

        return [
            'base_salary' => $baseSalary,
            'overtime' => $overtimePay,
            'bonus' => $bonus,
            'benefits' => $benefits,
            'taxes' => $taxes,
            'deductions' => $deductions,
            'overtime_hours' => $overtimeHours,
            'total_earnings' => $totalEarnings,
            'net_salary' => $netSalary
        ];
    }

    private function updatePayrollData(&$payrollData, $employee, $employeePayroll)
    {
        try {
            // Update totals with safety checks
            $payrollData['base_salary'] += max(0, $employeePayroll['base_salary'] ?? 0);
            $payrollData['overtime'] += max(0, $employeePayroll['overtime'] ?? 0);
            $payrollData['bonuses'] += max(0, $employeePayroll['bonus'] ?? 0);
            $payrollData['benefits'] += max(0, $employeePayroll['benefits'] ?? 0);
            $payrollData['taxes'] += max(0, $employeePayroll['taxes'] ?? 0);
            $payrollData['deductions'] += max(0, $employeePayroll['deductions'] ?? 0);
            $payrollData['overtime_hours'] += max(0, $employeePayroll['overtime_hours'] ?? 0);
            $payrollData['overtime_cost'] += max(0, $employeePayroll['overtime'] ?? 0);
            $payrollData['bonus_payments'] += max(0, $employeePayroll['bonus'] ?? 0);
            $payrollData['tax_deductions'] += max(0, $employeePayroll['taxes'] ?? 0);
            $payrollData['net_payroll'] += max(0, $employeePayroll['net_salary'] ?? 0);
            $payrollData['total_payroll'] += max(0, $employeePayroll['total_earnings'] ?? 0);

            // Update department costs with safety check
            if (!isset($payrollData['department_costs'][$employee->department])) {
                $payrollData['department_costs'][$employee->department] = 0;
            }
            $payrollData['department_costs'][$employee->department] += max(0, $employeePayroll['net_salary'] ?? 0);

            // Update highest and lowest paid with safety checks
            $payrollData['highest_paid'] = max($payrollData['highest_paid'] ?? 0, $employeePayroll['net_salary'] ?? 0);
            $payrollData['lowest_paid'] = min($payrollData['lowest_paid'] ?? PHP_FLOAT_MAX, $employeePayroll['net_salary'] ?? 0);

            // Add to top earners with safety checks
            $payrollData['top_earners'][] = [
                'name' => $employee->fullName ?? 'Unknown',
                'position' => $employee->position ?? 'Unknown',
                'department' => $employee->department ?? 'Unknown',
                'salary' => max(0, $employeePayroll['net_salary'] ?? 0)
            ];

            // Add to recent payments with safety checks
            $payrollData['recent_payments'][] = [
                'employee' => $employee->fullName ?? 'Unknown',
                'date' => Carbon::now()->format('Y-m-d'),
                'amount' => max(0, $employeePayroll['net_salary'] ?? 0),
                'status' => 'Paid'
            ];
        } catch (\Exception $e) {
            \Log::error('Payroll Data Update Error: ' . $e->getMessage());
        }
    }

    private function updateFinalCalculations(&$payrollData, $employeesWithPayroll)
    {
        // Ensure employeesWithPayroll is at least 0
        $employeesWithPayroll = max(0, $employeesWithPayroll);

        // Sort top earners by salary with safety check
        if (!empty($payrollData['top_earners'])) {
            usort($payrollData['top_earners'], function($a, $b) {
                return ($b['salary'] ?? 0) <=> ($a['salary'] ?? 0);
            });
            $payrollData['top_earners'] = array_slice($payrollData['top_earners'], 0, 5);
        }

        // Update total employees and average salary with multiple safety checks
        $payrollData['total_employees'] = $employeesWithPayroll;
        
        // Calculate average salary with multiple safety checks
        $payrollData['average_salary'] = 0;
        if ($employeesWithPayroll > 0 && 
            isset($payrollData['net_payroll']) && 
            $payrollData['net_payroll'] > 0) {
            $payrollData['average_salary'] = $payrollData['net_payroll'] / $employeesWithPayroll;
        }
        
        // Reset lowest paid value if there are no employees with payroll
        if ($employeesWithPayroll === 0 || !isset($payrollData['lowest_paid']) || $payrollData['lowest_paid'] === PHP_FLOAT_MAX) {
            $payrollData['lowest_paid'] = 0;
        }

        // Update payroll summary with multiple safety checks
        $payrollData['payroll_summary'] = [
            'total_employees' => $payrollData['total_employees'],
            'total_payroll' => max(0, $payrollData['total_payroll'] ?? 0),
            'average_salary' => $payrollData['average_salary'],
            'highest_paid' => max(0, $payrollData['highest_paid'] ?? 0),
            'lowest_paid' => max(0, $payrollData['lowest_paid'] ?? 0),
            'overtime_hours' => max(0, $payrollData['overtime_hours'] ?? 0),
            'overtime_cost' => max(0, $payrollData['overtime_cost'] ?? 0),
            'bonus_payments' => max(0, $payrollData['bonus_payments'] ?? 0),
            'tax_deductions' => max(0, $payrollData['tax_deductions'] ?? 0),
            'net_payroll' => max(0, $payrollData['net_payroll'] ?? 0)
        ];
    }

    private function calculateTrendsAndComparisons(&$payrollData, $year, $hourlyRate, $overtimeRate, $standardHours)
    {
        // Ensure we have valid values with safety checks
        $hourlyRate = max(0, $hourlyRate);
        $overtimeRate = max(0, $overtimeRate);
        $standardHours = max(1, $standardHours);

        // Calculate monthly trends with safety checks
        for ($m = 1; $m <= 12; $m++) {
            $monthlyHours = $this->calculateMonthlyHoursForPeriod($m, $year);
            $monthlyPayroll = $this->calculateMonthlyPayroll($monthlyHours, $hourlyRate, $overtimeRate, $standardHours);
            $payrollData['monthly_trends'][Carbon::create($year, $m, 1)->format('F')] = max(0, $monthlyPayroll);
        }

        // Calculate total payroll for 2025 with safety check
        $payrollData['total_payroll_2025'] = max(0, array_sum($payrollData['monthly_trends'] ?? []));

        // Calculate year-to-date comparison with safety checks
        $previousYear = $year - 1;
        $previousYearPayroll = $this->calculatePreviousYearPayroll($previousYear, $hourlyRate, $overtimeRate, $standardHours);

        // Calculate percentage change with multiple safety checks
        $percentageChange = 0;
        if ($previousYearPayroll > 0 && 
            isset($payrollData['total_payroll_2025']) && 
            $payrollData['total_payroll_2025'] > 0) {
            $percentageChange = (($payrollData['total_payroll_2025'] - $previousYearPayroll) / $previousYearPayroll) * 100;
        }

        $payrollData['ytd_comparison'] = [
            'year' => $year,
            'total_payroll' => $payrollData['total_payroll_2025'],
            'previous_year' => $previousYear,
            'previous_payroll' => $previousYearPayroll,
            'percentage_change' => $percentageChange,
            'difference' => $payrollData['total_payroll_2025'] - $previousYearPayroll
        ];
    }

    private function calculateMonthlyHoursForPeriod($month, $year)
    {
        try {
            // Validate month and year
            $month = max(1, min(12, $month)); // Ensure month is between 1-12
            $year = max(2000, min(date('Y') + 1, $year)); // Ensure year is valid
            
            $monthlyHours = 0;
            $attendances = Attendance::whereMonth('date', $month)
                ->whereYear('date', $year)
                ->get();
            
            foreach ($attendances as $attendance) {
                if (isset($attendance->hours_worked) && $attendance->hours_worked > 0) {
                    $monthlyHours += max(0, $attendance->hours_worked);
                }
            }
            return max(0, $monthlyHours);
        } catch (\Exception $e) {
            \Log::error('Monthly Hours For Period Calculation Error: ' . $e->getMessage());
            return 0;
        }
    }

    private function calculateMonthlyPayroll($monthlyHours, $hourlyRate, $overtimeRate, $standardHours)
    {
        // Ensure we have valid values with safety checks
        $monthlyHours = max(0, $monthlyHours);
        $hourlyRate = max(0, $hourlyRate);
        $overtimeRate = max(0, $overtimeRate);
        $standardHours = max(1, $standardHours);

        // Calculate with safety checks
        $regularPay = ($hourlyRate > 0) ? ($monthlyHours * $hourlyRate) : 0;
        $overtimePay = ($overtimeRate > 0 && $standardHours > 0) ? 
            (max(0, $monthlyHours - $standardHours) * $overtimeRate) : 0;

        return $regularPay + $overtimePay;
    }

    private function calculatePreviousYearPayroll($previousYear, $hourlyRate, $overtimeRate, $standardHours)
    {
        try {
            // Ensure we have valid values with safety checks
            $previousYear = max(2000, min(date('Y'), $previousYear)); // Ensure year is valid
            $hourlyRate = max(0, $hourlyRate);
            $overtimeRate = max(0, $overtimeRate);
            $standardHours = max(1, $standardHours);
            
            $previousYearPayroll = 0;
            for ($m = 1; $m <= 12; $m++) {
                $monthlyHours = $this->calculateMonthlyHoursForPeriod($m, $previousYear);
                $monthlyPayroll = $this->calculateMonthlyPayroll($monthlyHours, $hourlyRate, $overtimeRate, $standardHours);
                $previousYearPayroll += max(0, $monthlyPayroll);
            }
            return max(0, $previousYearPayroll);
        } catch (\Exception $e) {
            \Log::error('Previous Year Payroll Calculation Error: ' . $e->getMessage());
            return 0;
        }
    }
} 