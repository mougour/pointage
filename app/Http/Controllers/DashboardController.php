<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get employee stats
        $totalEmployees = Employee::count();
        
        // Calculate present and absent counts
        $today = now()->format('Y-m-d');
        $presentToday = Attendance::whereDate('check_in', $today)->count();
        $absentToday = $totalEmployees - $presentToday;
        
        // Calculate average hours worked
        $avgHoursWorked = Attendance::whereNotNull('check_out')
            ->whereDate('check_in', '>=', now()->subDays(30))
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, check_in, check_out)) as avg_hours')
            ->first()
            ->avg_hours ?? 0;
            
        // Get department statistics
        $departmentStats = Employee::selectRaw('department, COUNT(*) as count')
            ->groupBy('department')
            ->pluck('count', 'department')
            ->toArray();
        
        // Fetch recent attendance records (last 5)
        $recentActivities = Attendance::with('employee')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get top performers (employees with most hours worked recently)
        $topPerformers = Attendance::select('employee_id')
            ->selectRaw('TIMESTAMPDIFF(HOUR, check_in, IFNULL(check_out, NOW())) as hours_worked')
            ->with('employee:id,name,position,department')
            ->whereNotNull('check_in')
            ->whereDate('check_in', '>=', now()->subDays(7)) // Consider last 7 days
            ->orderBy('hours_worked', 'desc')
            ->limit(3)
            ->get();

        return view('dashboard', [
            'totalEmployees' => $totalEmployees,
            'presentToday' => $presentToday,
            'absentToday' => $absentToday,
            'avgHoursWorked' => $avgHoursWorked,
            'departmentStats' => $departmentStats,
            'recentActivities' => $recentActivities,
            'topPerformers' => $topPerformers
        ]);
    }
} 