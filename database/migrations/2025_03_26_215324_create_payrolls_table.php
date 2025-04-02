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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->string('cin');
            $table->foreign('cin')->references('cin')->on('employees')->onDelete('cascade');
            $table->decimal('base_salary', 10, 2);
            $table->decimal('overtime_hours', 5, 2)->default(0);
            $table->decimal('overtime_rate', 10, 2)->default(0);
            $table->decimal('bonus', 10, 2)->default(0);
            $table->decimal('benefits', 10, 2)->default(0);
            $table->decimal('taxes', 10, 2)->default(0);
            $table->decimal('deductions', 10, 2)->default(0);
            $table->decimal('net_salary', 10, 2);
            $table->date('pay_date');
            $table->string('payment_status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
