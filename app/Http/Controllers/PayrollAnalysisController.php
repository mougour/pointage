<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Support\Carbon;

class PayrollAnalysisController extends Controller
{
    /**
     * Display the payroll analysis page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get the current month and year
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        // Mock data for demonstration purposes
        $departments = [
            'IT & Technical', 
            'Human Resources', 
            'Marketing & Sales', 
            'Finance & Accounting', 
            'Operations & Logistics'
        ];
        
        $departmentCosts = [
            'IT & Technical' => 42500.00,
            'Human Resources' => 28750.00,
            'Marketing & Sales' => 35600.00,
            'Finance & Accounting' => 38200.00,
            'Operations & Logistics' => 31400.00
        ];
        
        $monthlyTrends = [
            'January' => 168200.00,
            'February' => 172400.00,
            'March' => 176500.00,
            'April' => 179800.00,
            'May' => 183200.00,
            'June' => 185400.00,
            'July' => 187600.00,
            'August' => 190200.00,
            'September' => 192500.00,
            'October' => 195800.00,
            'November' => 198300.00,
            'December' => 203500.00,
        ];
        
        $payrollComponents = [
            'Base Salary' => 145600.00,
            'Overtime' => 12400.00,
            'Bonuses' => 18500.00,
            'Benefits' => 22300.00,
            'Taxes' => -35200.00,
            'Deductions' => -8700.00
        ];
        
        $payrollSummary = [
            'total_employees' => 78,
            'total_payroll' => 176450.00,
            'average_salary' => 2262.18,
            'highest_paid' => 7200.00,
            'lowest_paid' => 1200.00,
            'overtime_hours' => 230,
            'overtime_cost' => 12400.00,
            'bonus_payments' => 18500.00,
            'tax_deductions' => 35200.00,
            'net_payroll' => 154900.00,
        ];
        
        $topEarners = [
            ['name' => 'Sarah Johnson', 'position' => 'CTO', 'department' => 'IT & Technical', 'salary' => 7200.00],
            ['name' => 'Michael Chen', 'position' => 'Finance Director', 'department' => 'Finance & Accounting', 'salary' => 6800.00],
            ['name' => 'Emily Rodriguez', 'position' => 'Marketing Director', 'department' => 'Marketing & Sales', 'salary' => 6500.00],
            ['name' => 'David Kim', 'position' => 'HR Director', 'department' => 'Human Resources', 'salary' => 6200.00],
            ['name' => 'Lisa Patel', 'position' => 'Operations Manager', 'department' => 'Operations & Logistics', 'salary' => 5900.00],
        ];
        
        $recentPayments = [
            ['employee' => 'John Smith', 'date' => '2025-03-28', 'amount' => 2450.00, 'status' => 'Paid'],
            ['employee' => 'Anna Brown', 'date' => '2025-03-28', 'amount' => 2350.00, 'status' => 'Paid'],
            ['employee' => 'Mark Wilson', 'date' => '2025-03-28', 'amount' => 3100.00, 'status' => 'Paid'],
            ['employee' => 'Maria Garcia', 'date' => '2025-03-28', 'amount' => 2200.00, 'status' => 'Paid'],
            ['employee' => 'James Taylor', 'date' => '2025-03-28', 'amount' => 2650.00, 'status' => 'Paid'],
            ['employee' => 'Susan Martinez', 'date' => '2025-03-28', 'amount' => 2800.00, 'status' => 'Paid'],
            ['employee' => 'Robert Johnson', 'date' => '2025-03-28', 'amount' => 2550.00, 'status' => 'Paid'],
            ['employee' => 'Jennifer Lee', 'date' => '2025-03-28', 'amount' => 2250.00, 'status' => 'Paid'],
        ];
        
        $taxationData = [
            'Income Tax' => 28600.00,
            'Social Security' => 15200.00,
            'Medicare' => 8900.00,
            'State Tax' => 7500.00,
            'Local Tax' => 3000.00,
        ];
        
        $ytdComparison = [
            'year' => $currentYear,
            'total_payroll' => 176450.00,
            'previous_year' => $currentYear - 1,
            'previous_payroll' => 165800.00,
            'percentage_change' => 6.42,
            'difference' => 10650.00
        ];
        
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
    }
} 