<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $primaryKey = 'cin';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'cin',
        'fullName',
        'email',
        'phone',
        'address',
        'date_of_birth',
        'gender',
        'position',
        'department',
        'isActive'
    ];

    protected $casts = [
        'isActive' => 'boolean',
        'date_of_birth' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the attendance records for the employee.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'cin', 'cin');
    }

    /**
     * Get the current day's attendance record.
     */
    public function todayAttendance()
    {
        return $this->attendances()
            ->where('date', now()->toDateString())
            ->first();
    }
    
    /**
     * Check if the employee is currently checked in.
     */
    public function isCheckedIn()
    {
        $attendance = $this->todayAttendance();
        return $attendance && $attendance->check_in && !$attendance->check_out;
    }
    
    /**
     * Get the recent attendance records.
     */
    public function recentAttendance()
    {
        return $this->attendances()
            ->orderBy('date', 'desc')
            ->take(10);
    }
}
