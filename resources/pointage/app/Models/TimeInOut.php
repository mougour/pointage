<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeInOut extends Model
{
    // If your table is not named "time_in_outs", you can specify the table name
    protected $table = 'time_in_outs'; // Adjust according to your table name

    // Mass assignable fields
    protected $fillable = [
        'cin',          // Assuming 'cin' is the foreign key for Employee
        'date_debut',   // Start time
        'date_fin',     // End time (nullable)
    ];

    // If the table does not have created_at and updated_at columns, set this to false
    public $timestamps = true;  // Set to false if you don't have timestamps

    // Relationship with Employee
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'cin', 'cin');
    }
}
