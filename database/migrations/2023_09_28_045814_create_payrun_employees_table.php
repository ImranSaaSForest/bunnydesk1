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
        Schema::create('payrun_employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('payrun_id')->constrained()->onDelete('cascade');
            $table->float('total_working_hours');
            $table->string('payrun_employee_id')->unique();
            $table->float('total_paid_leave')->nullable();
            $table->float('total_unpaid_leave')->nullable();
            $table->double('gross_salary');
            $table->boolean('include_in_payrun')->default(1);
            $table->boolean('refresh')->default(0);
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrun_employees');
    }
};
