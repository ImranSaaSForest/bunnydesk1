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
        Schema::create('payrun_employee_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payrun_employee_id')->constrained()->onDelete('cascade');
            $table->double('taxable');
            $table->double('tax');
            $table->double('net_pay');
            $table->double('workman')->default(0);
            $table->double('workman_percentage')->default(0);
            $table->double('employee_social_security')->default(0);
            $table->float('employee_social_security_percentage')->default(0);
            $table->double('employer_social_security')->default(0);
            $table->float('employer_social_security_percentage')->default(0);
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
        Schema::dropIfExists('payrun_employee_payments');
    }
};
