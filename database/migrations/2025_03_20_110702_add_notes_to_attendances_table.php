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
            // Add the notes column if it doesn't exist
            if (!Schema::hasColumn('attendances', 'notes')) {
                $table->text('notes')->nullable()->after('total_pause_time');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Drop the notes column if it exists
            if (Schema::hasColumn('attendances', 'notes')) {
                $table->dropColumn('notes');
            }
        });
    }
};
