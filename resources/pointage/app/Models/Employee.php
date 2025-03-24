<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    // If your table is not named "employees", you can specify the table name
    protected $table = 'employees'; // Adjust this according to your table name

    // Mass assignable fields
    protected $fillable = [
        'name',
        'email',
        'position',
        'department',
        'is_active'
    ];

    // If the table does not have created_at and updated_at columns, set this to false
    public $timestamps = true;  // Set to false if you don't have timestamps

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relationship with TimeInOut (Attendance)
    public function timeInOuts()
    {
        return $this->hasMany(TimeInOut::class, 'cin', 'cin');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
