<?php 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\LoginController;

// Mock user data for testing
Route::get('/user', function (Request $request) {
    return response()->json([
        'id' => 1,
        'name' => 'Test User',
        'email' => 'test@example.com',
        'role' => 'admin'
    ]);
});

// Public routes
Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected routes - temporarily public for testing
Route::group(function () {
    Route::get('/user', function (Request $request) {
        return response()->json([
            'id' => 1,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'admin'
        ]);
    });
    Route::post('/logout', [LoginController::class, 'logout']);
});
