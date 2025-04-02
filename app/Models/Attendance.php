<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'cin',
        'date',
        'check_in',
        'check_out',
        'hours_worked',
        'total_pause_time',
        'status',
        'notes',
    ];

    /**
     * Get the employee associated with the attendance record.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'cin', 'cin');
    }

    /**
     * Get the pause times for this attendance record.
     */
    public function pauseTimes()
    {
        return $this->hasMany(PauseTime::class);
    }
    
    /**
     * Calculate the hours worked between check-in and check-out times.
     * Returns the calculated value but doesn't save it to the database.
     */
    public function calculateHoursWorked()
    {
        try {
            if ($this->check_in && $this->check_out) {
                $checkIn = new \DateTime($this->check_in);
                $checkOut = new \DateTime($this->check_out);
                
                // Ensure check-out is after check-in
                if ($checkOut <= $checkIn) {
                    return 0;
                }
                
                $diff = $checkIn->diff($checkOut);
                
                // Calculate total hours as decimal
                $hours = $diff->h + ($diff->i / 60) + ($diff->s / 3600);
                
                // Subtract pause time with safety check, ensuring we don't have negative value
                $pauseTime = max(0, $this->total_pause_time ?? 0);
                $pauseHours = $pauseTime > 0 ? ($pauseTime / 60) : 0;
                $hoursWorked = max(0, $hours - $pauseHours);
                
                // Mark as present
                $this->hours_worked = $hoursWorked;
                $this->status = 'present';
                $this->save();
                
                return $hoursWorked;
            }
            
            return 0;
        } catch (\Exception $e) {
            \Log::error('Hours Worked Calculation Error: ' . $e->getMessage());
            return 0;
        }
    }
}
