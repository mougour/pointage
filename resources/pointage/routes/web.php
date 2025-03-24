<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Auth routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard and Employee routes
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
Route::get('/employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show');
Route::get('/employees/{employee}/calculate-hours', [EmployeeController::class, 'calculateHours'])->name('employees.calculate-hours');
Route::post('/employees/{employee}/checkin', [EmployeeController::class, 'checkIn'])->name('employees.checkin');
Route::post('/employees/{employee}/checkout', [EmployeeController::class, 'checkOut'])->name('employees.checkout');
