<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login to access the dashboard.');
            }

            $today = Carbon::today();

            // Get today's attendance
            $attendance = Attendance::where('user_id', $user->id)
                ->whereDate('check_in', $today)
                ->first();

            // Get last check-in and check-out times
            $lastCheckIn = $attendance ? $attendance->check_in : null;
            $lastCheckOut = $attendance ? $attendance->check_out : null;

            // Calculate today's hours
            $todayHours = 0;
            if ($lastCheckIn && $lastCheckOut) {
                $todayHours = $lastCheckOut->diffInHours($lastCheckIn, true);
            }

            // Get recent activity
            $recentActivity = Attendance::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
                ->map(function ($record) {
                    return (object)[
                        'type' => $record->check_out ? 'checkout' : 'checkin',
                        'created_at' => $record->check_out ?? $record->check_in,
                    ];
                });

            return view('dashboard', compact(
                'attendance',
                'lastCheckIn',
                'lastCheckOut',
                'todayHours',
                'recentActivity'
            ));
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'An error occurred. Please try again.');
        }
    }

    public function checkIn()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login to check in.');
            }

            $today = Carbon::today();

            // Check if already checked in today
            $existingAttendance = Attendance::where('user_id', $user->id)
                ->whereDate('check_in', $today)
                ->first();

            if ($existingAttendance) {
                return back()->with('error', 'You have already checked in today.');
            }

            // Create new attendance record
            Attendance::create([
                'user_id' => $user->id,
                'check_in' => Carbon::now(),
                'status' => 'present'
            ]);

            return back()->with('success', 'Successfully checked in!');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while checking in. Please try again.');
        }
    }

    public function checkOut()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login to check out.');
            }

            $today = Carbon::today();

            // Get today's attendance
            $attendance = Attendance::where('user_id', $user->id)
                ->whereDate('check_in', $today)
                ->first();

            if (!$attendance) {
                return back()->with('error', 'You must check in before checking out.');
            }

            if ($attendance->check_out) {
                return back()->with('error', 'You have already checked out today.');
            }

            // Update attendance record
            $attendance->update([
                'check_out' => Carbon::now()
            ]);

            return back()->with('success', 'Successfully checked out!');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while checking out. Please try again.');
        }
    }

    public function showLeaveRequest()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login to access leave requests.');
            }
            return view('leave.request');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'An error occurred. Please try again.');
        }
    }
} 