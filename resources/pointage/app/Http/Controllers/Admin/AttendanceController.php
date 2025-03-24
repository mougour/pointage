<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function show(Employee $employee)
    {
        return view('admin.attendance.show', compact('employee'));
    }

    public function getData(Request $request, Employee $employee)
    {
        $month = $request->get('month', date('Y-m'));
        $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereBetween('datee', [$startDate, $endDate])
            ->orderBy('datee')
            ->get();

        $stats = [
            'present' => $attendance->where('status', 'present')->count(),
            'absent' => $attendance->where('status', 'absent')->count(),
            'late' => $attendance->where('status', 'late')->count(),
        ];

        $recent = Attendance::where('employee_id', $employee->id)
            ->orderBy('datee', 'desc')
            ->take(10)
            ->get()
            ->map(function ($record) {
                return [
                    'date' => $record->datee->format('Y-m-d'),
                    'status' => $record->status,
                    'notes' => $record->notes
                ];
            });

        return response()->json([
            'attendance' => $attendance->map(function ($record) {
                return [
                    'date' => $record->datee->format('Y-m-d'),
                    'status' => $record->status,
                    'notes' => $record->notes
                ];
            }),
            'stats' => $stats,
            'recent' => $recent
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'datee' => 'required|date',
            'status' => 'required|in:present,absent,late',
            'notes' => 'nullable|string'
        ]);

        $attendance = Attendance::updateOrCreate(
            [
                'employee_id' => $validated['employee_id'],
                'datee' => $validated['datee']
            ],
            [
                'status' => $validated['status'],
                'notes' => $validated['notes']
            ]
        );

        return response()->json([
            'message' => 'Attendance recorded successfully',
            'attendance' => $attendance
        ]);
    }

    public function checkin(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'notes' => 'nullable|string'
        ]);

        $now = Carbon::now();
        $status = $now->hour > 9 ? 'late' : 'present';

        $attendance = Attendance::updateOrCreate(
            [
                'employee_id' => $validated['employee_id'],
                'datee' => $now->toDateString()
            ],
            [
                'status' => $status,
                'notes' => $validated['notes'] ?? 'Checked in at ' . $now->format('H:i:s')
            ]
        );

        return response()->json([
            'message' => 'Checked in successfully',
            'attendance' => $attendance
        ]);
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'notes' => 'nullable|string'
        ]);

        $now = Carbon::now();
        $attendance = Attendance::where('employee_id', $validated['employee_id'])
            ->where('datee', $now->toDateString())
            ->first();

        if (!$attendance) {
            return response()->json([
                'message' => 'No check-in record found for today'
            ], 400);
        }

        $attendance->update([
            'notes' => ($attendance->notes ? $attendance->notes . ' | ' : '') . 
                      'Checked out at ' . $now->format('H:i:s') . 
                      ($validated['notes'] ? ' - ' . $validated['notes'] : '')
        ]);

        return response()->json([
            'message' => 'Checked out successfully',
            'attendance' => $attendance
        ]);
    }
} 