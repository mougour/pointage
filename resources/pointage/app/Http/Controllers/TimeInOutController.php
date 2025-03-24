<?php

namespace App\Http\Controllers;

use App\Models\TimeInOut;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TimeInOutController extends Controller
{
    // Create an attendance record
    public function store(Request $request)
    {
        $request->validate([
            'cin' => 'required|exists:employees,cin',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut',
        ]);

        $attendance = TimeInOut::create([
            'cin' => $request->cin,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
        ]);

        return response()->json([
            'message' => 'Attendance record created successfully',
            'data' => $attendance,
        ], 201); // Created status code
    }

    // Get all attendance records
    public function index()
    {
        $attendances = TimeInOut::all();

        return response()->json([
            'message' => 'Attendance records retrieved successfully',
            'data' => $attendances,
        ]);
    }

    // Update an attendance record
    public function update(Request $request, $id)
    {
        $request->validate([
            'cin' => 'required|exists:employees,cin',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut',
        ]);

        $attendance = TimeInOut::findOrFail($id);
        $attendance->update($request->all());

        return response()->json([
            'message' => 'Attendance record updated successfully',
            'data' => $attendance,
        ]);
    }

    // Delete an attendance record
    public function destroy($id)
    {
        $attendance = TimeInOut::findOrFail($id);
        $attendance->delete();

        return response()->json([
            'message' => 'Attendance record deleted successfully',
        ]);
    }

    // Generate attendance report for an employee over a date range
    public function generateReport(Request $request)
    {
        $request->validate([
            'cin' => 'required|exists:employees,cin', // Employee identification number
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Fetch attendance records for the employee within the specified date range
        $attendances = TimeInOut::where('cin', $request->cin)
                                ->whereBetween('date_debut', [$request->start_date, $request->end_date])
                                ->get();

        // Calculate total hours worked
        $totalHours = 0;
        foreach ($attendances as $attendance) {
            $startTime = Carbon::parse($attendance->date_debut);
            $endTime = $attendance->date_fin ? Carbon::parse($attendance->date_fin) : Carbon::now();

            // Make sure to calculate the difference in hours correctly
            $totalHours += $startTime->diffInHours($endTime);

            // Optionally, you can also calculate minutes
            // $totalMinutes += $startTime->diffInMinutes($endTime);
        }

        return response()->json([
            'message' => 'Attendance report generated successfully',
            'data' => [
                'attendances' => $attendances,
                'total_hours' => $totalHours,
            ],
        ]);
    }
}
