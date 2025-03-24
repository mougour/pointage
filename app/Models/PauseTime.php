<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PauseTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'start_time',
        'end_time',
        'duration_minutes',
        'reason',
    ];

    /**
     * Get the attendance record that this pause time belongs to.
     */
    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    /**
     * Calculate the duration of the pause.
     */
    public function calculateDuration()
    {
        if ($this->start_time && $this->end_time) {
            $start = new \DateTime($this->start_time);
            $end = new \DateTime($this->end_time);
            $diff = $start->diff($end);
            
            // Calculate minutes as decimal
            $minutes = $diff->i + ($diff->h * 60) + ($diff->s / 60);
            
            $this->duration_minutes = $minutes;
            $this->save();
            
            return $minutes;
        }
        
        return 0;
    }
}
