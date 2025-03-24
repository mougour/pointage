<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->string('cin');
            $table->date('date');
            $table->dateTime('check_in')->nullable();
            $table->dateTime('check_out')->nullable();
            $table->decimal('hours_worked', 8, 2)->default(0);
            $table->decimal('total_pause_time', 8, 2)->default(0);
            $table->string('status')->default('pending'); // pending, present, absent
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('cin')->references('cin')->on('employees')->onDelete('cascade');
            $table->index(['cin', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}
