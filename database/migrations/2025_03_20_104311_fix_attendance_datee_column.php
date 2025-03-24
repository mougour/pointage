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
        // First check if datee column already exists
        if (!Schema::hasColumn('attendances', 'datee')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->date('datee')->nullable();
            });
        }
        
        // Ensure check_out is nullable
        Schema::table('attendances', function (Blueprint $table) {
            $table->dateTime('check_out')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('attendances', 'datee')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->dropColumn('datee');
            });
        }
    }
};
