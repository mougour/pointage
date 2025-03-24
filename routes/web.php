<?php

use App\Http\Controllers\LoginTestController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Public routes
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login-test');
})->name('home');

Route::post('/login', [LoginTestController::class, 'login'])->name('login');
Route::post('/logout', [LoginTestController::class, 'logout'])->name('logout');

// New route for COSTUMIZED PAYROLL
Route::get('/customized-payroll', function () {
    // Initialize variables to avoid undefined variable errors
    $totalHoursWorked = 0;
    $averageWage = 0;
    
    return view('customized-payroll', compact('totalHoursWorked', 'averageWage'));
})->name('customized.payroll');

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Employee resource routes
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/employees/{cin}', [EmployeeController::class, 'show'])->name('employees.show');
    Route::get('/employees/{cin}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{cin}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/employees/{cin}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
    
    // Additional employee routes
    Route::get('/employees/search', [EmployeeController::class, 'search'])->name('employees.search');
    Route::get('/employees/{cin}/get-data', [EmployeeController::class, 'getData'])->name('employees.getData');
    Route::post('/employees/{cin}/check-in', [EmployeeController::class, 'checkIn'])->name('employees.checkIn')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
    Route::post('/employees/{cin}/check-out', [EmployeeController::class, 'checkOut'])->name('employees.checkOut')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
    Route::post('/employees/{cin}/pause-start', [EmployeeController::class, 'pauseStart'])->name('employees.pauseStart')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
    Route::post('/employees/{cin}/pause-end', [EmployeeController::class, 'pauseEnd'])->name('employees.pauseEnd')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
    Route::get('/employees/{cin}/customized-payroll', [EmployeeController::class, 'showCustomizedPayroll'])->name('customized.payroll.employee');
    Route::get('/employees/{cin}/show-stats', [EmployeeController::class, 'showStats'])->name('show.stats');
    
    // Attendance log route
    Route::post('/attendance/log', [EmployeeController::class, 'logAttendance'])->name('attendance.log');
});

// Dashboard route
Route::get('/dashboard', function () {
    // Get statistics for the dashboard
    $today = now()->toDateString();

    // Count total employees
    $totalEmployees = \App\Models\Employee::count();
    
    // Count employees present today
    $presentToday = \App\Models\Attendance::where('date', $today)
        ->where('status', 'present')
        ->count();
    
    // Count employees absent today
    $absentToday = $totalEmployees - $presentToday;
    
    // Count employees currently checked in
    $currentlyCheckedIn = \App\Models\Attendance::where('date', $today)
        ->whereNotNull('check_in')
        ->whereNull('check_out')
        ->count();
    
    // Count employees on pause
    $onPause = \App\Models\Attendance::where('date', $today)
        ->whereNotNull('check_in')
        ->whereNull('check_out')
        ->whereHas('pauseTimes', function($query) {
            $query->whereNotNull('start_time')
                  ->whereNull('end_time');
        })
        ->count();
    
    // Calculate average hours worked today
    $avgHoursWorked = \App\Models\Attendance::where('date', $today)
        ->where('status', 'present')
        ->whereNotNull('check_out')
        ->whereNotNull('check_in')
        ->get()
        ->map(function($attendance) {
            $checkIn = new \DateTime($attendance->check_in);
            $checkOut = new \DateTime($attendance->check_out);
            $diff = $checkIn->diff($checkOut);
            // Calculate hours as decimal
            return $diff->h + ($diff->i / 60) + ($diff->s / 3600);
        })
        ->avg() ?? 0;
    
    // Get department distribution
    $departmentStats = \App\Models\Employee::select('department', \DB::raw('count(*) as count'))
        ->groupBy('department')
        ->get()
        ->pluck('count', 'department')
        ->toArray();
    
    return view('dashboard', compact(
        'totalEmployees', 
        'presentToday', 
        'absentToday', 
        'currentlyCheckedIn', 
        'onPause', 
        'avgHoursWorked',
        'departmentStats'
    ));
})->middleware(['auth'])->name('dashboard');
