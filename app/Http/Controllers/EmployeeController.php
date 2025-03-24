<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\Attendance; // Import Attendance model
use Illuminate\Support\Facades\Log;
use App\Models\PauseTime;

class EmployeeController extends Controller
{
    public function search(Request $request)
{
    $query = $request->input('query');
    $employees = Employee::where('fullName', 'LIKE', "%{$query}%")
        ->orWhere('cin', 'LIKE', "%{$query}%")
        ->get();

    return view('employees.index', compact('employees'));
}

    public function index()
    {
        $employees = Employee::all();
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'cin' => 'required|unique:employees',
            'fullName' => 'required',
            'email' => 'required|email|unique:employees',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'position' => 'required',
            'department' => 'required',
        ]);

        Employee::create($request->all());

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully.');
    }

    public function show($cin)
    {
        $employee = Employee::where('cin', $cin)->firstOrFail();
        
        // Get the employee's recent attendance records
        $recentAttendance = Attendance::where('cin', $cin)
            ->orderBy('date', 'desc')
            ->limit(10)
            ->get();
            
        // Check if employee is currently checked in
        $todayAttendance = Attendance::where('cin', $cin)
            ->where('date', now()->toDateString())
            ->first();
            
        $isCheckedIn = false;
        $isPaused = false;
        $activePause = null;
        
        if ($todayAttendance && $todayAttendance->check_in && !$todayAttendance->check_out) {
            $isCheckedIn = true;
            
            // Check if there's an active pause
            $activePause = PauseTime::where('attendance_id', $todayAttendance->id)
                ->whereNotNull('start_time')
                ->whereNull('end_time')
                ->first();
                
            $isPaused = $activePause ? true : false;
        }
        
        return view('employees.show', compact('employee', 'recentAttendance', 'isCheckedIn', 'isPaused', 'todayAttendance', 'activePause'));
    }

    public function edit($cin)
    {
        $employee = Employee::where('cin', $cin)->firstOrFail();
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, $cin)
    {
        $request->validate([
            'fullName' => 'required',
            'email' => 'required|email|unique:employees,email,' . $cin . ',cin',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'position' => 'required',
            'department' => 'required',
        ]);

        $employee = Employee::where('cin', $cin)->firstOrFail();
        $employee->update($request->all());

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy($cin)
    {
        $employee = Employee::where('cin', $cin)->firstOrFail();
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }

    public function showCustomizedPayroll($cin)
{
        // Initialize variables to avoid undefined variable errors
        $totalHoursWorked = 0;
        $averageWage = 0;
        
        return view('customized-payroll', compact('cin', 'totalHoursWorked', 'averageWage'));
    }
    
    public function showStats($cin)
    {
        try {
            // If cin is a URL parameter, use that, otherwise check if it's a form field
            if ($cin == ':cin' && request()->has('employee_cin')) {
                $cin = request('employee_cin');
            }
            
            // Find the employee by CIN
            $employee = Employee::where('cin', $cin)->firstOrFail();
            
        $startDate = request('start_date');
        $endDate = request('end_date');
        
            // Calculate total hours worked directly, accounting for pauses
            $records = Attendance::where('cin', $employee->cin)
            ->whereBetween('date', [$startDate, $endDate])
            ->where('status', 'present')
                ->whereNotNull('check_in')
                ->whereNotNull('check_out')
                ->get();
                
            $totalHoursWorked = $records->sum(function($attendance) {
                $checkIn = new \DateTime($attendance->check_in);
                $checkOut = new \DateTime($attendance->check_out);
                $diff = $checkIn->diff($checkOut);
                
                // Calculate hours as decimal
                $hours = $diff->h + ($diff->i / 60) + ($diff->s / 3600);
                
                // Subtract pause time if available
                $pauseMinutes = $attendance->total_pause_time ?: 0;
                $hoursWorked = $hours - ($pauseMinutes / 60);
                
                return max(0, $hoursWorked);
            });
            
            // Calculate total pause time for display
            $totalPauseTime = $records->sum(function($attendance) {
                return ($attendance->total_pause_time ?: 0) / 60; // Convert minutes to hours
            });

        // Average wage data (this should ideally come from a database or configuration)
        $averageWages = [
            'Administration' => [
                'Receptionist' => 17,
                'Office Manager' => 40,
                'Data Entry Clerk' => 22,
                'Executive Assistant' => 35,
            ],
            'Human Resources (HR)' => [
                'HR Assistant' => 32,
                'HR Manager' => 55,
                'Training Coordinator' => 38,
                'Recruitment Officer' => 42,
            ],
            'Finance & Accounting' => [
                'Accountant' => 40,
                'Finance Manager' => 75,
                'Payroll Specialist' => 45,
                'Auditor' => 80,
            ],
            'Sales & Marketing' => [
                'Sales Representative' => 28,
                'Marketing Specialist' => 48,
                'Social Media Manager' => 50,
                'Public Relations (PR) Officer' => 55,
            ],
            'IT & Technical' => [
                'IT Technician' => 45,
                'Software Developer' => 85,
                'System Administrator' => 50,
                'Cybersecurity Specialist' => 90,
            ],
            'Operations & Logistics' => [
                'Warehouse Supervisor' => 38,
                'Operations Manager' => 70,
                'Supply Chain Coordinator' => 45,
                'Transport Manager' => 65,
                'Technical Support Specialist' => 35,
                'Client Relations Officer' => 45,
            ],
            'Engineering' => [
                'Mechanical Engineer' => 75,
                'Electrical Engineer' => 80,
                'Civil Engineer' => 85,
                'Industrial Engineer' => 70,
            ],
            'Construction' => [
                'Site Supervisor' => 55,
                'Project Manager' => 90,
                'Architect' => 100,
            ],
            'Manufacturing' => [
                'Production Manager' => 60,
                'Quality Assurance Inspector' => 45,
                'Factory Worker' => 25,
            ],
            'Security & Maintenance' => [
                'Security Guard' => 22,
                'Maintenance Technician' => 35,
                'Facility Manager' => 50,
            ],
            'Education & Training' => [
                'Teacher' => 40,
                'Trainer' => 50,
                'University Professor' => 100,
            ],
            'Healthcare' => [
                'Nurse' => 50,
                'Doctor' => 150,
                'Pharmacist' => 120,
            ],
            'Senior Management' => [
                'CEO / General Manager' => 200,
                'Chief Operating Officer (COO)' => 180,
                'Chief Financial Officer (CFO)' => 220,
            ],
        ];

        $averageWage = isset($averageWages[$employee->department][$employee->position]) 
            ? $averageWages[$employee->department][$employee->position] 
            : 0; // Set to 0 if not found

            return view('customized-payroll', compact('employee', 'totalHoursWorked', 'averageWage', 'records', 'totalPauseTime'));
        } catch (\Exception $e) {
            // Log any errors that occurred
            \Illuminate\Support\Facades\Log::error('Error generating payroll report', [
                'cin' => $cin ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return to the form with an error message
            return redirect()->route('customized.payroll')
                ->with('error', 'There was an error generating the payroll report: ' . $e->getMessage());
        }
    }

    public function checkIn(Request $request, $cin)
    {
        $employee = Employee::where('cin', $cin)->firstOrFail();
        $today = now()->toDateString();
        
        // Check if there's already an attendance record for today
        $attendance = Attendance::where('cin', $cin)
            ->where('date', $today)
            ->first();
        
        if ($attendance && $attendance->check_in) {
            return response()->json([
                'status' => 'error',
                'message' => 'Employee already checked in today'
            ], 400);
        }
        
        // Create a new attendance record if one doesn't exist
        if (!$attendance) {
            try {
                $attendance = new Attendance([
                    'cin' => $cin,
                    'date' => $today,
                    'status' => 'pending',
                    'check_in' => now(),
                    'check_out' => null,
                    'hours_worked' => 0,
                    'total_pause_time' => 0,
                    'notes' => ''
                ]);
                $attendance->save();
            } catch (\Exception $e) {
                Log::error('Error creating attendance record', [
                    'cin' => $cin,
                    'date' => $today,
                    'error' => $e->getMessage()
                ]);
                
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error creating attendance record: ' . $e->getMessage()
                ], 500);
            }
        } else {
            // Set check-in time
            $attendance->check_in = now();
            $attendance->save();
        }
        
        Log::info('Employee checked in', ['cin' => $cin, 'time' => $attendance->check_in]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Checked in successfully',
            'data' => [
                'attendance_id' => $attendance->id,
                'check_in' => $attendance->check_in,
                'date' => $attendance->date
            ]
        ]);
    }

    public function checkOut(Request $request, $cin)
    {
        $employee = Employee::where('cin', $cin)->firstOrFail();
        $today = now()->toDateString();
        
        // Find today's attendance record
        $attendance = Attendance::where('cin', $cin)
            ->where('date', $today)
            ->first();
        
        if (!$attendance || !$attendance->check_in) {
            return response()->json([
                'status' => 'error',
                'message' => 'No check-in record found for today'
            ], 400);
        }
        
        if ($attendance->check_out) {
            return response()->json([
                'status' => 'error',
                'message' => 'Employee already checked out today'
            ], 400);
        }
        
        // Handle pause times if available in request
        if ($request->has('pauseLog') && is_array($request->pauseLog)) {
            foreach ($request->pauseLog as $pause) {
                $startTime = new \DateTime($pause['start']);
                $endTime = new \DateTime($pause['end']);
                
                $pauseTime = new PauseTime([
                    'attendance_id' => $attendance->id,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                ]);
                
                $pauseTime->save();
                $pauseTime->calculateDuration();
            }
            
            // Update total pause time
            $totalPauseMinutes = $attendance->pauseTimes()->sum('duration_minutes');
            $attendance->total_pause_time = $totalPauseMinutes;
        }
        
        // Set check-out time
        $attendance->check_out = now();
        $attendance->save();
        
        // Calculate hours worked (but don't store it in database)
        $checkIn = new \DateTime($attendance->check_in);
        $checkOut = new \DateTime($attendance->check_out);
        $diff = $checkIn->diff($checkOut);
        $hours = $diff->h + ($diff->i / 60) + ($diff->s / 3600);
        $hoursWorked = $hours - ($attendance->total_pause_time / 60);
        $hoursWorked = max(0, $hoursWorked);
        
        Log::info('Employee checked out', [
            'cin' => $cin, 
            'check_in' => $attendance->check_in,
            'check_out' => $attendance->check_out,
            'hours_worked' => $hoursWorked,
            'total_pause_time' => $attendance->total_pause_time
        ]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Checked out successfully',
            'data' => [
                'attendance_id' => $attendance->id,
                'check_in' => $attendance->check_in,
                'check_out' => $attendance->check_out,
                'hours_worked' => $hoursWorked,
                'total_pause_time' => $attendance->total_pause_time,
                'status' => $attendance->status
            ]
        ]);
    }

    public function pauseStart(Request $request, $cin)
    {
        $employee = Employee::where('cin', $cin)->firstOrFail();
        $today = now()->toDateString();
        
        // Find today's attendance record
        $attendance = Attendance::where('cin', $cin)
            ->where('date', $today)
            ->first();
        
        if (!$attendance || !$attendance->check_in) {
            return response()->json([
                'status' => 'error',
                'message' => 'No check-in record found for today'
            ], 400);
        }
        
        if ($attendance->check_out) {
            return response()->json([
                'status' => 'error',
                'message' => 'Employee already checked out today'
            ], 400);
        }
        
        // Check if there's an active pause
        $activePause = PauseTime::where('attendance_id', $attendance->id)
            ->whereNull('end_time')
            ->first();
        
        if ($activePause) {
            return response()->json([
                'status' => 'error',
                'message' => 'There is already an active pause'
            ], 400);
        }
        
        // Create a new pause record
        $pauseTime = new PauseTime([
            'attendance_id' => $attendance->id,
            'start_time' => now(),
            'reason' => $request->input('reason', '')
        ]);
        
        $pauseTime->save();
        
        Log::info('Employee started pause', [
            'cin' => $cin, 
            'pause_id' => $pauseTime->id,
            'start_time' => $pauseTime->start_time
        ]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Pause started successfully',
            'data' => [
                'pause_id' => $pauseTime->id,
                'start_time' => $pauseTime->start_time
            ]
        ]);
    }

    public function pauseEnd(Request $request, $cin)
    {
        $employee = Employee::where('cin', $cin)->firstOrFail();
        $today = now()->toDateString();
        
        // Find today's attendance record
        $attendance = Attendance::where('cin', $cin)
            ->where('date', $today)
            ->first();
        
        if (!$attendance) {
            return response()->json([
                'status' => 'error',
                'message' => 'No attendance record found for today'
            ], 400);
        }
        
        // Find the active pause
        $activePause = PauseTime::where('attendance_id', $attendance->id)
            ->whereNull('end_time')
            ->first();
        
        if (!$activePause) {
            return response()->json([
                'status' => 'error',
                'message' => 'No active pause found'
            ], 400);
        }
        
        // End the pause
        $activePause->end_time = now();
        $activePause->save();
        
        // Calculate duration
        $durationMinutes = $activePause->calculateDuration();
        
        // Update total pause time
        $totalPauseMinutes = $attendance->pauseTimes()->sum('duration_minutes');
        $attendance->total_pause_time = $totalPauseMinutes;
        $attendance->save();
        
        Log::info('Employee ended pause', [
            'cin' => $cin, 
            'pause_id' => $activePause->id,
            'end_time' => $activePause->end_time,
            'duration_minutes' => $durationMinutes
        ]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Pause ended successfully',
            'data' => [
                'pause_id' => $activePause->id,
                'start_time' => $activePause->start_time,
                'end_time' => $activePause->end_time,
                'duration_minutes' => $durationMinutes,
                'total_pause_time' => $attendance->total_pause_time
            ]
        ]);
    }

    /**
     * Get employee data for editing
     */
    public function getData($cin)
    {
        $employee = Employee::where('cin', $cin)->firstOrFail();
        
        return response()->json([
            'phone' => $employee->phone,
            'address' => $employee->address,
            'date_of_birth' => $employee->date_of_birth ? date('Y-m-d', strtotime($employee->date_of_birth)) : null,
            'gender' => $employee->gender,
        ]);
    }
    
    /**
     * Handle attendance log requests from the employee details page
     */
    public function logAttendance(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'action' => 'required|string|in:check_in,check_out,pause_start,pause_end',
                'cin' => 'required|exists:employees,cin',
                'timestamp' => 'required|date'
            ]);
            
            $employee = Employee::where('cin', $request->cin)->firstOrFail();
            $action = $request->action;
            
            switch ($action) {
                case 'check_in':
                    return $this->handleCheckIn($employee);
                    
                case 'check_out':
                    return $this->handleCheckOut($employee);
                    
                case 'pause_start':
                    return $this->handlePauseStart($employee);
                    
                case 'pause_end':
                    return $this->handlePauseEnd($employee);
                    
                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid action specified'
                    ], 400);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error logging attendance', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Handle check-in action
     */
    private function handleCheckIn($employee)
    {
        $today = now()->toDateString();
        
        // Check if there's already an attendance record for today
        $attendance = Attendance::where('cin', $employee->cin)
            ->where('date', $today)
            ->first();
        
        if ($attendance && $attendance->check_in) {
            return response()->json([
                'success' => false,
                'message' => 'Employee already checked in today'
            ], 400);
        }
        
        // Create a new attendance record if one doesn't exist
        if (!$attendance) {
            try {
                $attendance = new Attendance([
                    'cin' => $employee->cin,
                    'date' => $today,
                    'status' => 'present',
                    'check_in' => now(),
                    'check_out' => null,
                    'hours_worked' => 0,
                    'total_pause_time' => 0,
                    'notes' => ''
                ]);
                $attendance->save();
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Error creating attendance record', [
                    'cin' => $employee->cin,
                    'date' => $today,
                    'error' => $e->getMessage()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating attendance record: ' . $e->getMessage()
                ], 500);
            }
        } else {
            // Set check-in time for existing record
            $attendance->check_in = now();
            $attendance->save();
        }
        
        Log::info('Employee checked in', ['cin' => $employee->cin, 'time' => $attendance->check_in]);
        
        return response()->json([
            'success' => true,
            'message' => 'Checked in successfully',
            'data' => [
                'attendance_id' => $attendance->id,
                'check_in' => $attendance->check_in,
                'date' => $attendance->date
            ]
        ]);
    }
    
    /**
     * Handle check-out action
     */
    private function handleCheckOut($employee)
    {
        $today = now()->toDateString();
        
        // Find today's attendance record
        $attendance = Attendance::where('cin', $employee->cin)
            ->where('date', $today)
            ->first();
        
        if (!$attendance || !$attendance->check_in) {
            return response()->json([
                'success' => false,
                'message' => 'No check-in record found for today'
            ], 400);
        }
        
        if ($attendance->check_out) {
            return response()->json([
                'success' => false,
                'message' => 'Employee already checked out today'
            ], 400);
        }
        
        // End any active pauses
        $activePause = PauseTime::where('attendance_id', $attendance->id)
            ->whereNull('end_time')
            ->first();
            
        if ($activePause) {
            $activePause->end_time = now();
            $activePause->save();
            $activePause->calculateDuration();
            
            // Update total pause time
            $totalPauseMinutes = $attendance->pauseTimes()->sum('duration_minutes');
            $attendance->total_pause_time = $totalPauseMinutes;
        }
        
        // Set check-out time
        $attendance->check_out = now();
        $attendance->save();
        
        Log::info('Employee checked out', [
            'cin' => $employee->cin, 
            'check_in' => $attendance->check_in,
            'check_out' => $attendance->check_out,
            'total_pause_time' => $attendance->total_pause_time
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Checked out successfully',
            'data' => [
                'attendance_id' => $attendance->id,
                'check_in' => $attendance->check_in,
                'check_out' => $attendance->check_out,
                'total_pause_time' => $attendance->total_pause_time,
                'status' => $attendance->status
            ]
        ]);
    }
    
    /**
     * Handle pause start action
     */
    private function handlePauseStart($employee)
    {
        $today = now()->toDateString();
        
        // Find today's attendance record
        $attendance = Attendance::where('cin', $employee->cin)
            ->where('date', $today)
            ->first();
        
        if (!$attendance || !$attendance->check_in) {
            return response()->json([
                'success' => false,
                'message' => 'No check-in record found for today'
            ], 400);
        }
        
        if ($attendance->check_out) {
            return response()->json([
                'success' => false,
                'message' => 'Employee already checked out today'
            ], 400);
        }
        
        // Check if there's an active pause
        $activePause = PauseTime::where('attendance_id', $attendance->id)
            ->whereNull('end_time')
            ->first();
        
        if ($activePause) {
            return response()->json([
                'success' => false,
                'message' => 'There is already an active pause'
            ], 400);
        }
        
        // Create a new pause record
        $pauseTime = new PauseTime([
            'attendance_id' => $attendance->id,
            'start_time' => now()
        ]);
        
        $pauseTime->save();
        
        Log::info('Employee started pause', [
            'cin' => $employee->cin, 
            'pause_id' => $pauseTime->id,
            'start_time' => $pauseTime->start_time
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Pause started successfully',
            'data' => [
                'pause_id' => $pauseTime->id,
                'start_time' => $pauseTime->start_time
            ]
        ]);
    }
    
    /**
     * Handle pause end action
     */
    private function handlePauseEnd($employee)
    {
        $today = now()->toDateString();
        
        // Find today's attendance record
        $attendance = Attendance::where('cin', $employee->cin)
            ->where('date', $today)
            ->first();
        
        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'No attendance record found for today'
            ], 400);
        }
        
        // Find the active pause
        $activePause = PauseTime::where('attendance_id', $attendance->id)
            ->whereNull('end_time')
            ->first();
        
        if (!$activePause) {
            return response()->json([
                'success' => false,
                'message' => 'No active pause found'
            ], 400);
        }
        
        // End the pause
        $activePause->end_time = now();
        $activePause->save();
        
        // Calculate duration
        $durationMinutes = $activePause->calculateDuration();
        
        // Update total pause time
        $totalPauseMinutes = $attendance->pauseTimes()->sum('duration_minutes');
        $attendance->total_pause_time = $totalPauseMinutes;
        $attendance->save();
        
        Log::info('Employee ended pause', [
            'cin' => $employee->cin, 
            'pause_id' => $activePause->id,
            'end_time' => $activePause->end_time,
            'duration_minutes' => $durationMinutes
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Pause ended successfully',
            'data' => [
                'pause_id' => $activePause->id,
                'start_time' => $activePause->start_time,
                'end_time' => $activePause->end_time,
                'duration_minutes' => $durationMinutes,
                'total_pause_time' => $attendance->total_pause_time
            ]
        ]);
    }
}