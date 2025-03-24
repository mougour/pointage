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
        Schema::table('attendances', function (Blueprint $table) {
            // Add the hours_worked column if it doesn't exist
            if (!Schema::hasColumn('attendances', 'hours_worked')) {
                $table->decimal('hours_worked', 8, 2)->default(0)->after('check_out');
            }
            
            // Also check for total_pause_time column
            if (!Schema::hasColumn('attendances', 'total_pause_time')) {
                $table->decimal('total_pause_time', 8, 2)->default(0)->after('hours_worked');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Drop the columns if they exist
            if (Schema::hasColumn('attendances', 'hours_worked')) {
                $table->dropColumn('hours_worked');
            }
            
            if (Schema::hasColumn('attendances', 'total_pause_time')) {
                $table->dropColumn('total_pause_time');
            }
        });
    }
};
