<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->string('cin'); // Foreign key to employees
            $table->date('datee'); // Date of attendance
            $table->time('check_in'); // Check-in time
            $table->time('check_out'); // Check-out time

            $table->enum('status', ['present', 'absent']); // Attendance status
            $table->timestamps(); // Created at and updated at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
